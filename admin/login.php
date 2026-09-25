<?php
/**
 * FoodBites — Admin: Login
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';

// Redirect if already logged in as admin/kitchen
if (isLoggedIn() && isKitchen()) redirect(BASE_URL . '/admin/index.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role IN ('admin','kitchen') LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email']= $user['email'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'kitchen') {
                redirect(BASE_URL . '/kds/index.php');
            }
            redirect(BASE_URL . '/admin/index.php');
        } else {
            $error = 'Invalid credentials or unauthorized role.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — FoodBites</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body class="admin-body admin-login-body">

<div class="admin-login-wrap">
    <div class="admin-login-card">
        <div class="admin-login-logo">
            <span><i class="fa-solid fa-utensils"></i></span>
            <span>FoodBites</span>
        </div>
        <h1 class="admin-login-title">Admin Portal</h1>
        <p class="admin-login-sub">Sign in to manage your catering system</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="admin@foodbites.co.tz" required autocomplete="username">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="••••••••" required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-full btn-lg">Login to Dashboard →</button>
        </form>

        <div class="auth-demo" style="margin-top:1.5rem">
            <button class="demo-btn" onclick="fill('admin@foodbites.co.tz')">Admin</button>
            <button class="demo-btn" onclick="fill('kitchen@foodbites.co.tz')">Kitchen</button>
            <p class="demo-note">Password: <code>Admin@1234</code></p>
        </div>

        <p style="text-align:center;margin-top:1rem">
            <a href="<?= BASE_URL ?>/index.php" style="color:var(--primary)">← Back to Site</a>
        </p>
    </div>
</div>

<script>
function fill(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'Admin@1234';
}
</script>
</body>
</html>
