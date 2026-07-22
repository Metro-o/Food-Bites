<?php
/**
 * FoodBites — Terms of Service
 */
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Terms of Service';
$pageDesc  = 'The terms and conditions for using FoodBites.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title">Terms of Service</h1>
        <p class="page-sub">Last updated: <?= date('d M Y') ?></p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:760px; line-height:1.8;">

        <h2>Orders &amp; Payment</h2>
        <p>All prices are listed in Tanzanian Shillings (TZS) and include applicable taxes. A delivery fee is added at checkout. Orders can be paid via Cash on Delivery, M-Pesa, Tigo Pesa, or Airtel Money.</p>

        <h2>Order Fulfilment</h2>
        <p>Once placed, an order moves through Pending → Preparing → Ready → Delivered. Estimated delivery times are best-effort and may vary due to traffic, weather, or order volume. You can track your order's live status from <a href="<?= BASE_URL ?>/customer/order_history.php">My Orders</a>.</p>

        <h2>Cancellations</h2>
        <p>Orders can only be cancelled before preparation has started. Please contact us as soon as possible via WhatsApp if you need to cancel or change an order.</p>

        <h2>Accuracy of Menu Information</h2>
        <p>We do our best to keep menu items, prices, and availability up to date. Occasionally an item may be unavailable after ordering; in that case, we'll contact you to substitute or refund that item.</p>

        <h2>Account Responsibility</h2>
        <p>You're responsible for keeping your account password confidential and for all activity under your account. Notify us immediately if you suspect unauthorized use.</p>

        <h2>Contact</h2>
        <p>Questions about these terms? Reach us at <a href="mailto:hello@foodbites.co.tz">hello@foodbites.co.tz</a> or via WhatsApp at <a href="https://wa.me/255700000000">+255 700 000 000</a>.</p>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
