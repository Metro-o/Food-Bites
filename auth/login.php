<?php
/**
 * FoodBites — Auth: Login
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
guestOnly();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $error = 'Your session expired. Please try again.';
    } elseif (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            session_regenerate_id(true);
            $_SESSION['user_id']   = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email']= $user['email'];
            $_SESSION['user_role'] = $user['role'];

            // Merge session cart → DB cart (if any guest cart items)
            // (handled by cart.php on next load)

            $redirect = $_SESSION['redirect_after_login'] ?? null;
            unset($_SESSION['redirect_after_login']);

            if ($user['role'] === 'admin') {
                redirect($redirect ?? BASE_URL . '/admin/index.php');
            } elseif ($user['role'] === 'kitchen') {
                redirect(BASE_URL . '/kds/index.php');
            } else {
                redirect($redirect ?? BASE_URL . '/customer/menu.php');
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$pageTitle = 'Login';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="auth-section">
    <div class="auth-card">
        <div class="auth-brand">
            <span class="auth-logo"><i class="fa-solid fa-utensils"></i></span>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Login to your FoodBites account</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="your@email.com" value="<?= e($_POST['email'] ?? '') ?>"
                           required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Your password" required autocomplete="current-password">
                    <button type="button" class="toggle-password" onclick="togglePwd('password')" aria-label="Show password"><i class="fa-solid fa-eye"></i></button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg">Login →</button>
        </form>

        <div class="auth-demo">
            <p class="demo-title"><i class="fa-solid fa-flask"></i> Demo Accounts</p>
            <button class="demo-btn" onclick="fillDemo('admin@foodbites.co.tz')">Admin Login</button>
            <button class="demo-btn" onclick="fillDemo('kitchen@foodbites.co.tz')">Kitchen Login</button>
            <button class="demo-btn" onclick="fillDemo('amina@example.com')">Customer Login</button>
            <p class="demo-note">Password for all: <code>Admin@1234</code></p>
        </div>

        <p class="auth-switch">Don't have an account? <a href="<?= BASE_URL ?>/auth/register.php">Sign up free</a></p>
    </div>

    <div class="auth-visual" aria-hidden="true">
        <div class="auth-visual-content">
            <div class="auth-tagline"><i class="fa-solid fa-earth-africa"></i></div>
            <h2>Order. Track. Enjoy.</h2>
            <p>Real-time order tracking, mobile money payments, and authentic Tanzanian food — all in one place.</p>
            <div class="auth-features">
                <div class="auth-feature"><i class="fa-solid fa-champagne-glasses"></i> Catering for events</div>
                <div class="auth-feature"><i class="fa-solid fa-calendar-day"></i> Schedule deliveries</div>
                <div class="auth-feature"><i class="fa-solid fa-location-dot"></i> Track your order live</div>
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
function fillDemo(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'Admin@1234';
}
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
