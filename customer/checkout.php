<?php
/**
 * FoodBites — Customer: Checkout
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole('customer');

$items    = getCart();
$subtotal = cartSubtotal($items);
$total    = $subtotal + DELIVERY_FEE;

if (empty($items)) {
    setFlash('error', 'Your cart is empty. Please add items before checking out.');
    redirect(BASE_URL . '/customer/menu.php');
}

$minDate = date('Y-m-d');
$minTime = date('H:i', strtotime('+1 hour'));

$pageTitle = 'Checkout';
$activePage = 'checkout';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<section class="page-hero page-hero-sm">
    <div class="container">
        <h1 class="page-title">Checkout</h1>
        <p class="page-sub">Almost there! Confirm your delivery details.</p>
    </div>
</section>

<section class="checkout-section">
    <div class="container">
        <div class="checkout-layout">

            <!-- ── LEFT: Delivery & Payment Form ─────────────── -->
            <div class="checkout-form-col">

                <!-- Step 1: Delivery -->
                <div class="checkout-card">
                    <h2 class="checkout-card-title">
                        <span class="step-badge">1</span> Delivery Details
                    </h2>

                    <div class="form-group">
                        <label for="delivery_location" class="form-label"><i class="fa-solid fa-location-dot"></i> Delivery Location *</label>
                        <input type="text" id="delivery_location" name="delivery_location"
                               class="form-control" placeholder="e.g. Kinondoni, Kariakoo, Mikocheni…"
                               required autocomplete="street-address">
                        <small class="form-hint">Be specific: include area name and any landmarks.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="delivery_date" class="form-label"><i class="fa-solid fa-calendar-day"></i> Delivery Date *</label>
                            <input type="date" id="delivery_date" class="form-control"
                                   min="<?= $minDate ?>" value="<?= $minDate ?>">
                        </div>
                        <div class="form-group">
                            <label for="delivery_time_input" class="form-label"><i class="fa-solid fa-clock"></i> Delivery Time *</label>
                            <input type="time" id="delivery_time_input" class="form-control"
                                   value="<?= $minTime ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="order_type" class="form-label"><i class="fa-solid fa-clipboard-list"></i> Order Type</label>
                        <select id="order_type" class="form-control select-control">
                            <option value="standard">Standard Order</option>
                            <option value="bulk">Bulk / Office Order (10+ persons)</option>
                            <option value="subscription">Monthly Meal Plan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label"><i class="fa-solid fa-note-sticky"></i> Special Notes <span class="form-optional">(optional)</span></label>
                        <textarea id="notes" class="form-control" rows="3"
                                  placeholder="Allergies, spice level, gate/building details…"></textarea>
                    </div>
                </div>

                <!-- Step 2: Payment -->
                <div class="checkout-card">
                    <h2 class="checkout-card-title">
                        <span class="step-badge">2</span> Payment Method
                    </h2>

                    <div class="payment-methods" id="paymentMethods">

                        <!-- Cash on Delivery -->
                        <label class="payment-option active" for="pay_cash">
                            <input type="radio" name="payment_method" id="pay_cash" value="cash" checked class="payment-radio">
                            <div class="payment-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                            <div class="payment-info">
                                <span class="payment-name">Cash on Delivery</span>
                                <span class="payment-desc">Pay when your order arrives</span>
                            </div>
                            <div class="payment-check"><i class="fa-solid fa-check"></i></div>
                        </label>

                        <!-- M-Pesa -->
                        <label class="payment-option" for="pay_mpesa">
                            <input type="radio" name="payment_method" id="pay_mpesa" value="mpesa" class="payment-radio">
                            <div class="payment-icon" style="background:#00A651"><i class="fa-solid fa-mobile-screen"></i></div>
                            <div class="payment-info">
                                <span class="payment-name">M-Pesa</span>
                                <span class="payment-desc">Vodacom mobile money</span>
                            </div>
                            <div class="payment-check"><i class="fa-solid fa-check"></i></div>
                        </label>

                        <!-- Tigo Pesa -->
                        <label class="payment-option" for="pay_tigo">
                            <input type="radio" name="payment_method" id="pay_tigo" value="tigopesa" class="payment-radio">
                            <div class="payment-icon" style="background:#003087"><i class="fa-solid fa-mobile-screen"></i></div>
                            <div class="payment-info">
                                <span class="payment-name">Tigo Pesa</span>
                                <span class="payment-desc">Tigo mobile money</span>
                            </div>
                            <div class="payment-check"><i class="fa-solid fa-check"></i></div>
                        </label>

                        <!-- Airtel Money -->
                        <label class="payment-option" for="pay_airtel">
                            <input type="radio" name="payment_method" id="pay_airtel" value="airtel" class="payment-radio">
                            <div class="payment-icon" style="background:#E40000"><i class="fa-solid fa-mobile-screen"></i></div>
                            <div class="payment-info">
                                <span class="payment-name">Airtel Money</span>
                                <span class="payment-desc">Airtel mobile money</span>
                            </div>
                            <div class="payment-check"><i class="fa-solid fa-check"></i></div>
                        </label>
                    </div>

                    <!-- Mobile money phone input (shown conditionally) -->
                    <div class="mm-input" id="mmInput" style="display:none">
                        <div class="form-group">
                            <label for="mm_phone" class="form-label"><i class="fa-solid fa-mobile-screen"></i> Mobile Money Number *</label>
                            <input type="tel" id="mm_phone" class="form-control"
                                   placeholder="+255 712 345 678" autocomplete="tel">
                            <small class="form-hint" id="mmHint">Enter your registered mobile money number</small>
                        </div>
                        <div class="mm-pending-note">
                            <i class="fa-solid fa-triangle-exclamation"></i> Mobile money integration is in demo mode. You'll receive a payment prompt on your phone after placing the order.
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── RIGHT: Order Summary ───────────────────────── -->
            <div class="checkout-summary">
                <div class="checkout-card sticky-card">
                    <h2 class="checkout-card-title"><i class="fa-solid fa-clipboard-list"></i> Order Summary</h2>

                    <div class="checkout-items">
                        <?php foreach ($items as $item): ?>
                            <div class="checkout-item">
                                <div class="checkout-item-img">
                                    <img src="<?= productImage($item['image']) ?>" alt="<?= e($item['name']) ?>"
                                         onerror="this.src='<?= BASE_URL ?>/assets/images/default_food.jpg'">
                                    <span class="checkout-qty"><?= $item['quantity'] ?></span>
                                </div>
                                <div class="checkout-item-name"><?= e($item['name']) ?></div>
                                <div class="checkout-item-price"><?= formatPrice((float)$item['unit_price'] * $item['quantity']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-rows">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span><?= formatPrice($subtotal) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Fee</span>
                            <span><?= formatPrice(DELIVERY_FEE) ?></span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span><?= formatPrice($total) ?></span>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-full btn-xl" id="placeOrderBtn" onclick="placeOrder()">
                        <span id="placeOrderText"><i class="fa-solid fa-cart-shopping"></i> Place Order</span>
                        <span id="placeOrderSpinner" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i> Placing…</span>
                    </button>
                    <p class="checkout-security"><i class="fa-solid fa-lock"></i> Secure checkout · Your data is safe</p>
                </div>
            </div>

        </div>
    </div>
</section>

<div id="toastContainer" class="toast-container"></div>

<script>
const BASE = '<?= BASE_URL ?>';

// Payment method selection highlighting
document.querySelectorAll('.payment-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
        this.closest('.payment-option').classList.add('active');

        const mm = document.getElementById('mmInput');
        const isCard = ['mpesa','tigopesa','airtel'].includes(this.value);
        mm.style.display = isCard ? 'block' : 'none';

        const hints = { mpesa: 'Vodacom M-Pesa number', tigopesa: 'Tigo Pesa number', airtel: 'Airtel Money number' };
        document.getElementById('mmHint').textContent = hints[this.value] || '';
    });
});

async function placeOrder() {
    const location = document.getElementById('delivery_location').value.trim();
    if (!location) {
        showToast('Please enter a delivery location.', 'error');
        document.getElementById('delivery_location').focus();
        return;
    }

    const date    = document.getElementById('delivery_date').value;
    const time    = document.getElementById('delivery_time_input').value;
    const method  = document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
    const notes   = document.getElementById('notes').value.trim();
    const type    = document.getElementById('order_type').value;
    const delivery_time = `${date}T${time}:00`;

    // Validate mobile money phone if selected
    let mmPhone = '';
    if (['mpesa','tigopesa','airtel'].includes(method)) {
        mmPhone = document.getElementById('mm_phone').value.trim();
        if (!mmPhone) {
            showToast('Please enter your mobile money number.', 'error');
            return;
        }
    }

    document.getElementById('placeOrderText').style.display   = 'none';
    document.getElementById('placeOrderSpinner').style.display = 'inline';
    document.getElementById('placeOrderBtn').disabled = true;

    try {
        const res = await fetch(`${BASE}/api/order.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'place_order',
                delivery_location: location,
                delivery_time,
                payment_method: method,
                mm_phone: mmPhone,
                notes,
                order_type: type,
            })
        });
        const data = await res.json();

        if (data.success) {
            window.location.href = data.redirect;
        } else {
            showToast(data.message || 'Failed to place order.', 'error');
            document.getElementById('placeOrderText').style.display   = 'inline';
            document.getElementById('placeOrderSpinner').style.display = 'none';
            document.getElementById('placeOrderBtn').disabled = false;
        }
    } catch (e) {
        showToast('Network error. Please try again.', 'error');
        document.getElementById('placeOrderBtn').disabled = false;
    }
}

function showToast(msg, type = 'info') {
    const t = document.createElement('div');
    t.className = `toast toast-${type}`;
    t.textContent = msg;
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(() => t.classList.add('show'), 10);
    setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 300); }, 3500);
}
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
