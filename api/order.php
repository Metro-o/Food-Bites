<?php
/**
 * FoodBites — Order API
 * place_order, get_status, get_history
 */
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';
$db     = getDB();

switch ($action) {

    // ── Place order from checkout form ────────────────────────
    case 'place_order':
        requireRole('customer');
        $uid      = currentUserId();
        $location = trim($_POST['delivery_location'] ?? '');
        $time     = $_POST['delivery_time'] ?? null;
        $method   = $_POST['payment_method'] ?? 'cash';
        $mmPhone  = trim($_POST['mm_phone'] ?? '');
        $notes    = trim($_POST['notes'] ?? '');
        $type     = $_POST['order_type'] ?? 'standard';

        if (!$location) jsonResponse(false, 'Delivery location is required.');

        $allowed_methods = ['cash', 'mpesa', 'tigopesa', 'airtel'];
        if (!in_array($method, $allowed_methods)) $method = 'cash';

        // Get cart items
        $cartItems = getCart();
        if (empty($cartItems)) jsonResponse(false, 'Your cart is empty.');

        // Check all items still active
        foreach ($cartItems as $item) {
            if ($item['status'] !== 'active') {
                jsonResponse(false, "Sorry, '{$item['name']}' is no longer available.");
            }
        }

        $subtotal    = cartSubtotal($cartItems);
        $deliveryFee = (float) DELIVERY_FEE;
        $total       = $subtotal + $deliveryFee;

        // Begin transaction
        $db->beginTransaction();
        try {
            // Insert order
            $stmt = $db->prepare(
                'INSERT INTO orders (user_id, total_amount, delivery_fee, status, delivery_location,
                                     delivery_time, notes, payment_method, order_type)
                 VALUES (?, ?, ?, "pending", ?, ?, ?, ?, ?)'
            );
            $deliveryDateTime = $time ? date('Y-m-d H:i:s', strtotime($time)) : null;
            $stmt->execute([$uid, $total, $deliveryFee, $location, $deliveryDateTime, $notes, $method, $type]);
            $orderId = (int) $db->lastInsertId();

            // Insert order items
            $itemStmt = $db->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)'
            );
            foreach ($cartItems as $item) {
                $itemStmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['unit_price']]);
            }

            // Insert payment record
            $payStmt = $db->prepare(
                'INSERT INTO payments (order_id, method, amount, reference, status) VALUES (?, ?, ?, ?, ?)'
            );
            $reference = ($method !== 'cash' && $mmPhone !== '') ? $mmPhone : null;
            $payStmt->execute([$orderId, $method, $total, $reference, 'pending']);

            // Clear cart
            $db->prepare('DELETE FROM cart WHERE user_id = ?')->execute([$uid]);

            $db->commit();
            jsonResponse(true, 'Order placed successfully!', [
                'order_id' => $orderId,
                'redirect' => BASE_URL . '/customer/order_confirm.php?id=' . $orderId,
            ]);
        } catch (Exception $e) {
            $db->rollBack();
            error_log('[FoodBites] Order error: ' . $e->getMessage());
            jsonResponse(false, 'Failed to place order. Please try again.');
        }

    // ── Get order status (for tracking page polling) ──────────
    case 'get_status':
        $orderId = (int) ($_GET['order_id'] ?? 0);
        $uid     = currentUserId();

        $where  = 'o.id = ?';
        $params = [$orderId];

        if ($uid) {
            $where  .= ' AND o.user_id = ?';
            $params[] = $uid;
        }

        $stmt = $db->prepare("SELECT o.id, o.status, o.updated_at FROM orders o WHERE {$where}");
        $stmt->execute($params);
        $order = $stmt->fetch();

        if (!$order) jsonResponse(false, 'Order not found.');

        jsonResponse(true, '', [
            'status'     => $order['status'],
            'updated_at' => $order['updated_at'],
            'badge_html' => statusBadge($order['status']),
        ]);

    // ── Get order history for logged-in user ──────────────────
    case 'get_history':
        requireRole('customer');
        $uid  = currentUserId();
        $stmt = $db->prepare(
            'SELECT o.id, o.total_amount, o.status, o.delivery_location, o.delivery_time,
                    o.payment_method, o.order_type, o.created_at,
                    COUNT(oi.id) AS item_count
             FROM orders o
             LEFT JOIN order_items oi ON o.id = oi.order_id
             WHERE o.user_id = ?
             GROUP BY o.id
             ORDER BY o.created_at DESC'
        );
        $stmt->execute([$uid]);
        $orders = $stmt->fetchAll();

        foreach ($orders as &$ord) {
            $ord['total_fmt']    = formatPrice((float) $ord['total_amount']);
            $ord['time_ago']     = timeAgo($ord['created_at']);
            $ord['status_badge'] = statusBadge($ord['status']);
        }
        unset($ord);

        jsonResponse(true, '', ['orders' => $orders]);

    default:
        jsonResponse(false, 'Unknown action.');
}

function requireRole(string $role): void
{
    if (!isLoggedIn()) {
        jsonResponse(false, 'Please login.', ['redirect' => BASE_URL . '/auth/login.php']);
    }
    if ($_SESSION['user_role'] !== $role && $role !== 'any') {
        jsonResponse(false, 'Unauthorized.');
    }
}
