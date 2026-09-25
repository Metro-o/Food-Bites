<?php
/**
 * FoodBites — Admin: Manage Menu Items
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('admin', BASE_URL . '/admin/login.php');

$db = getDB();

// Delete product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $pid  = (int) $_POST['delete_id'];
    $prod = $db->prepare('SELECT image FROM products WHERE id = ?');
    $prod->execute([$pid]);
    $img  = $prod->fetchColumn();
    if ($img && file_exists(UPLOAD_DIR . $img)) {
        @unlink(UPLOAD_DIR . $img);
    }
    $db->prepare('DELETE FROM products WHERE id = ?')->execute([$pid]);
    setFlash('success', 'Product deleted.');
    redirect(BASE_URL . '/admin/products.php');
}

// Filter
$filterCat = $_GET['cat'] ?? 'all';
$search    = trim($_GET['q'] ?? '');
$where  = ['1=1'];
$params = [];

if ($filterCat !== 'all') { $where[] = 'p.category_id = ?'; $params[] = $filterCat; }
if ($search)              { $where[] = 'p.name LIKE ?';     $params[] = "%{$search}%"; }

$stmt = $db->prepare(
    "SELECT p.*, c.name AS category FROM products p
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE " . implode(' AND ', $where) . " ORDER BY p.created_at DESC"
);
$stmt->execute($params);
$products   = $stmt->fetchAll();
$categories = $db->query('SELECT * FROM categories ORDER BY sort_order')->fetchAll();

$pageTitle  = 'Menu Items';
$activePage = 'products';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="admin-toolbar">
    <form method="GET" class="toolbar-filters">
        <input type="search" name="q" placeholder="Search products…" class="form-control"
               value="<?= e($search) ?>" style="max-width:220px">
        <select name="cat" class="select-control" onchange="this.form.submit()">
            <option value="all">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $filterCat == $cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
    <a href="<?= BASE_URL ?>/admin/product_form.php" class="btn btn-primary btn-sm">+ Add Item</a>
</div>

<div class="product-admin-grid">
    <?php foreach ($products as $p): ?>
        <div class="product-admin-card">
            <div class="pac-img">
                <img src="<?= productImage($p['image']) ?>" alt="<?= e($p['name']) ?>"
                     onerror="this.src='<?= BASE_URL ?>/assets/images/default_food.jpg'">
                <span class="pac-status-badge <?= $p['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                    <?= $p['status'] === 'active' ? '● Live' : '○ Hidden' ?>
                </span>
            </div>
            <div class="pac-body">
                <div class="pac-category"><?= e($p['category'] ?? 'Uncategorized') ?></div>
                <h4 class="pac-name"><?= e($p['name']) ?></h4>
                <div class="pac-price"><?= formatPrice((float)$p['price']) ?></div>
                <div class="pac-badges">
                    <?php if ($p['is_featured']) echo '<span class="badge badge-featured"><i class="fa-solid fa-star"></i> Featured</span>'; ?>
                    <?php if ($p['is_combo'])    echo '<span class="badge badge-combo"><i class="fa-solid fa-layer-group"></i> Combo</span>'; ?>
                    <?php if ($p['is_bulk'])     echo '<span class="badge badge-bulk"><i class="fa-solid fa-boxes-stacked"></i> Bulk</span>'; ?>
                </div>
            </div>
            <div class="pac-actions">
                <a href="<?= BASE_URL ?>/admin/product_form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i> Edit</a>
                <form method="POST" onsubmit="return confirm('Delete «<?= e(addslashes($p['name'])) ?>»?')">
                    <input type="hidden" name="delete_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($products)): ?>
        <div class="empty-state" style="grid-column:1/-1">
            <div class="empty-icon"><i class="fa-solid fa-utensils"></i></div>
            <h3>No products found</h3>
            <a href="<?= BASE_URL ?>/admin/product_form.php" class="btn btn-primary btn-sm">Add First Item</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
