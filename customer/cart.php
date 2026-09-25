<?php
/**
 * FoodBites — Customer: Cart
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('customer');

$items    = getCart();
$subtotal = cartSubtotal($items);
$total    = $subtotal + DELIVERY_FEE;

$pageTitle = 'Your Cart';
$activePage = 'cart';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title"><i class="fa-solid fa-cart-shopping"></i> Your Cart</h1>
        <p class="page-sub"><?= count($items) ?> item<?= count($items) !== 1 ? 's' : '' ?> in your cart</p>
    </div>
</section>

<section class="cart-section">
    <div class="container">
        <?php if (empty($items)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                <h2>Your cart is empty</h2>
                <p>Add some delicious Tanzanian food to get started!</p>
                <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-primary btn-lg">Browse Menu →</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">

                <!-- Cart Items -->
                <div class="cart-items" id="cartItems">
                    <?php foreach ($items as $item): ?>
                        <div class="cart-item" id="cartItem-<?= $item['id'] ?>" data-cart-id="<?= $item['id'] ?>">
                            <div class="cart-item-img">
                                <img src="<?= productImage($item['image']) ?>"
                                     alt="<?= e($item['name']) ?>"
                                     onerror="this.src='<?= BASE_URL ?>/assets/images/default_food.jpg'">
                            </div>
                            <div class="cart-item-info">
                                <h4 class="cart-item-name"><?= e($item['name']) ?></h4>
                                <p class="cart-item-price"><?= formatPrice((float)$item['unit_price']) ?> each</p>
                            </div>
                            <div class="cart-item-controls">
                                <div class="qty-stepper">
                                    <button class="qty-btn" onclick="cartQty(<?= $item['id'] ?>, <?= $item['quantity'] - 1 ?>)">−</button>
                                    <span class="qty-val" id="qty-<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                                    <button class="qty-btn" onclick="cartQty(<?= $item['id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                                </div>
                                <div class="cart-item-subtotal" id="subtotal-<?= $item['id'] ?>">
                                    <?= formatPrice((float)$item['unit_price'] * $item['quantity']) ?>
                                </div>
                                <button class="btn-remove" onclick="removeItem(<?= $item['id'] ?>)" aria-label="Remove item"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Order Summary -->
                <div class="cart-summary" id="cartSummary">
                    <h3 class="summary-title">Order Summary</h3>
                    <div class="summary-rows">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="summarySubtotal"><?= formatPrice($subtotal) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Fee</span>
                            <span><?= formatPrice(DELIVERY_FEE) ?></span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span id="summaryTotal"><?= formatPrice($total) ?></span>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>/customer/checkout.php" class="btn btn-primary btn-full btn-lg" id="checkoutBtn">
                        Proceed to Checkout →
                    </a>
                    <a href="<?= BASE_URL ?>/customer/menu.php" class="btn btn-ghost btn-full">
                        + Add More Items
                    </a>
                    <div class="summary-note">
                        <span><i class="fa-solid fa-location-dot"></i></span> Free delivery for orders above TZS 50,000
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>
</section>

<script>
const BASE = '<?= BASE_URL ?>';

async function cartQty(cartId, newQty) {
    const res = await fetch(`${BASE}/api/cart.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'update', cart_id: cartId, quantity: newQty })
    });
    const data = await res.json();

    if (data.success) {
        if (newQty <= 0) {
            document.getElementById(`cartItem-${cartId}`)?.remove();
            checkEmptyCart();
        } else {
            document.getElementById(`qty-${cartId}`).textContent = newQty;
        }
        document.getElementById('summarySubtotal').textContent = data.subtotal_fmt;
        document.getElementById('summaryTotal').textContent     = data.total_fmt;
        updateNavCartCount(data.count);
    }
}

async function removeItem(cartId) {
    const row = document.getElementById(`cartItem-${cartId}`);
    row.classList.add('removing');
    setTimeout(async () => {
        const res = await fetch(`${BASE}/api/cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'remove', cart_id: cartId })
        });
        const data = await res.json();
        row.remove();
        document.getElementById('summarySubtotal').textContent = data.subtotal_fmt;
        document.getElementById('summaryTotal').textContent     = data.total_fmt;
        updateNavCartCount(data.count);
        checkEmptyCart();
    }, 300);
}

function checkEmptyCart() {
    if (!document.querySelector('.cart-item')) {
        document.querySelector('.cart-layout').innerHTML = `
            <div class="empty-state" style="grid-column:1/-1">
                <div class="empty-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                <h2>Your cart is empty</h2>
                <p>Add some delicious Tanzanian food to get started!</p>
                <a href="${BASE}/customer/menu.php" class="btn btn-primary btn-lg">Browse Menu →</a>
            </div>`;
    }
}

function updateNavCartCount(count) {
    const el = document.getElementById('cartCount');
    if (el) el.textContent = count;
}
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
