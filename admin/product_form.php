<?php
/**
 * FoodBites — Admin: Add / Edit Product
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('admin', BASE_URL . '/admin/login.php');

$db = getDB();
$id = (int) ($_GET['id'] ?? 0);

// Load existing product for edit
$product = null;
if ($id > 0) {
    $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}

$categories = $db->query('SELECT * FROM categories ORDER BY sort_order')->fetchAll();
$errors     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (float) ($_POST['price'] ?? 0);
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_combo    = isset($_POST['is_combo'])    ? 1 : 0;
    $is_bulk     = isset($_POST['is_bulk'])     ? 1 : 0;
    $status      = $_POST['status'] === 'inactive' ? 'inactive' : 'active';

    if (strlen($name) < 2) $errors[] = 'Name is required.';
    if ($price <= 0)        $errors[] = 'Price must be greater than 0.';

    // Handle image upload
    $imageName = $product['image'] ?? null;

    if (!empty($_FILES['image']['name'])) {
        $file     = $_FILES['image'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','webp','gif'];

        if (!in_array($ext, $allowed)) {
            $errors[] = 'Image must be JPG, PNG, WebP or GIF.';
        } elseif ($file['size'] > MAX_FILE_SIZE) {
            $errors[] = 'Image must be under 5 MB.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload error.';
        } else {
            // Delete old image
            if ($imageName && file_exists(UPLOAD_DIR . $imageName)) {
                @unlink(UPLOAD_DIR . $imageName);
            }
            $imageName = uniqid('food_') . '.' . $ext;
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $imageName);
        }
    }

    if (empty($errors)) {
        if ($id > 0) {
            // UPDATE
            $stmt = $db->prepare(
                'UPDATE products SET name=?, description=?, price=?, image=?, category_id=?,
                 is_featured=?, is_combo=?, is_bulk=?, status=? WHERE id=?'
            );
            $stmt->execute([$name, $description, $price, $imageName, $category_id,
                            $is_featured, $is_combo, $is_bulk, $status, $id]);
            setFlash('success', "Product «{$name}» updated.");
        } else {
            // INSERT
            $stmt = $db->prepare(
                'INSERT INTO products (name, description, price, image, category_id, is_featured, is_combo, is_bulk, status)
                 VALUES (?,?,?,?,?,?,?,?,?)'
            );
            $stmt->execute([$name, $description, $price, $imageName, $category_id,
                            $is_featured, $is_combo, $is_bulk, $status]);
            setFlash('success', "Product «{$name}» created.");
        }
        redirect(BASE_URL . '/admin/products.php');
    }
}

$pageTitle  = $id ? 'Edit Product' : 'Add Product';
$activePage = 'products';
require_once dirname(__DIR__) . '/includes/admin_header.php';
?>

<div class="form-page-layout">
    <div class="form-card">

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <ul class="error-list">
                    <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-grid-2">
                <div class="form-group form-group-span2">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control" required
                           value="<?= e($product['name'] ?? ($_POST['name'] ?? '')) ?>"
                           placeholder="e.g. Ugali na Mchuzi wa Nyama">
                </div>

                <div class="form-group">
                    <label class="form-label">Price (TZS) *</label>
                    <input type="number" name="price" class="form-control" required min="100"
                           value="<?= e($product['price'] ?? ($_POST['price'] ?? '')) ?>"
                           placeholder="8500">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control select-control">
                        <option value="">— None —</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= ($product['category_id'] ?? ($_POST['category_id'] ?? '')) == $cat['id'] ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group form-group-span2">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Describe the dish…"><?= e($product['description'] ?? ($_POST['description'] ?? '')) ?></textarea>
                </div>

                <!-- Image Upload -->
                <div class="form-group form-group-span2">
                    <label class="form-label">Product Image</label>
                    <div class="file-upload-area" id="uploadArea" onclick="document.getElementById('imageInput').click()">
                        <img id="imgPreview" src="<?= isset($product['image']) ? productImage($product['image']) : BASE_URL . '/assets/images/default_food.jpg' ?>"
                             alt="Preview" class="img-preview">
                        <div class="upload-overlay">
                            <p><i class="fa-solid fa-camera"></i> Click to upload image</p>
                            <p class="upload-hint">JPG, PNG, WebP — Max 5MB</p>
                        </div>
                    </div>
                    <input type="file" id="imageInput" name="image" accept="image/*" style="display:none"
                           onchange="previewImage(this)">
                </div>

                <!-- Toggles -->
                <div class="form-group">
                    <label class="form-label">Flags</label>
                    <div class="toggle-group">
                        <label class="toggle-label">
                            <input type="checkbox" name="is_featured" <?= ($product['is_featured'] ?? 0) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span> <i class="fa-solid fa-star"></i> Featured
                        </label>
                        <label class="toggle-label">
                            <input type="checkbox" name="is_combo" <?= ($product['is_combo'] ?? 0) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span> <i class="fa-solid fa-layer-group"></i> Combo Meal
                        </label>
                        <label class="toggle-label">
                            <input type="checkbox" name="is_bulk" <?= ($product['is_bulk'] ?? 0) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span> <i class="fa-solid fa-boxes-stacked"></i> Bulk Order
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control select-control">
                        <option value="active"   <?= ($product['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>● Active (visible on menu)</option>
                        <option value="inactive" <?= ($product['status'] ?? 'active') === 'inactive' ? 'selected' : '' ?>>○ Inactive (hidden)</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <?= $id ? '<i class="fa-solid fa-floppy-disk"></i> Save Changes' : '<i class="fa-solid fa-plus"></i> Add Product' ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-ghost btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('imgPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once dirname(__DIR__) . '/includes/admin_footer.php'; ?>
