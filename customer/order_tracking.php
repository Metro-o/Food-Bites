<?php
/**
 * FoodBites — Order Tracking (real-time via AJAX polling)
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('customer');

$orderId = (int) ($_GET['id'] ?? 0);
if ($orderId <= 0) redirect(BASE_URL . '/customer/order_history.php');

$db   = getDB();
$stmt = $db->prepare(
    'SELECT o.*, u.name AS customer_name FROM orders o
     JOIN users u ON o.user_id = u.id
     WHERE o.id = ? AND o.user_id = ?'
);
$stmt->execute([$orderId, currentUserId()]);
$order = $stmt->fetch();

if (!$order) {
    setFlash('error', 'Order not found.');
    redirect(BASE_URL . '/customer/order_history.php');
}

// Fetch items
$items = $db->prepare(
    'SELECT oi.quantity, p.name AS product_name FROM order_items oi
     JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?'
);
$items->execute([$orderId]);
$orderItems = $items->fetchAll();

$statuses = ['pending', 'preparing', 'ready', 'delivered'];
$currentIdx = array_search($order['status'], $statuses);

$pageTitle = "Tracking Order #$orderId";
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title">Order Tracking</h1>
        <p class="page-sub">Order #<?= $orderId ?> — Live Status Updates</p>
    </div>
</section>

<section class="tracking-section">
    <div class="container container-narrow">

        <!-- Status Timeline -->
        <div class="tracking-card">
            <div class="tracking-header">
                <h3>Current Status</h3>
                <span class="badge <?= 'badge-' . $order['status'] ?>" id="statusBadge">
                    <?= ucfirst($order['status']) ?>
                </span>
            </div>

            <div class="tracking-timeline" id="trackingTimeline">
                <?php
                $steps = [
                    ['key' => 'pending',   'label' => 'Order Placed',    'icon' => 'fa-receipt',     'desc' => 'Your order has been received'],
                    ['key' => 'preparing', 'label' => 'Preparing',        'icon' => 'fa-kitchen-set', 'desc' => 'Kitchen is preparing your food'],
                    ['key' => 'ready',     'label' => 'Ready for Pickup', 'icon' => 'fa-circle-check','desc' => 'Order is ready — driver assigned'],
                    ['key' => 'delivered', 'label' => 'Delivered',        'icon' => 'fa-truck-fast',  'desc' => 'Enjoy your meal!'],
                ];
                foreach ($steps as $i => $step):
                    $done    = $i <= $currentIdx && $order['status'] !== 'cancelled';
                    $current = $i === $currentIdx;
                ?>
                    <div class="timeline-step <?= $done ? 'done' : '' ?> <?= $current ? 'current' : '' ?>"
                         id="step-<?= $step['key'] ?>">
                        <div class="timeline-icon"><i class="fa-solid <?= $step['icon'] ?>"></i></div>
                        <div class="timeline-connector"></div>
                        <div class="timeline-content">
                            <div class="timeline-label"><?= $step['label'] ?></div>
                            <div class="timeline-desc"><?= $step['desc'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($order['status'] === 'cancelled'): ?>
                <div class="alert alert-error" style="margin-top:1rem;">
                    <i class="fa-solid fa-circle-xmark"></i> This order has been cancelled. Please contact us for assistance.
                </div>
            <?php endif; ?>
        </div>

        <!-- Delivery Info -->
        <div class="tracking-card">
            <h3>Delivery Information</h3>
            <div class="tracking-info-grid">
                <div class="tinfo">
                    <span><i class="fa-solid fa-location-dot"></i> Location</span>
                    <strong><?= e($order['delivery_location']) ?></strong>
                </div>
                <div class="tinfo">
                    <span><i class="fa-solid fa-clock"></i> Expected</span>
                    <strong><?= $order['delivery_time'] ? date('d M, H:i', strtotime($order['delivery_time'])) : 'ASAP' ?></strong>
                </div>
                <div class="tinfo">
                    <span><i class="fa-solid fa-credit-card"></i> Payment</span>
                    <strong><?= ucfirst($order['payment_method']) ?></strong>
                </div>
                <div class="tinfo">
                    <span><i class="fa-solid fa-calendar-day"></i> Ordered</span>
                    <strong><?= date('d M H:i', strtotime($order['created_at'])) ?></strong>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="tracking-card">
            <h3>Your Items</h3>
            <ul class="tracking-items">
                <?php foreach ($orderItems as $item): ?>
                    <li>
                        <span class="ti-qty">× <?= $item['quantity'] ?></span>
                        <span class="ti-name"><?= e($item['product_name']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Auto-refresh indicator -->
        <div class="refresh-indicator" id="refreshIndicator">
            <div class="refresh-dot"></div>
            <span>Auto-updating every 5 seconds</span>
        </div>

        <div class="tracking-actions">
            <a href="<?= BASE_URL ?>/customer/order_history.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> My Orders</a>
            <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-primary">Order Again</a>
        </div>

    </div>
</section>

<script>
const BASE    = '<?= BASE_URL ?>';
const orderId = <?= $orderId ?>;
let lastStatus = '<?= $order['status'] ?>';

const statusMap = {
    pending:   0,
    preparing: 1,
    ready:     2,
    delivered: 3
};

async function pollStatus() {
    try {
        const res  = await fetch(`${BASE}/api/order.php?action=get_status&order_id=${orderId}`);
        const data = await res.json();
        if (!data.success) return;

        if (data.status !== lastStatus) {
            lastStatus = data.status;
            updateTimeline(data.status);
        }
    } catch (e) { /* silent */ }
}

function updateTimeline(status) {
    const idx = statusMap[status] ?? 0;
    document.querySelectorAll('.timeline-step').forEach((el, i) => {
        el.classList.toggle('done',    i <= idx);
        el.classList.toggle('current', i === idx);
    });

    const badge = document.getElementById('statusBadge');
    badge.className = `badge badge-${status}`;
    badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

    if (status === 'delivered') {
        clearInterval(pollInterval);
        document.getElementById('refreshIndicator').innerHTML =
            '<span style="color:var(--success)"><i class="fa-solid fa-circle-check"></i> Order delivered! Enjoy your meal!</span>';
    }
}

const pollInterval = setInterval(pollStatus, 5000);
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
