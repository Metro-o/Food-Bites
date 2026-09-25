<?php
/**
 * FoodBites — Customer: Menu / Browse
 */
require_once dirname(__DIR__) . '/includes/functions.php';

$db = getDB();

// Get all categories for filter tabs
$categories = $db->query('SELECT * FROM categories ORDER BY sort_order')->fetchAll();

// Initial load (server-side render for SEO / fast first paint)
$selectedCat = $_GET['category'] ?? 'all';
$search      = trim($_GET['search'] ?? '');

$where  = ['p.status = "active"'];
$params = [];

if ($search !== '') {
    $where[]  = '(p.name LIKE ? OR p.description LIKE ?)';
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if ($selectedCat !== 'all' && $selectedCat !== '') {
    $where[]  = 'c.slug = ?';
    $params[] = $selectedCat;
}

$sql  = "SELECT p.*, c.name AS category, c.slug AS category_slug, c.icon AS cat_icon
         FROM products p
         LEFT JOIN categories c ON p.category_id = c.id
         WHERE " . implode(' AND ', $where) . "
         ORDER BY p.is_featured DESC, p.created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$pageTitle = 'Menu';
$pageDesc  = 'Browse FoodBites full menu – authentic Tanzanian dishes for delivery.';
$activePage = 'menu';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title">Our Menu</h1>
        <p class="page-sub">Fresh, authentic Tanzanian cuisine made with love</p>
    </div>
</section>

<section class="menu-section">
    <div class="container">

        <!-- Toolbar: Search + Sort -->
        <div class="menu-toolbar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="search" id="menuSearch" class="search-input"
                       placeholder="Search dishes…" value="<?= e($search) ?>"
                       autocomplete="off">
                <button class="search-clear" id="searchClear" aria-label="Clear search" style="display:none"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="sort-wrapper">
                <select id="menuSort" class="select-control">
                    <option value="featured">Featured First</option>
                    <option value="price_asc">Price: Low &rarr; High</option>
                    <option value="price_desc">Price: High &rarr; Low</option>
                    <option value="newest">Newest</option>
                </select>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="cat-tabs" id="catTabs" role="tablist">
            <button class="cat-tab <?= $selectedCat === 'all' ? 'active' : '' ?>"
                    data-cat="all" role="tab" aria-selected="<?= $selectedCat === 'all' ? 'true' : 'false' ?>">
                <i class="fa-solid fa-utensils"></i> All
            </button>
            <?php foreach ($categories as $cat): ?>
                <button class="cat-tab <?= $selectedCat === $cat['slug'] ? 'active' : '' ?>"
                        data-cat="<?= e($cat['slug']) ?>" role="tab"
                        aria-selected="<?= $selectedCat === $cat['slug'] ? 'true' : 'false' ?>">
                    <i class="fa-solid <?= e($cat['icon']) ?>"></i> <?= e($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Results count -->
        <div class="results-info">
            <span id="resultsCount"><?= count($products) ?> dish<?= count($products) !== 1 ? 'es' : '' ?> available</span>
        </div>

        <!-- Food Grid -->
        <div class="food-grid" id="foodGrid">
            <?php if (empty($products)): ?>
                <div class="empty-state" id="emptyState">
                    <div class="empty-icon"><i class="fa-solid fa-bowl-food"></i></div>
                    <h3>No dishes found</h3>
                    <p>Try a different search or category</p>
                    <button class="btn btn-primary btn-sm" onclick="resetFilters()">Show All Dishes</button>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="food-card" data-id="<?= $product['id'] ?>">
                        <div class="food-card-img">
                            <img src="<?= productImage($product['image']) ?>"
                                 alt="<?= e($product['name']) ?>" loading="lazy"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/default_food.jpg'">
                            <?php if ($product['is_featured']): ?><span class="food-badge badge-featured"><i class="fa-solid fa-star"></i> Featured</span><?php endif; ?>
                            <?php if ($product['is_combo']): ?><span class="food-badge badge-combo"><i class="fa-solid fa-layer-group"></i> Combo</span><?php endif; ?>
                            <?php if ($product['is_bulk']): ?><span class="food-badge badge-bulk"><i class="fa-solid fa-boxes-stacked"></i> Bulk</span><?php endif; ?>
                        </div>
                        <div class="food-card-body">
                            <div class="food-meta">
                                <span class="food-category"><?php if (!empty($product['cat_icon'])): ?><i class="fa-solid <?= e($product['cat_icon']) ?>"></i> <?php endif; ?><?= e($product['category'] ?? '') ?></span>
                            </div>
                            <h3 class="food-name"><?= e($product['name']) ?></h3>
                            <p class="food-desc"><?= e(mb_substr($product['description'] ?? '', 0, 90)) ?><?= strlen($product['description'] ?? '') > 90 ? '…' : '' ?></p>
                            <div class="food-footer">
                                <span class="food-price"><?= formatPrice((float)$product['price']) ?></span>
                                <div class="food-actions">
                                    <div class="qty-stepper" id="stepper-<?= $product['id'] ?>" style="display:none">
                                        <button class="qty-btn" onclick="adjustQty(<?= $product['id'] ?>, -1)">−</button>
                                        <span class="qty-val" id="qty-<?= $product['id'] ?>">1</span>
                                        <button class="qty-btn" onclick="adjustQty(<?= $product['id'] ?>, 1)">+</button>
                                    </div>
                                    <button class="btn-add-cart"
                                            id="addbtn-<?= $product['id'] ?>"
                                            onclick="initAddToCart(<?= $product['id'] ?>, this)"
                                            aria-label="Add <?= e($product['name']) ?> to cart">
                                        <span>+ Add</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<script src="<?= BASE_URL ?>/assets/js/cart.js"></script>
<script>
// ── Menu AJAX filter/search ─────────────────────────────────────────────────
const BASE = '<?= BASE_URL ?>';
let searchTimer = null;
let currentCat  = '<?= e($selectedCat) ?>';
let currentSort = 'featured';

const grid        = document.getElementById('foodGrid');
const countEl     = document.getElementById('resultsCount');
const searchInput = document.getElementById('menuSearch');
const clearBtn    = document.getElementById('searchClear');

searchInput.addEventListener('input', () => {
    clearBtn.style.display = searchInput.value ? 'block' : 'none';
    clearTimeout(searchTimer);
    searchTimer = setTimeout(loadProducts, 350);
});

clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    clearBtn.style.display = 'none';
    loadProducts();
});

