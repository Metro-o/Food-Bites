<?php
/**
 * FoodBites — Global Header
 * Theme  : Food-App (Uber Eats / Zomato style)
 * i18n   : English · Kiswahili · Arabic · French
 */
if (session_status() === PHP_SESSION_NONE) session_start();

// ── Language system ───────────────────────────────────────────────────────────
$supportedLangs = ['en', 'sw', 'ar', 'fr'];
$langMeta = [
    'en' => ['label' => 'EN', 'flag' => '🇬🇧', 'name' => 'English'],
    'sw' => ['label' => 'SW', 'flag' => '🇹🇿', 'name' => 'Kiswahili'],
    'ar' => ['label' => 'AR', 'flag' => '🇸🇦', 'name' => 'العربية'],
    'fr' => ['label' => 'FR', 'flag' => '🇫🇷', 'name' => 'Français'],
];

if (isset($_GET['lang']) && in_array($_GET['lang'], $supportedLangs)) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang  = $_SESSION['lang'] ?? 'en';
$isRtl = ($lang === 'ar');
$dir   = $isRtl ? 'rtl' : 'ltr';

// ── Nav translations ──────────────────────────────────────────────────────────
$navT = [
    'en' => ['home' => 'Home', 'menu' => 'Menu', 'orders' => 'My Orders', 'login' => 'Login', 'join' => 'Join', 'logout' => 'Logout', 'search_placeholder' => 'Search dishes...'],
    'sw' => ['home' => 'Nyumbani', 'menu' => 'Menyu', 'orders' => 'Maagizo Yangu', 'login' => 'Ingia', 'join' => 'Jiunge', 'logout' => 'Toka', 'search_placeholder' => 'Tafuta vyakula...'],
    'ar' => ['home' => 'الرئيسية', 'menu' => 'القائمة', 'orders' => 'طلباتي', 'login' => 'تسجيل الدخول', 'join' => 'انضم', 'logout' => 'خروج', 'search_placeholder' => 'ابحث عن الأطباق...'],
    'fr' => ['home' => 'Accueil', 'menu' => 'Menu', 'orders' => 'Mes Commandes', 'login' => 'Connexion', 'join' => 'Rejoindre', 'logout' => 'Déconnexion', 'search_placeholder' => 'Rechercher des plats...'],
];
$N = $navT[$lang];

