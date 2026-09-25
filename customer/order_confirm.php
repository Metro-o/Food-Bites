<?php
/**
 * FoodBites — Order Confirmation
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('customer');

$orderId = (int) ($_GET['id'] ?? 0);
if ($orderId <= 0) redirect(BASE_URL . '/index.php');

$db   = getDB();
$stmt = $db->prepare(
    'SELECT o.*, u.name AS customer_name, u.phone AS customer_phone
     FROM orders o JOIN users u ON o.user_id = u.id
     WHERE o.id = ? AND o.user_id = ?'
);
$stmt->execute([$orderId, currentUserId()]);
$order = $stmt->fetch();

if (!$order) {
    setFlash('error', 'Order not found.');
    redirect(BASE_URL . '/customer/order_history.php');
}

// Order items
$items = $db->prepare(
    'SELECT oi.*, p.name AS product_name, p.image FROM order_items oi
     JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?'
);
$items->execute([$orderId]);
$orderItems = $items->fetchAll();

$pageTitle = "Order #$orderId Confirmed";
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title"><i class="fa-solid fa-circle-check"></i> Order Confirmed!</h1>
    </div>
</section>

<section class="confirm-section">
    <div class="container container-narrow">

        <!-- Success Banner -->
        <div class="confirm-banner">
            <div class="confirm-checkmark animate-pop"><i class="fa-solid fa-circle-check"></i></div>
            <h2>Thank you, <?= e(explode(' ', $order['customer_name'])[0]) ?>!</h2>
            <p>Your order <strong>#<?= $orderId ?></strong> has been placed successfully and is now being processed.</p>
        </div>

        <!-- Order Details -->
        <div class="confirm-card">
            <div class="confirm-card-header">
                <h3>Order #<?= $orderId ?></h3>
                <?= statusBadge($order['status']) ?>
            </div>

            <div class="confirm-details-grid">
                <div class="confirm-detail">
                    <span class="detail-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <div>
                        <div class="detail-label">Delivery Location</div>
                        <div class="detail-value"><?= e($order['delivery_location']) ?></div>
                    </div>
                </div>
                <div class="confirm-detail">
                    <span class="detail-icon"><i class="fa-solid fa-clock"></i></span>
                    <div>
                        <div class="detail-label">Expected Delivery</div>
                        <div class="detail-value">
                            <?= $order['delivery_time']
                                ? date('D, d M Y H:i', strtotime($order['delivery_time']))
                                : 'As soon as possible' ?>
                        </div>
                    </div>
                </div>
                <div class="confirm-detail">
                    <span class="detail-icon"><i class="fa-solid fa-credit-card"></i></span>
                    <div>
                        <div class="detail-label">Payment</div>
                        <div class="detail-value"><?= ucfirst(str_replace(['mpesa','tigopesa','airtel'], ['M-Pesa', 'Tigo Pesa', 'Airtel Money'], $order['payment_method'])) ?></div>
                    </div>
                </div>
                <div class="confirm-detail">
                    <span class="detail-icon"><i class="fa-solid fa-calendar-day"></i></span>
                    <div>
                        <div class="detail-label">Ordered At</div>
                        <div class="detail-value"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <h4 class="confirm-items-title">Items Ordered</h4>
            <div class="confirm-items">
                <?php foreach ($orderItems as $item): ?>
                    <div class="confirm-item">
                        <img src="<?= productImage($item['image']) ?>" alt="<?= e($item['product_name']) ?>"
                             onerror="this.src='<?= BASE_URL ?>/assets/images/default_food.jpg'">
                        <div class="confirm-item-info">
                            <span class="confirm-item-name"><?= e($item['product_name']) ?></span>
                            <span class="confirm-item-qty">× <?= $item['quantity'] ?></span>
                        </div>
                        <div class="confirm-item-price"><?= formatPrice((float)$item['price'] * $item['quantity']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Total -->
            <div class="confirm-total">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span><?= formatPrice((float)$order['total_amount'] - (float)$order['delivery_fee']) ?></span>
                </div>
                <div class="summary-row">
                    <span>Delivery Fee</span>
                    <span><?= formatPrice((float)$order['delivery_fee']) ?></span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total Paid</span>
                    <span><?= formatPrice((float)$order['total_amount']) ?></span>
                </div>
            </div>
        </div>

        <!-- Notification Placeholder -->
        <div class="notification-note">
            <span class="notif-icon"><i class="fa-solid fa-mobile-screen"></i></span>
            <p>You'll receive an SMS/WhatsApp update when your order status changes. <strong>(Notification feature coming soon)</strong></p>
        </div>

        <!-- Actions -->
        <div class="confirm-actions">
            <a href="<?= BASE_URL ?>/customer/order_tracking.php?id=<?= $orderId ?>" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-location-dot"></i> Track My Order
            </a>
            <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-outline btn-lg">
                Order Again
            </a>
        </div>

    </div>
</section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
