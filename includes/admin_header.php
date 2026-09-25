<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Dashboard') ?> — <?= APP_NAME ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <?= $extraHead ?? '' ?>
</head>
<body class="admin-body">

<div class="admin-layout">

    <!-- ── Sidebar ──────────────────────────────────────────── -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="<?= BASE_URL ?>/admin/index.php" class="sidebar-logo">
                <span class="logo-icon"><i class="fa-solid fa-utensils"></i></span>
                <span class="logo-text"><?= APP_NAME ?></span>
            </a>
            <button class="sidebar-close" id="sidebarClose"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?></div>
            <div>
                <div class="sidebar-username"><?= e($_SESSION['user_name'] ?? '') ?></div>
                <div class="sidebar-role"><?= ucfirst($_SESSION['user_role'] ?? '') ?></div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <?php $ap = $activePage ?? ''; ?>
            <a href="<?= BASE_URL ?>/admin/index.php"       class="sidebar-link <?= $ap === 'dashboard' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-gauge"></i></span> Dashboard
            </a>
            <a href="<?= BASE_URL ?>/admin/orders.php"      class="sidebar-link <?= $ap === 'orders' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-box"></i></span> Orders
                <?php
                    $pend = getDB()->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
                    if ($pend > 0) echo '<span class="sidebar-badge">' . $pend . '</span>';
                ?>
            </a>
            <a href="<?= BASE_URL ?>/admin/products.php"    class="sidebar-link <?= $ap === 'products' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-utensils"></i></span> Menu Items
            </a>
            <a href="<?= BASE_URL ?>/admin/users.php"       class="sidebar-link <?= $ap === 'users' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Customers
            </a>
            <div class="sidebar-divider"></div>
            <a href="<?= BASE_URL ?>/kds/index.php"         class="sidebar-link <?= $ap === 'kds' ? 'active' : '' ?>" target="_blank">
                <span class="nav-icon"><i class="fa-solid fa-desktop"></i></span> Kitchen Display
            </a>
            <a href="<?= BASE_URL ?>/index.php"             class="sidebar-link" target="_blank">
                <span class="nav-icon"><i class="fa-solid fa-globe"></i></span> View Site
            </a>
            <div class="sidebar-divider"></div>
            <a href="<?= BASE_URL ?>/admin/logout.php"      class="sidebar-link sidebar-logout">
                <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span> Logout
            </a>
        </nav>
    </aside>

    <!-- ── Main Content ─────────────────────────────────────── -->
    <div class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <button class="topbar-menu-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                <span></span><span></span><span></span>
            </button>
            <h1 class="topbar-title"><?= e($pageTitle ?? 'Dashboard') ?></h1>
            <div class="topbar-right">
                <span class="topbar-time" id="topbarTime"></span>
                <a href="<?= BASE_URL ?>/kds/index.php" class="btn btn-sm btn-outline" target="_blank"><i class="fa-solid fa-desktop"></i> KDS</a>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php renderFlash(); ?>

        <div class="admin-content">
