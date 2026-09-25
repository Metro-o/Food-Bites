<?php
/**
 * FoodBites — Cart API
 * Handles AJAX cart operations (add, update, remove, get)
 * All endpoints require the user to be logged in.
 */
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    jsonResponse(false, 'Please login to manage your cart.', ['redirect' => BASE_URL . '/auth/login.php']);
}

$action = $_REQUEST['action'] ?? '';
$db     = getDB();
$uid    = currentUserId();

switch ($action) {

    // ── Add item ──────────────────────────────────────────────
    case 'add':
        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty       = max(1, (int) ($_POST['quantity'] ?? 1));

        if ($productId <= 0) {
            jsonResponse(false, 'Invalid product.');
        }

        // Verify product exists and is active
        $prod = $db->prepare('SELECT id, name FROM products WHERE id = ? AND status = "active"');
        $prod->execute([$productId]);
        if (!$prod->fetch()) {
            jsonResponse(false, 'Product not available.');
        }

        // INSERT or UPDATE quantity
        $stmt = $db->prepare(
            'INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)'
        );
        $stmt->execute([$uid, $productId, $qty]);

        // Return new count
        $count = $db->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?');
        $count->execute([$uid]);
        jsonResponse(true, 'Added to cart!', ['count' => (int) $count->fetchColumn()]);

    // ── Update quantity ───────────────────────────────────────
    case 'update':
        $cartId = (int) ($_POST['cart_id'] ?? 0);
        $qty    = (int) ($_POST['quantity'] ?? 1);

        if ($qty <= 0) {
            // Remove item
            $stmt = $db->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
            $stmt->execute([$cartId, $uid]);
            $msg = 'Item removed.';
        } else {
            $stmt = $db->prepare('UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([$qty, $cartId, $uid]);
            $msg = 'Cart updated.';
        }

        // Recalculate cart totals
        $items    = getCart();
        $subtotal = cartSubtotal($items);
        $count    = array_sum(array_column($items, 'quantity'));

        jsonResponse(true, $msg, [
            'count'    => $count,
            'subtotal' => $subtotal,
            'total'    => $subtotal + DELIVERY_FEE,
            'subtotal_fmt' => formatPrice($subtotal),
            'total_fmt'    => formatPrice($subtotal + DELIVERY_FEE),
        ]);

    // ── Remove item ───────────────────────────────────────────
    case 'remove':
        $cartId = (int) ($_POST['cart_id'] ?? 0);
        $stmt   = $db->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
        $stmt->execute([$cartId, $uid]);

        $items    = getCart();
        $subtotal = cartSubtotal($items);
        $count    = array_sum(array_column($items, 'quantity'));

        jsonResponse(true, 'Item removed.', [
            'count'        => $count,
            'subtotal_fmt' => formatPrice($subtotal),
            'total_fmt'    => formatPrice($subtotal + DELIVERY_FEE),
        ]);

    // ── Get cart count ────────────────────────────────────────
    case 'count':
        $count = $db->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?');
        $count->execute([$uid]);
        jsonResponse(true, '', ['count' => (int) $count->fetchColumn()]);

    default:
        jsonResponse(false, 'Unknown action.');
}
