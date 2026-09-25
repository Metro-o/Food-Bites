<?php
/**
 * FoodBites — KDS API
 * Returns active orders (JSON) for real-time KDS polling
 * Also handles status updates from kitchen staff
 */
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json');

// KDS accessible to kitchen + admin roles
if (!isLoggedIn() || !isKitchen()) {
    http_response_code(401);
    jsonResponse(false, 'Unauthorized');
}

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

// ── GET: Return active orders ──────────────────────────────────────────────
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'orders';

    if ($action === 'orders') {
        $stmt = $db->prepare(
            "SELECT o.id, o.status, o.delivery_location, o.delivery_time,
                    o.payment_method, o.notes, o.created_at, o.updated_at,
                    u.name AS customer_name, u.phone AS customer_phone
             FROM orders o
             JOIN users u ON o.user_id = u.id
             WHERE o.status IN ('pending','preparing','ready')
             ORDER BY
               FIELD(o.status, 'pending', 'preparing', 'ready'),
               o.created_at ASC"
        );
        $stmt->execute();
        $orders = $stmt->fetchAll();

        // Attach order items
        foreach ($orders as &$order) {
            $items = $db->prepare(
                'SELECT oi.quantity, oi.price, p.name AS product_name
                 FROM order_items oi
                 JOIN products p ON oi.product_id = p.id
                 WHERE oi.order_id = ?'
            );
            $items->execute([$order['id']]);
            $order['items']        = $items->fetchAll();
            $order['elapsed_mins'] = elapsedMinutes($order['created_at']);
            $order['is_delayed']   = $order['elapsed_mins'] >= 20 && $order['status'] !== 'ready';
        }
        unset($order);

        echo json_encode(['success' => true, 'orders' => $orders, 'server_time' => time()]);
        exit;
    }

    // Last order timestamp (lightweight ping for new-order detection)
    if ($action === 'ping') {
        $stmt = $db->query("SELECT MAX(created_at) FROM orders WHERE status = 'pending'");
        echo json_encode(['success' => true, 'latest' => $stmt->fetchColumn()]);
        exit;
    }
}

// ── POST: Update order status ──────────────────────────────────────────────
if ($method === 'POST') {
    $orderId   = (int) ($_POST['order_id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';

    $allowed = ['preparing', 'ready'];
    if ($orderId <= 0 || !in_array($newStatus, $allowed)) {
        jsonResponse(false, 'Invalid request.');
    }

    // Only allow valid transitions
    $cur = $db->prepare('SELECT status FROM orders WHERE id = ?');
    $cur->execute([$orderId]);
    $current = $cur->fetchColumn();

    $transitions = ['pending' => 'preparing', 'preparing' => 'ready'];
    if (($transitions[$current] ?? '') !== $newStatus) {
        jsonResponse(false, "Cannot change status from {$current} to {$newStatus}.");
    }

    $stmt = $db->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$newStatus, $orderId]);

    jsonResponse(true, "Order #{$orderId} → " . ucfirst($newStatus));
}

jsonResponse(false, 'Method not allowed.');