// ── Lang URL helper ───────────────────────────────────────────────────────────
function headerLangUrl(string $code): string {
    $p = $_GET; $p['lang'] = $code;
    return '?' . http_build_query($p);
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?> — <?= APP_NAME ?></title>
    <meta name="description" content="<?= e($pageDesc ?? 'FoodBites — Taste of Tanzania, Delivered to You.') ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">

    <!-- App styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/customer.css">

    <style>
    /* ═══════════════════════════════════════════════════
       FOOD-APP NAVBAR THEME
    ═══════════════════════════════════════════════════ */
    :root {
        --primary:       #16A34A;
        --primary-dark:  #15803D;
        --primary-light: #22C55E;
        --accent:        #F59E0B;
        --bg:            #FFFAF7;
        --surface:       #FFFFFF;
        --surface-2:     #FFF4EF;
        --text:          #1A1A1A;
        --text-muted:    #6B6B6B;
        --border:        #F0E6E0;
        /* Overrides for main.css/customer.css shared component vars (those
           files default to a dark theme; customer pages are light) */
        --bg-card:       #FFFFFF;
        --bg-elevated:   #FFF4EF;
        --bg-input:      #FFFFFF;
        --text-subtle:   #9CA3AF;
        --shadow-sm:     0 2px 8px rgba(22,163,74,.10);
        --shadow-md:     0 6px 24px rgba(22,163,74,.14);
        --radius-sm:     8px;
        --radius-md:     16px;
        --font:          'Poppins', sans-serif;
        --font-ar:       'Noto Sans Arabic', sans-serif;
        --nav-h:         68px;
        --transition:    .22s cubic-bezier(.4,0,.2,1);
    }
    [dir="rtl"] { font-family: var(--font-ar), var(--font); }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: var(--font); background: var(--bg); color: var(--text); }

    /* ── Top announcement / lang bar ──────────────────── */
    .top-bar {
        background: var(--primary-dark);
        color: rgba(255,255,255,.9);
        font-size: .78rem; font-weight: 500;
        padding: .35rem 0;
    }
    .top-bar-inner {
        max-width: 1200px; margin: 0 auto; padding: 0 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
        gap: .75rem; flex-wrap: wrap;
    }
    .top-bar-left { display: flex; align-items: center; gap: .5rem; opacity: .85; }
    .top-bar-left i { color: var(--accent); }

    /* Lang switcher */
    .lang-switcher { display: flex; align-items: center; gap: .35rem; }
    .lang-switcher i { color: rgba(255,255,255,.6); font-size: .8rem; }
    .lang-btn {
        padding: .18rem .6rem; border-radius: 999px;
        font-size: .74rem; font-weight: 600;
        color: rgba(255,255,255,.7); text-decoration: none;
        border: 1.5px solid rgba(255,255,255,.18);
        transition: var(--transition); white-space: nowrap;
    }
    .lang-btn:hover, .lang-btn.active {
        background: #fff; color: var(--primary-dark);
        border-color: #fff;
    }

    /* ── Navbar ───────────────────────────────────────── */
    .navbar {
        position: sticky; top: 0; z-index: 200;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        height: var(--nav-h);
        transition: box-shadow var(--transition), background var(--transition);
    }
    .navbar.scrolled {
        box-shadow: var(--shadow-md);
        background: rgba(255,255,255,.96);
        backdrop-filter: blur(10px);
    }
    .nav-container {
        max-width: 1200px; margin: 0 auto; padding: 0 1.25rem;
        height: 100%; display: flex; align-items: center;
        gap: 1.25rem;
    }

    /* Logo */
    .nav-logo { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
    .nav-logo-svg { height: 44px; width: auto; display: block; }

    /* Search bar */
    .nav-search {
        flex: 1; max-width: 380px;
        position: relative;
        display: flex; align-items: center;
    }
    .nav-search-icon {
        position: absolute;
        <?= $isRtl ? 'right' : 'left' ?>: .85rem;
        color: var(--text-muted); font-size: .85rem; pointer-events: none;
    }
    .nav-search input {
        width: 100%;
        padding: .55rem 1rem .55rem <?= $isRtl ? '1rem' : '2.4rem' ?>;
        <?php if ($isRtl) echo 'padding-right: 2.4rem;' ?>
        border: 1.5px solid var(--border);
        border-radius: 999px; font-family: inherit;
        font-size: .88rem; background: var(--surface-2);
        color: var(--text); outline: none;
        transition: var(--transition);
    }
    .nav-search input:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    }
    .nav-search input::placeholder { color: var(--text-muted); }

    /* Nav links */
    .nav-links {
        list-style: none; display: flex; align-items: center; gap: .25rem;
        margin: 0; padding: 0;
    }
    .nav-links a {
        display: flex; align-items: center; gap: .35rem;
        padding: .45rem .85rem; border-radius: var(--radius-sm);
        font-size: .9rem; font-weight: 600; color: var(--text-muted);
        text-decoration: none; transition: var(--transition); white-space: nowrap;
    }
    .nav-links a:hover { color: var(--primary); background: var(--surface-2); }
    .nav-links a.active {
        color: var(--primary); background: var(--surface-2);
        position: relative;
    }
    .nav-links a.active::after {
        content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 20px; height: 2.5px; border-radius: 99px;
        background: var(--primary);
    }

    /* Nav actions */
    .nav-actions {
        display: flex; align-items: center; gap: .65rem;
        margin-<?= $isRtl ? 'right' : 'left' ?>: auto;
        flex-shrink: 0;
    }

    /* Cart */
    .nav-cart {
        position: relative; display: flex; align-items: center; justify-content: center;
        width: 42px; height: 42px; border-radius: 50%;
        background: var(--surface-2); color: var(--text);
        text-decoration: none; font-size: 1.1rem;
        transition: var(--transition);
    }
    .nav-cart:hover { background: var(--primary); color: #fff; transform: scale(1.08); }
    .cart-count {
        position: absolute; top: -3px;
        <?= $isRtl ? 'left' : 'right' ?>: -3px;
        background: var(--primary); color: #fff;
        border-radius: 999px; font-size: .65rem; font-weight: 700;
        min-width: 18px; height: 18px;
        display: flex; align-items: center; justify-content: center;
        padding: 0 4px;
        border: 2px solid var(--surface);
        line-height: 1;
    }
    .cart-count:empty, .cart-count[data-count="0"] { display: none; }

    /* Auth buttons */
    .btn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1.1rem; border-radius: 999px;
        font-family: inherit; font-weight: 600; font-size: .88rem;
        cursor: pointer; border: none; text-decoration: none;
        transition: var(--transition); white-space: nowrap;
    }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(22,163,74,.3); }
    .btn-outline {
        background: transparent; color: var(--primary);
        border: 1.5px solid var(--primary);
    }
    .btn-outline:hover { background: var(--surface-2); }

    /* User dropdown */
    .nav-user-menu { position: relative; }
    .btn-user {
        display: flex; align-items: center; gap: .4rem;
        background: var(--surface-2); border: 1.5px solid var(--border);
        border-radius: 999px; padding: .3rem .75rem .3rem .35rem;
        cursor: pointer; transition: var(--transition); font-family: inherit;
    }
    .btn-user:hover { border-color: var(--primary); background: #fff; }
    .user-avatar {
        width: 30px; height: 30px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem;
    }
    .btn-user .chevron { font-size: .68rem; color: var(--text-muted); transition: transform var(--transition); }
    .btn-user.open .chevron { transform: rotate(180deg); }

    .user-dropdown {
        position: absolute; top: calc(100% + .5rem);
        <?= $isRtl ? 'left' : 'right' ?>: 0;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius-md); padding: .4rem;
        box-shadow: var(--shadow-md); min-width: 180px;
        display: none; z-index: 300;
        animation: dropIn .18s ease;
    }
    .user-dropdown.open { display: block; }
    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .user-dropdown a {
        display: flex; align-items: center; gap: .6rem;
        padding: .6rem .85rem; border-radius: var(--radius-sm);
        font-size: .88rem; font-weight: 500; color: var(--text);
        text-decoration: none; transition: var(--transition);
    }
    .user-dropdown a:hover { background: var(--surface-2); color: var(--primary); }
    .user-dropdown a i    { width: 16px; text-align: center; color: var(--text-muted); }
    .user-dropdown a:hover i { color: var(--primary); }
    .user-dropdown .divider { height: 1px; background: var(--border); margin: .3rem 0; }
    .user-dropdown .text-danger       { color: #dc2626; }
    .user-dropdown .text-danger:hover { background: #fef2f2; color: #dc2626; }
    .user-dropdown .text-danger i     { color: #dc2626; }

    /* Hamburger */
    .nav-hamburger {
        display: none; flex-direction: column; justify-content: center;
        gap: 5px; background: none; border: none;
        cursor: pointer; padding: .4rem; border-radius: var(--radius-sm);
        transition: var(--transition);
    }
    .nav-hamburger:hover { background: var(--surface-2); }
    .nav-hamburger span {
        display: block; width: 22px; height: 2px;
        background: var(--text); border-radius: 99px;
        transition: var(--transition);
    }
    .nav-hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
    .nav-hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
    .nav-hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

    /* Mobile drawer */
    .mobile-drawer {
        display: none; position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 190;
    }
    .mobile-drawer.open { display: block; }
    .drawer-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,.45); backdrop-filter: blur(3px);
    }
    .drawer-panel {
        position: absolute;
        <?= $isRtl ? 'right' : 'left' ?>: 0;
        top: 0; bottom: 0; width: 280px;
        background: var(--surface);
        display: flex; flex-direction: column;
        box-shadow: var(--shadow-md);
        animation: slideIn .25s ease;
        overflow-y: auto;
    }
    @keyframes slideIn {
        from { transform: translateX(<?= $isRtl ? '100%' : '-100%' ?>); }
        to   { transform: translateX(0); }
    }
    .drawer-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--border);
    }
    .drawer-close {
        background: var(--surface-2); border: none; border-radius: 50%;
        width: 34px; height: 34px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: var(--text-muted); transition: var(--transition);
    }
    .drawer-close:hover { background: var(--primary); color: #fff; }
    .drawer-search {
        padding: .85rem 1.25rem; border-bottom: 1px solid var(--border);
    }
    .drawer-search input {
        width: 100%; padding: .6rem 1rem;
        border: 1.5px solid var(--border); border-radius: 999px;
        font-family: inherit; font-size: .9rem; outline: none;
        background: var(--surface-2);
    }
    .drawer-search input:focus { border-color: var(--primary); }
    .drawer-nav { list-style: none; padding: .6rem; flex: 1; }
    .drawer-nav li a {
        display: flex; align-items: center; gap: .75rem;
        padding: .8rem 1rem; border-radius: var(--radius-sm);
        font-size: .93rem; font-weight: 600;
        color: var(--text); text-decoration: none;
        transition: var(--transition);
    }
    .drawer-nav li a:hover, .drawer-nav li a.active {
        color: var(--primary); background: var(--surface-2);
    }
    .drawer-nav li a i { width: 20px; text-align: center; color: var(--text-muted); }
    .drawer-nav li a.active i, .drawer-nav li a:hover i { color: var(--primary); }
    .drawer-nav .drawer-divider { height: 1px; background: var(--border); margin: .4rem 0; }
    .drawer-footer {
        padding: 1rem 1.25rem; border-top: 1px solid var(--border);
        display: flex; flex-direction: column; gap: .6rem;
    }
    .drawer-footer .btn { justify-content: center; }
    .drawer-langs {
        padding: .75rem 1.25rem; border-top: 1px solid var(--border);
        display: flex; flex-wrap: wrap; gap: .4rem;
    }
    .drawer-langs a {
        padding: .25rem .7rem; border-radius: 999px; font-size: .78rem; font-weight: 600;
        color: var(--text-muted); border: 1.5px solid var(--border);
        text-decoration: none; transition: var(--transition);
    }
    .drawer-langs a.active { background: var(--primary); color: #fff; border-color: var(--primary); }

    /* Flash messages */
    .flash-messages { position: fixed; top: calc(var(--nav-h) + 2.5rem + 36px); right: 1.25rem; z-index: 500; display: flex; flex-direction: column; gap: .5rem; }
    [dir="rtl"] .flash-messages { right: auto; left: 1.25rem; }
    .flash { padding: .75rem 1.1rem; border-radius: var(--radius-sm); font-size: .88rem; font-weight: 500; box-shadow: var(--shadow-md); display: flex; align-items: center; gap: .5rem; animation: slideInFlash .3s ease; max-width: 340px; }
    @keyframes slideInFlash { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: none; } }
    .flash-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
    .flash-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

    /* Responsive breakpoint */
    @media (max-width: 900px) {
        .nav-links, .nav-search { display: none; }
        .nav-hamburger { display: flex; }
    }
    @media (max-width: 500px) {
        .top-bar-left { display: none; }
    }
    </style>

    <?= $extraHead ?? '' ?>
</head>
<body>

<!-- ══════════════════════════════════════════════════
     TOP BAR — announcement + language switcher
══════════════════════════════════════════════════ -->
<div class="top-bar">
    <div class="top-bar-inner">
        <div class="top-bar-left">
            <i class="fa-solid fa-truck-fast"></i>
            <span>
                <?php echo match($lang) {
                    'sw' => 'Uwasilishaji wa haraka Dar es Salaam',
                    'ar' => 'توصيل سريع في دار السلام',
                    'fr' => 'Livraison rapide à Dar es Salaam',
                    default => 'Fast delivery across Dar es Salaam',
                }; ?>
            </span>
        </div>
        <div class="lang-switcher">
            <i class="fa-solid fa-globe"></i>
            <?php foreach ($langMeta as $code => $meta): ?>
                <a href="<?= headerLangUrl($code) ?>"
                   class="lang-btn <?= $lang === $code ? 'active' : '' ?>"
                   title="<?= $meta['name'] ?>">
                    <?= $meta['flag'] ?> <?= $meta['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════════════ -->
<nav class="navbar" id="navbar" role="navigation" aria-label="Main navigation">
    <div class="nav-container">

        <!-- Logo -->
        <a href="<?= BASE_URL ?>/index.php" class="nav-logo" aria-label="FoodBites Home">
            <svg class="nav-logo-svg" viewBox="0 0 500 120" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="foodGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#16A34A"/>
                        <stop offset="100%" stop-color="#F59E0B"/>
                    </linearGradient>
                </defs>
                <!-- Icon circle -->
                <g transform="translate(10,10)">
                    <circle cx="50" cy="50" r="44" fill="#FFF4EF" stroke="url(#foodGrad)" stroke-width="3.5"/>
                    <!-- Fork -->
                    <path d="M38,28 L38,48 M34,28 L34,42 M42,28 L42,42 M38,48 L38,70"
                          stroke="#16A34A" stroke-width="3" stroke-linecap="round"/>
                    <!-- Knife -->
                    <rect x="57" y="28" width="7" height="24" rx="2" fill="#1A1A1A"/>
                    <circle cx="63.5" cy="38" r="4.5" fill="#FFF4EF"/>
                    <!-- Steam dots -->
                    <circle cx="50" cy="18" r="2" fill="#F59E0B" opacity=".5"/>
                    <circle cx="44" cy="14" r="1.5" fill="#F59E0B" opacity=".35"/>
                    <circle cx="56" cy="14" r="1.5" fill="#F59E0B" opacity=".35"/>
                </g>
                <!-- Wordmark -->
                <text x="122" y="68" font-family="Poppins,sans-serif" font-size="54"
                      font-weight="800" fill="url(#foodGrad)">Food</text>
                <text x="272" y="68" font-family="Poppins,sans-serif" font-size="54"
                      font-weight="800" fill="#1A1A1A">Bites</text>
                <text x="124" y="95" font-family="Poppins,sans-serif" font-size="15"
                      font-weight="600" fill="#6B6B6B" letter-spacing="4.5">TASTE OF TANZANIA</text>
            </svg>
        </a>

        <!-- Search bar (desktop) -->
        <div class="nav-search" role="search">
            <i class="fa-solid fa-magnifying-glass nav-search-icon"></i>
            <input type="search" id="navSearchInput"
                   placeholder="<?= e($N['search_placeholder']) ?>"
                   autocomplete="off" aria-label="<?= e($N['search_placeholder']) ?>"
                   onkeydown="if(event.key==='Enter'&&this.value.trim()) window.location='<?= BASE_URL ?>/customer/menu.php?search='+encodeURIComponent(this.value.trim())">
        </div>

        <!-- Nav links (desktop) -->
        <ul class="nav-links" id="navLinks">
            <li>
                <a href="<?= BASE_URL ?>/index.php"
                   class="<?= ($activePage ?? '') === 'home' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i> <?= e($N['home']) ?>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/customer/menu.php"
                   class="<?= ($activePage ?? '') === 'menu' ? 'active' : '' ?>">
                    <i class="fa-solid fa-utensils"></i> <?= e($N['menu']) ?>
                </a>
            </li>
            <?php if (isLoggedIn()): ?>
            <li>
                <a href="<?= BASE_URL ?>/customer/order_history.php"
                   class="<?= ($activePage ?? '') === 'orders' ? 'active' : '' ?>">
                    <i class="fa-solid fa-bag-shopping"></i> <?= e($N['orders']) ?>
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <!-- Actions -->
        <div class="nav-actions">

            <!-- Cart -->
            <a href="<?= BASE_URL ?>/customer/cart.php" class="nav-cart"
               aria-label="Shopping cart">
                <i class="fa-solid fa-basket-shopping"></i>
                <span class="cart-count" id="cartCount"><?= cartCount() ?: '' ?></span>
            </a>

            <?php if (isLoggedIn()): ?>
                <!-- User menu -->
                <div class="nav-user-menu">
                    <button class="btn-user" id="userMenuBtn" aria-haspopup="true" aria-expanded="false">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <i class="fa-solid fa-chevron-down chevron"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown" role="menu">
                        <div style="padding:.5rem .85rem .25rem;font-size:.78rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.07em;">
                            <?= e($_SESSION['user_name'] ?? '') ?>
                        </div>
                        <div class="divider"></div>
                        <a href="<?= BASE_URL ?>/customer/order_history.php" role="menuitem">
                            <i class="fa-solid fa-bag-shopping"></i> <?= e($N['orders']) ?>
                        </a>
                        <a href="<?= BASE_URL ?>/customer/profile.php" role="menuitem">
                            <i class="fa-solid fa-circle-user"></i>
                            <?php echo match($lang) {
                                'sw' => 'Wasifu', 'ar' => 'الملف الشخصي',
                                'fr' => 'Profil', default => 'Profile',
                            }; ?>
                        </a>
                        <div class="divider"></div>
                        <a href="<?= BASE_URL ?>/auth/logout.php" class="text-danger" role="menuitem">
                            <i class="fa-solid fa-power-off"></i> <?= e($N['logout']) ?>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login.php"  class="btn btn-outline">
                    <i class="fa-solid fa-right-to-bracket"></i> <?= e($N['login']) ?>
                </a>
                <a href="<?= BASE_URL ?>/auth/register.php" class="btn btn-primary">
                    <i class="fa-solid fa-user-plus"></i> <?= e($N['join']) ?>
                </a>
            <?php endif; ?>

            <!-- Hamburger -->
            <button class="nav-hamburger" id="hamburgerBtn"
                    aria-label="Toggle mobile menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

<!-- ══════════════════════════════════════════════════
     MOBILE DRAWER
══════════════════════════════════════════════════ -->
<div class="mobile-drawer" id="mobileDrawer" aria-hidden="true" role="dialog">
    <div class="drawer-overlay" id="drawerOverlay"></div>
    <div class="drawer-panel">

        <div class="drawer-head">
            <a href="<?= BASE_URL ?>/index.php" class="nav-logo" style="transform:scale(.85);transform-origin:<?= $isRtl ? 'right' : 'left' ?> center;">
                <svg viewBox="0 0 500 120" style="height:36px;width:auto" xmlns="http://www.w3.org/2000/svg">
                    <defs><linearGradient id="fg2" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#16A34A"/><stop offset="100%" stop-color="#F59E0B"/></linearGradient></defs>
                    <g transform="translate(10,10)"><circle cx="50" cy="50" r="44" fill="#FFF4EF" stroke="url(#fg2)" stroke-width="3.5"/><path d="M38,28 L38,48 M34,28 L34,42 M42,28 L42,42 M38,48 L38,70" stroke="#16A34A" stroke-width="3" stroke-linecap="round"/><rect x="57" y="28" width="7" height="24" rx="2" fill="#1A1A1A"/><circle cx="63.5" cy="38" r="4.5" fill="#FFF4EF"/></g>
                    <text x="122" y="68" font-family="Poppins,sans-serif" font-size="54" font-weight="800" fill="url(#fg2)">Food</text>
                    <text x="272" y="68" font-family="Poppins,sans-serif" font-size="54" font-weight="800" fill="#1A1A1A">Bites</text>
                </svg>
            </a>
            <button class="drawer-close" id="drawerClose" aria-label="Close menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="drawer-search">
            <input type="search" placeholder="<?= e($N['search_placeholder']) ?>"
                   onkeydown="if(event.key==='Enter'&&this.value.trim()) window.location='<?= BASE_URL ?>/customer/menu.php?search='+encodeURIComponent(this.value.trim())">
        </div>

        <ul class="drawer-nav">
            <li>
                <a href="<?= BASE_URL ?>/index.php" class="<?= ($activePage ?? '') === 'home' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i> <?= e($N['home']) ?>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/customer/menu.php" class="<?= ($activePage ?? '') === 'menu' ? 'active' : '' ?>">
                    <i class="fa-solid fa-utensils"></i> <?= e($N['menu']) ?>
                </a>
            </li>
            <?php if (isLoggedIn()): ?>
            <li>
                <a href="<?= BASE_URL ?>/customer/order_history.php">
                    <i class="fa-solid fa-bag-shopping"></i> <?= e($N['orders']) ?>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/customer/profile.php">
                    <i class="fa-solid fa-circle-user"></i>
                    <?php echo match($lang) { 'sw'=>'Wasifu','ar'=>'الملف الشخصي','fr'=>'Profil',default=>'Profile' }; ?>
                </a>
            </li>
            <li><div class="drawer-divider"></div></li>
            <li>
                <a href="<?= BASE_URL ?>/auth/logout.php" style="color:#dc2626;">
                    <i class="fa-solid fa-power-off" style="color:#dc2626;"></i> <?= e($N['logout']) ?>
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <!-- Lang switcher (drawer) -->
        <div class="drawer-langs">
            <?php foreach ($langMeta as $code => $meta): ?>
                <a href="<?= headerLangUrl($code) ?>" class="<?= $lang === $code ? 'active' : '' ?>">
                    <?= $meta['flag'] ?> <?= $meta['name'] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!isLoggedIn()): ?>
        <div class="drawer-footer">
            <a href="<?= BASE_URL ?>/auth/login.php"   class="btn btn-outline" style="justify-content:center;">
                <i class="fa-solid fa-right-to-bracket"></i> <?= e($N['login']) ?>
            </a>
            <a href="<?= BASE_URL ?>/auth/register.php" class="btn btn-primary" style="justify-content:center;">
                <i class="fa-solid fa-user-plus"></i> <?= e($N['join']) ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Flash messages -->
<div class="flash-messages" role="alert" aria-live="polite">
    <?php renderFlash(); ?>
</div>

<main class="main-content">

<script>
(function () {
    // ── Scroll shadow ──────────────────────────────────────
    const nav = document.getElementById('navbar');
    window.addEventListener('scroll', () =>
        nav.classList.toggle('scrolled', window.scrollY > 10), { passive: true });

    // ── User dropdown ──────────────────────────────────────
    const userBtn  = document.getElementById('userMenuBtn');
    const userDrop = document.getElementById('userDropdown');
    if (userBtn && userDrop) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const open = userDrop.classList.toggle('open');
            userBtn.classList.toggle('open', open);
            userBtn.setAttribute('aria-expanded', open);
        });
        document.addEventListener('click', () => {
            userDrop.classList.remove('open');
            userBtn.classList.remove('open');
            userBtn.setAttribute('aria-expanded', 'false');
        });
    }

    // ── Mobile drawer ──────────────────────────────────────
    const hamburger = document.getElementById('hamburgerBtn');
    const drawer    = document.getElementById('mobileDrawer');
    const overlay   = document.getElementById('drawerOverlay');
    const closeBtn  = document.getElementById('drawerClose');

    function openDrawer() {
        drawer.classList.add('open');
        drawer.setAttribute('aria-hidden', 'false');
        hamburger.classList.add('open');
        hamburger.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        drawer.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    hamburger?.addEventListener('click', openDrawer);
    overlay?.addEventListener('click', closeDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
})();
</script>