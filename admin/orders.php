<?php
/**
 * FoodBites — Admin: Manage Orders
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('admin', BASE_URL . '/admin/login.php');

$db = getDB();

// ── Handle status update ──────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $oid    = (int) $_POST['order_id'];
    $status = $_POST['status'];
    $allowed = ['pending','preparing','ready','delivered','cancelled'];
    if (in_array($status, $allowed) && $oid > 0) {
        $stmt = $db->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $oid]);
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        setFlash('success', "Order #$oid status updated to " . ucfirst($status));
    }
    redirect(BASE_URL . '/admin/orders.php');
}

// ── Filters ───────────────────────────────────────────────────
$filterStatus = $_GET['status'] ?? 'all';
$filterDate   = $_GET['date'] ?? '';
$search       = trim($_GET['q'] ?? '');

$where  = ['1=1'];
$params = [];

if ($filterStatus !== 'all') {
    $where[]  = 'o.status = ?';
    $params[] = $filterStatus;
}
if ($filterDate) {
    $where[]  = 'DATE(o.created_at) = ?';
    $params[] = $filterDate;
}
if ($search) {
    $where[]  = '(u.name LIKE ? OR o.id = ?)';
    $params[] = "%{$search}%";
    $params[] = (int)$search;
}

$sql = "SELECT o.id, o.status, o.total_amount, o.delivery_location, o.delivery_time,
               o.payment_method, o.order_type, o.created_at,
               u.name AS customer, u.phone
        FROM orders o JOIN users u ON o.user_id = u.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY o.created_at DESC LIMIT 100";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

$pageTitle  = 'Manage Orders';
$activePage = 'orders';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<!-- Toolbar -->
<div class="admin-toolbar">
    <form method="GET" class="toolbar-filters">
        <input type="search" name="q" placeholder="Search customer / order ID…"
               class="form-control" value="<?= e($search) ?>" style="max-width:220px">
        <select name="status" class="select-control" onchange="this.form.submit()">
            <option value="all"       <?= $filterStatus === 'all'       ? 'selected' : '' ?>>All Statuses</option>
            <option value="pending"   <?= $filterStatus === 'pending'   ? 'selected' : '' ?>>Pending</option>
            <option value="preparing" <?= $filterStatus === 'preparing' ? 'selected' : '' ?>>Preparing</option>
            <option value="ready"     <?= $filterStatus === 'ready'     ? 'selected' : '' ?>>Ready</option>
            <option value="delivered" <?= $filterStatus === 'delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="cancelled" <?= $filterStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <input type="date" name="date" class="form-control" value="<?= e($filterDate) ?>"
               onchange="this.form.submit()" style="max-width:160px">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-ghost btn-sm">Clear</a>
    </form>
    <div class="toolbar-info"><?= count($orders) ?> order<?= count($orders) !== 1 ? 's' : '' ?></div>
</div>

<!-- Orders Table -->
<div class="table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Delivery</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Ordered</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o):
                // Fetch item count
                $icount = $db->prepare('SELECT COUNT(*) FROM order_items WHERE order_id = ?');
                $icount->execute([$o['id']]);
                $cnt = $icount->fetchColumn();
            ?>
            <tr id="order-row-<?= $o['id'] ?>">
                <td><strong>#<?= $o['id'] ?></strong></td>
                <td>
                    <div><?= e($o['customer']) ?></div>
                    <small><?= e($o['phone'] ?? '') ?></small>
                </td>
                <td><?= $cnt ?> item<?= $cnt !== 1 ? 's' : '' ?></td>
                <td>
                    <div><?= e(mb_substr($o['delivery_location'], 0, 30)) ?>…</div>
                    <small><?= $o['delivery_time'] ? date('d M H:i', strtotime($o['delivery_time'])) : 'ASAP' ?></small>
                </td>
                <td><?= formatPrice((float)$o['total_amount']) ?></td>
                <td><?= ucfirst($o['payment_method']) ?></td>
                <td><?= timeAgo($o['created_at']) ?></td>
                <td id="status-cell-<?= $o['id'] ?>"><?= statusBadge($o['status']) ?></td>
                <td>
                    <select class="select-control select-sm" onchange="updateStatus(<?= $o['id'] ?>, this.value, this)">
                        <?php foreach (['pending','preparing','ready','delivered','cancelled'] as $s): ?>
                            <option value="<?= $s ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (empty($orders)): ?>
    <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-box"></i></div>
        <h3>No orders match the filter</h3>
        <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-primary btn-sm">Show All</a>
    </div>
<?php endif; ?>

<script>
async function updateStatus(orderId, newStatus, sel) {
    const res = await fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({ update_status: 1, order_id: orderId, status: newStatus })
    });
    const data = await res.json();
    if (data.success) {
        const cell = document.getElementById(`status-cell-${orderId}`);
        const colors = { pending:'badge-pending', preparing:'badge-preparing', ready:'badge-ready', delivered:'badge-delivered', cancelled:'badge-cancelled' };
        cell.innerHTML = `<span class="badge ${colors[newStatus] || ''}">${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}</span>`;
    }
}
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
