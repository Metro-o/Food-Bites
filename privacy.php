<?php
/**
 * FoodBites — Privacy Policy
 */
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Privacy Policy';
$pageDesc  = 'How FoodBites collects, uses, and protects your information.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title">Privacy Policy</h1>
        <p class="page-sub">Last updated: <?= date('d M Y') ?></p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:760px; line-height:1.8;">

        <h2>Information We Collect</h2>
        <p>When you create a FoodBites account or place an order, we collect your name, email address, phone number, and delivery location. Order details (items, payment method) are stored so we can fulfil and track your orders.</p>

        <h2>How We Use Your Information</h2>
        <p>We use your information to process orders, deliver food, communicate order status, and improve our service. We do not sell your personal information to third parties.</p>

        <h2>Payment Information</h2>
        <p>Mobile money numbers (M-Pesa, Tigo Pesa, Airtel Money) provided at checkout are used only to coordinate payment for your order and are not shared beyond what's required to process that payment.</p>

        <h2>Data Security</h2>
        <p>Passwords are stored using industry-standard hashing (bcrypt) and are never stored or transmitted in plain text. We use secure session handling to protect your account.</p>

        <h2>Your Rights</h2>
        <p>You may update your account details at any time from your <a href="<?= BASE_URL ?>/customer/profile.php">Profile</a> page, or contact us at <a href="mailto:hello@foodbites.co.tz">hello@foodbites.co.tz</a> to request deletion of your account and associated data.</p>

        <h2>Contact</h2>
        <p>Questions about this policy? Reach us at <a href="mailto:hello@foodbites.co.tz">hello@foodbites.co.tz</a> or via WhatsApp at <a href="https://wa.me/255700000000">+255 700 000 000</a>.</p>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
