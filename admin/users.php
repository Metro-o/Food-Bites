<?php
/**
 * FoodBites — Admin: View Customers
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('admin', BASE_URL . '/admin/login.php');

$db = getDB();

$stmt = $db->query(
    "SELECT u.id, u.name, u.email, u.phone, u.created_at,
            COUNT(o.id)              AS order_count,
            COALESCE(SUM(o.total_amount),0) AS total_spent
     FROM users u
     LEFT JOIN orders o ON u.id = o.user_id
     WHERE u.role = 'customer'
     GROUP BY u.id
     ORDER BY u.created_at DESC"
);
$customers = $stmt->fetchAll();

$pageTitle  = 'Customers';
$activePage = 'users';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-toolbar">
    <span style="font-size:.9rem;color:var(--text-muted)"><?= count($customers) ?> registered customers</span>
</div>

<div class="table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td>
                        <div class="user-cell">
                            <span class="user-avatar-sm"><?= strtoupper(substr($c['name'], 0, 1)) ?></span>
                            <?= e($c['name']) ?>
                        </div>
                    </td>
                    <td><?= e($c['email']) ?></td>
                    <td><?= e($c['phone'] ?? '—') ?></td>
                    <td>
                        <span class="badge <?= $c['order_count'] > 0 ? 'badge-preparing' : '' ?>">
                            <?= $c['order_count'] ?>
                        </span>
                    </td>
                    <td><?= formatPrice((float)$c['total_spent']) ?></td>
                    <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (empty($customers)): ?>
    <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-users"></i></div>
        <h3>No customers yet</h3>
    </div>
<?php endif; ?>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