document.getElementById('menuSort').addEventListener('change', function() {
    currentSort = this.value;
    loadProducts();
});

document.querySelectorAll('.cat-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentCat = btn.dataset.cat;
        loadProducts();
    });
});

function loadProducts() {
    const q = new URLSearchParams({
        search:   searchInput.value,
        category: currentCat,
        sort:     currentSort,
    });

    grid.classList.add('loading');
    fetch(`${BASE}/api/products.php?${q}`)
        .then(r => r.json())
        .then(data => {
            grid.classList.remove('loading');
            countEl.textContent = `${data.count} dish${data.count !== 1 ? 'es' : ''} available`;
            renderProducts(data.products);
        });
}

function renderProducts(products) {
    if (!products.length) {
        grid.innerHTML = `<div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-bowl-food"></i></div>
            <h3>No dishes found</h3>
            <p>Try a different search or category</p>
            <button class="btn btn-primary btn-sm" onclick="resetFilters()">Show All Dishes</button>
        </div>`;
        return;
    }

    grid.innerHTML = products.map(p => `
        <div class="food-card" data-id="${p.id}">
            <div class="food-card-img">
                <img src="${p.image_url}" alt="${escHtml(p.name)}" loading="lazy"
                     onerror="this.src='${BASE}/assets/images/default_food.jpg'">
                ${p.badges.map(b => `<span class="food-badge ${b.class}"><i class="fa-solid ${b.icon}"></i> ${b.label}</span>`).join('')}
            </div>
            <div class="food-card-body">
                <div class="food-meta">
                    <span class="food-category">${p.category_icon ? `<i class="fa-solid ${p.category_icon}"></i> ` : ''}${escHtml(p.category || '')}</span>
                </div>
                <h3 class="food-name">${escHtml(p.name)}</h3>
                <p class="food-desc">${escHtml((p.description || '').substring(0, 90))}${(p.description||'').length > 90 ? '…' : ''}</p>
                <div class="food-footer">
                    <span class="food-price">${p.price_fmt}</span>
                    <div class="food-actions">
                        <div class="qty-stepper" id="stepper-${p.id}" style="display:none">
                            <button class="qty-btn" onclick="adjustQty(${p.id}, -1)">−</button>
                            <span class="qty-val" id="qty-${p.id}">1</span>
                            <button class="qty-btn" onclick="adjustQty(${p.id}, 1)">+</button>
                        </div>
                        <button class="btn-add-cart" id="addbtn-${p.id}"
                                onclick="initAddToCart(${p.id}, this)">
                            <span>+ Add</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function resetFilters() {
    searchInput.value = '';
    currentCat = 'all';
    document.querySelectorAll('.cat-tab').forEach(b => b.classList.toggle('active', b.dataset.cat === 'all'));
    loadProducts();
}

function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

// Qty stepper logic
const qtys = {};
function initAddToCart(id, btn) {
    qtys[id] = qtys[id] || 1;
    document.getElementById(`stepper-${id}`).style.display = 'flex';
    btn.style.display = 'none';
    addToCart(id, btn);
}
function adjustQty(id, delta) {
    qtys[id] = Math.max(1, (qtys[id] || 1) + delta);
    document.getElementById(`qty-${id}`).textContent = qtys[id];
}
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
