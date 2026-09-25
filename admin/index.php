<?php
/**
 * FoodBites — Admin: Dashboard
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('admin', BASE_URL . '/admin/login.php');

$db = getDB();

// ── Analytics ────────────────────────────────────────────────
$totalOrders     = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingOrders   = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$totalRevenue    = $db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
$todayRevenue    = $db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'")->fetchColumn();
$totalCustomers  = $db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$totalProducts   = $db->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();

// Popular products
$popular = $db->query(
    "SELECT p.name, p.image, SUM(oi.quantity) AS total_sold, COUNT(DISTINCT oi.order_id) AS order_count
     FROM order_items oi JOIN products p ON oi.product_id = p.id
     GROUP BY oi.product_id ORDER BY total_sold DESC LIMIT 5"
)->fetchAll();

// Recent orders
$recentOrders = $db->query(
    "SELECT o.id, o.status, o.total_amount, o.delivery_location, o.created_at, u.name AS customer
     FROM orders o JOIN users u ON o.user_id = u.id
     ORDER BY o.created_at DESC LIMIT 8"
)->fetchAll();

// Orders by status (for donut chart)
$ordersByStatus = $db->query(
    "SELECT status, COUNT(*) AS cnt FROM orders GROUP BY status"
)->fetchAll(PDO::FETCH_KEY_PAIR);

// Revenue last 7 days
$revenue7 = $db->query(
    "SELECT DATE(created_at) AS day, SUM(total_amount) AS revenue
     FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND status != 'cancelled'
     GROUP BY day ORDER BY day ASC"
)->fetchAll();

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<!-- ── Stat Cards ───────────────────────────────────────────── -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#16A34A,#22C55E)"><i class="fa-solid fa-box" style="font-size:24px;color:#fff;"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value"><?= number_format($totalOrders) ?></div>
            <div class="stat-sub"><?= $pendingOrders ?> pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a)"><i class="fa-solid fa-money-bill-wave" style="font-size:24px;color:#fff;"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value"><?= formatPrice((float)$totalRevenue) ?></div>
            <div class="stat-sub">Today: <?= formatPrice((float)$todayRevenue) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)"><i class="fa-solid fa-users" style="font-size:24px;color:#fff;"></i></div>
        <div class="stat-body">
            <div class="stat-label">Customers</div>
            <div class="stat-value"><?= number_format($totalCustomers) ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#a855f7,#7c3aed)"><i class="fa-solid fa-utensils" style="font-size:24px;color:#fff;"></i></div>
        <div class="stat-body">
            <div class="stat-label">Active Menu Items</div>
            <div class="stat-value"><?= number_format($totalProducts) ?></div>
        </div>
    </div>
</div>

<!-- ── Charts Row ───────────────────────────────────────────── -->
<div class="dashboard-charts">
    <!-- Revenue Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3>Revenue — Last 7 Days</h3>
        </div>
        <canvas id="revenueChart" height="200"></canvas>
    </div>

    <!-- Orders by Status -->
    <div class="chart-card chart-card-sm">
        <div class="chart-header">
            <h3>Order Status</h3>
        </div>
        <canvas id="statusChart" height="200"></canvas>
    </div>
</div>

<!-- ── Dashboard Bottom Row ─────────────────────────────────── -->
<div class="dashboard-bottom">

    <!-- Recent Orders -->
    <div class="db-card">
        <div class="db-card-header">
            <h3>Recent Orders</h3>
            <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $o): ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?>/admin/orders.php?id=<?= $o['id'] ?>">#<?= $o['id'] ?></a></td>
                            <td><?= e($o['customer']) ?></td>
                            <td><?= e(mb_substr($o['delivery_location'], 0, 25)) ?>…</td>
                            <td><?= statusBadge($o['status']) ?></td>
                            <td><?= formatPrice((float)$o['total_amount']) ?></td>
                            <td><?= timeAgo($o['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popular Products -->
    <div class="db-card">
        <div class="db-card-header">
            <h3><i class="fa-solid fa-trophy"></i> Top Dishes</h3>
        </div>
        <div class="popular-list">
            <?php foreach ($popular as $i => $p): ?>
                <div class="popular-item">
                    <div class="popular-rank"><?= $i + 1 ?></div>
                    <img src="<?= productImage($p['image']) ?>" alt="<?= e($p['name']) ?>"
                         onerror="this.src='<?= BASE_URL ?>/assets/images/default_food.jpg'">
                    <div class="popular-info">
                        <div class="popular-name"><?= e($p['name']) ?></div>
                        <div class="popular-count"><?= $p['total_sold'] ?> sold · <?= $p['order_count'] ?> orders</div>
                    </div>
                    <div class="popular-bar">
                        <div class="popular-bar-fill" style="width:<?= min(100, $p['total_sold'] / max(1, $popular[0]['total_sold']) * 100) ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Line Chart
const revData = <?= json_encode($revenue7) ?>;
const labels  = revData.map(r => new Date(r.day).toLocaleDateString('en-TZ', { weekday:'short', month:'short', day:'numeric' }));
const values  = revData.map(r => parseFloat(r.revenue));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Revenue (TZS)',
            data: values,
            borderColor: '#16A34A',
            backgroundColor: 'rgba(22,163,74,0.1)',
            borderWidth: 3,
            pointBackgroundColor: '#16A34A',
            pointRadius: 5,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#e5e7eb' } } },
        scales: {
            x: { ticks: { color: '#9ca3af' }, grid: { color: '#1f2937' } },
            y: { ticks: { color: '#9ca3af', callback: v => 'TZS ' + v.toLocaleString() }, grid: { color: '#1f2937' } }
        }
    }
});

// Status Donut Chart
const statusData = <?= json_encode($ordersByStatus) ?>;
const statusColors = { pending:'#F59E0B', preparing:'#3B82F6', ready:'#22C55E', delivered:'#6B7280', cancelled:'#EF4444' };

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: Object.keys(statusData).map(s => statusColors[s] || '#888'),
            borderWidth: 0,
        }]
    },
    options: {
        cutout: '65%', responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { color: '#e5e7eb', padding: 12 } } }
    }
});
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
