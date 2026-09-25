<?php
/**
 * FoodBites — Products API
 * Handles live search + filter for customer menu (AJAX)
 */
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json');

$db       = getDB();
$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$sort     = $_GET['sort'] ?? 'featured';

// Build query
$where  = ['p.status = "active"'];
$params = [];

if ($search !== '') {
    $where[]  = '(p.name LIKE ? OR p.description LIKE ?)';
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if ($category !== '' && $category !== 'all') {
    $where[]  = 'c.slug = ?';
    $params[] = $category;
}

$orderBy = match($sort) {
    'price_asc'  => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'newest'     => 'p.created_at DESC',
    default      => 'p.is_featured DESC, p.created_at DESC',
};

$sql = "SELECT p.id, p.name, p.description, p.price, p.image,
               p.is_combo, p.is_bulk, p.is_featured,
               c.name AS category, c.slug AS category_slug, c.icon AS category_icon
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY {$orderBy}
        LIMIT 60";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Format for output
foreach ($products as &$p) {
    $p['price_fmt']  = formatPrice((float) $p['price']);
    $p['image_url']  = productImage($p['image']);
    $p['badges']     = [];
    if ($p['is_featured']) $p['badges'][] = ['label' => 'Featured', 'class' => 'badge-featured', 'icon' => 'fa-star'];
    if ($p['is_combo'])    $p['badges'][] = ['label' => 'Combo',    'class' => 'badge-combo',    'icon' => 'fa-layer-group'];
    if ($p['is_bulk'])     $p['badges'][] = ['label' => 'Bulk',     'class' => 'badge-bulk',     'icon' => 'fa-boxes-stacked'];
}
unset($p);

echo json_encode(['success' => true, 'products' => $products, 'count' => count($products)]);
