<?php
/**
 * FoodBites — Customer: Profile
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('customer');

$db  = getDB();
$uid = currentUserId();

$stmt = $db->prepare('SELECT id, name, email, phone, created_at FROM users WHERE id = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch();

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Your session expired. Please try again.';
    } elseif ($formAction === 'update_info') {
        $name  = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (strlen($name) < 2) $errors[] = 'Name must be at least 2 characters.';

        if (empty($errors)) {
            $stmt = $db->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
            $stmt->execute([$name, $phone, $uid]);
            $_SESSION['user_name'] = $name;
            $user['name']  = $name;
            $user['phone'] = $phone;
            $success = 'Profile updated successfully.';
        }
    } elseif ($formAction === 'change_password') {
        $current  = $_POST['current_password'] ?? '';
        $new      = $_POST['new_password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        $stmt = $db->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$uid]);
        $hash = $stmt->fetchColumn();

        if (!password_verify($current, $hash)) {
            $errors[] = 'Current password is incorrect.';
        } elseif (strlen($new) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        } elseif ($new !== $confirm) {
            $errors[] = 'New passwords do not match.';
        } else {
            $newHash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
            $db->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$newHash, $uid]);
            $success = 'Password changed successfully.';
        }
    }
}

$pageTitle  = 'My Profile';
$activePage = 'profile';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title">My Profile</h1>
        <p class="page-sub">Manage your account details</p>
    </div>
</section>

<section class="checkout-section">
    <div class="container" style="max-width:640px;">

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <ul class="error-list">
                    <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <div class="checkout-card">
            <h2 class="checkout-card-title"><i class="fa-solid fa-user"></i> Account Details</h2>
            <form method="POST" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="form_action" value="update_info">

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="<?= e($user['name']) ?>" required autocomplete="name">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
                    <small class="form-hint">Email cannot be changed.</small>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control"
                           value="<?= e($user['phone'] ?? '') ?>" placeholder="+255 712 345 678" autocomplete="tel">
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
            </form>
        </div>

        <div class="checkout-card" style="margin-top:1.5rem;">
            <h2 class="checkout-card-title"><i class="fa-solid fa-lock"></i> Change Password</h2>
            <form method="POST" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="form_action" value="change_password">

                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control"
                           required autocomplete="current-password">
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control"
                           required autocomplete="new-password" minlength="8">
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                           required autocomplete="new-password" minlength="8">
                </div>

                <button type="submit" class="btn btn-outline btn-lg">Update Password</button>
            </form>
        </div>

    </div>
</section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
