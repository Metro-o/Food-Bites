<?php
/**
 * FoodBites — Auth: Register
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
guestOnly();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Validate
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? ''))
        $errors[] = 'Your session expired. Please try again.';
    if (strlen($name) < 2)                        $errors[] = 'Name must be at least 2 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (strlen($password) < 8)                    $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm)                   $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $db = getDB();

        // Check email uniqueness
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetchColumn()) {
            $errors[] = 'This email is already registered. Please login.';
        } else {
            // Create user
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $db->prepare(
                'INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, "customer")'
            );
            $stmt->execute([$name, $email, $phone, $hash]);

            // Auto login
            $_SESSION['user_id']   = (int) $db->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email']= $email;
            $_SESSION['user_role'] = 'customer';

            setFlash('success', "Welcome to FoodBites, {$name}! Start exploring our menu.");
            redirect(BASE_URL . '/customer/menu.php');
        }
    }
}

$pageTitle = 'Create Account';
$pageDesc  = 'Register for FoodBites and enjoy authentic Tanzanian cuisine delivered to your door.';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="auth-section">
    <div class="auth-card">
        <!-- Brand -->
        <div class="auth-brand">
            <span class="auth-logo"><i class="fa-solid fa-utensils"></i></span>
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join FoodBites — Taste of Tanzania</p>
        </div>

        <!-- Errors -->
        <?php if ($errors): ?>
            <div class="alert alert-error">
                <ul class="error-list">
                    <?php foreach ($errors as $e): ?>
                        <li><?= e($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="auth-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Amina Hassan"
                           value="<?= e($_POST['name'] ?? '') ?>" required autocomplete="name">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" placeholder="amina@email.com"
                           value="<?= e($_POST['email'] ?? '') ?>" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone Number <span class="form-optional">(optional)</span></label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-mobile-screen"></i></span>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="+255 712 345 678"
                           value="<?= e($_POST['phone'] ?? '') ?>" autocomplete="tel">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Min. 8 characters" required autocomplete="new-password">
                    <button type="button" class="toggle-password" onclick="togglePwd('password')" aria-label="Show password"><i class="fa-solid fa-eye"></i></button>
                </div>
                <div class="password-strength" id="strengthBar"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                           placeholder="Repeat password" required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg">
                Create Account →
            </button>
        </form>

        <p class="auth-switch">Already have an account? <a href="<?= BASE_URL ?>/auth/login.php">Login here</a></p>
    </div>

    <!-- Decorative side panel -->
    <div class="auth-visual" aria-hidden="true">
        <div class="auth-visual-content">
            <div class="auth-tagline"><i class="fa-solid fa-earth-africa"></i></div>
            <h2>Authentic Tanzanian Cuisine</h2>
            <p>From Ugali to Pilau, Nyama Choma to Zanzibar Pizza — order and enjoy the best of Tanzania.</p>
            <div class="auth-features">
                <div class="auth-feature"><i class="fa-solid fa-circle-check"></i> Fast delivery</div>
                <div class="auth-feature"><i class="fa-solid fa-circle-check"></i> Mobile money accepted</div>
                <div class="auth-feature"><i class="fa-solid fa-circle-check"></i> Schedule your order</div>
            </div>
        </div>
    </div>
</section>

<script>
function togglePwd(id) {
    const el   = document.getElementById(id);
    const icon = el.parentElement.querySelector('.toggle-password i');
    el.type = el.type === 'password' ? 'text' : 'password';
    if (icon) icon.className = el.type === 'password' ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
}
// Password strength indicator
document.getElementById('password')?.addEventListener('input', function() {
    const v = this.value, bar = document.getElementById('strengthBar');
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const levels = ['', 'strength-weak', 'strength-fair', 'strength-good', 'strength-strong'];
    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    bar.className = 'password-strength ' + (levels[score] || '');
    bar.setAttribute('data-label', labels[score] || '');
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
