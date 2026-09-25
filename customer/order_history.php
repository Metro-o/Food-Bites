<?php
/**
 * FoodBites — Customer: Order History
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('customer');

$db  = getDB();
$uid = currentUserId();

$stmt = $db->prepare(
    'SELECT o.*, COUNT(oi.id) AS item_count,
            GROUP_CONCAT(p.name ORDER BY oi.id SEPARATOR ", ") AS item_names
     FROM orders o
     LEFT JOIN order_items oi ON o.id = oi.order_id
     LEFT JOIN products p ON oi.product_id = p.id
     WHERE o.user_id = ?
     GROUP BY o.id
     ORDER BY o.created_at DESC'
);
$stmt->execute([$uid]);
$orders = $stmt->fetchAll();

$pageTitle  = 'My Orders';
$activePage = 'orders';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title">My Orders</h1>
        <p class="page-sub"><?= count($orders) ?> order<?= count($orders) !== 1 ? 's' : '' ?> placed</p>
    </div>
</section>

<section class="history-section">
    <div class="container">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-box"></i></div>
                <h2>No orders yet</h2>
                <p>You haven't placed any orders. Let's fix that!</p>
                <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-primary btn-lg">Browse Menu →</a>
            </div>
        <?php else: ?>
            <div class="history-list">
                <?php foreach ($orders as $order): ?>
                    <div class="history-card">
                        <div class="history-card-top">
                            <div class="history-left">
                                <div class="history-id">#<?= $order['id'] ?></div>
                                <div class="history-date"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></div>
                            </div>
                            <div class="history-right">
                                <?= statusBadge($order['status']) ?>
                            </div>
                        </div>
                        <div class="history-items">
                            <span class="history-item-list"><?= e($order['item_names'] ?? '') ?></span>
                            <span class="history-item-count"><?= $order['item_count'] ?> item<?= $order['item_count'] !== 1 ? 's' : '' ?></span>
                        </div>
                        <div class="history-card-footer">
                            <div class="history-meta">
                                <span><i class="fa-solid fa-location-dot"></i> <?= e(mb_substr($order['delivery_location'], 0, 40)) ?></span>
                                <span><i class="fa-solid fa-credit-card"></i> <?= ucfirst($order['payment_method']) ?></span>
                            </div>
                            <div class="history-total"><?= formatPrice((float)$order['total_amount']) ?></div>
                        </div>
                        <div class="history-actions">
                            <?php if (in_array($order['status'], ['pending','preparing','ready'])): ?>
                                <a href="<?= BASE_URL ?>/customer/order_tracking.php?id=<?= $order['id'] ?>"
                                   class="btn btn-primary btn-sm"><i class="fa-solid fa-location-dot"></i> Track Order</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/customer/order_tracking.php?id=<?= $order['id'] ?>"
                                   class="btn btn-outline btn-sm">View Details</a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-ghost btn-sm">Reorder</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
