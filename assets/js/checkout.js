/**
 * FoodBites — Checkout JavaScript
 * Payment method switching, mobile money UI, form validation
 */

(function () {
    'use strict';

    // ── Payment method card highlight ─────────────────────────
    document.querySelectorAll('.payment-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            // Deactivate all
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('active');
            });
            // Activate selected
            this.closest('.payment-option').classList.add('active');

            // Show / hide mobile money input
            const mmInput = document.getElementById('mmInput');
            const mmHint  = document.getElementById('mmHint');
            const isMM    = ['mpesa', 'tigopesa', 'airtel'].includes(this.value);

            if (mmInput) {
                mmInput.style.display = isMM ? 'block' : 'none';
                // Animate in
                if (isMM) {
                    mmInput.style.opacity   = '0';
                    mmInput.style.transform = 'translateY(-8px)';
                    requestAnimationFrame(() => {
                        mmInput.style.transition = 'all 0.25s ease';
                        mmInput.style.opacity    = '1';
                        mmInput.style.transform  = 'translateY(0)';
                    });
                }
            }

            const hints = {
                mpesa:    'Enter your Vodacom M-Pesa number (e.g. +255 712 xxxxxx)',
                tigopesa: 'Enter your Tigo Pesa number (e.g. +255 653 xxxxxx)',
                airtel:   'Enter your Airtel Money number (e.g. +255 685 xxxxxx)',
            };
            if (mmHint && hints[this.value]) {
                mmHint.textContent = hints[this.value];
            }
        });
    });

    // ── Delivery date: enforce today minimum ──────────────────
    const dateInput = document.getElementById('delivery_date');
    const timeInput = document.getElementById('delivery_time_input');

    if (dateInput) {
        dateInput.addEventListener('change', function () {
            const today   = new Date().toISOString().split('T')[0];
            const isToday = this.value === today;

            if (isToday && timeInput) {
                // Enforce minimum 1 hour from now
                const minTime = new Date(Date.now() + 60 * 60 * 1000);
                timeInput.min = minTime.toTimeString().slice(0, 5);

                if (timeInput.value && timeInput.value < timeInput.min) {
                    timeInput.value = timeInput.min;
                    window.showToast?.('Delivery time adjusted to at least 1 hour from now.', 'info');
                }
            } else if (timeInput) {
                timeInput.min = '07:00';
            }
        });
    }

    // ── Order type label update ───────────────────────────────
    const orderTypeSelect = document.getElementById('order_type');
    if (orderTypeSelect) {
        orderTypeSelect.addEventListener('change', function () {
            const notes = document.getElementById('notes');
            if (!notes) return;

            const placeholders = {
                standard:     'Allergies, spice level, gate/building number…',
                bulk:         'Number of people, dietary requirements, meal preferences for the group…',
                subscription: 'Preferred meal days, dietary restrictions, subscription start date…',
            };
            notes.placeholder = placeholders[this.value] || placeholders.standard;
        });
    }

    // ── Global place order handler ────────────────────────────
    window.placeOrder = async function () {
        const locationInput = document.getElementById('delivery_location');
        const date          = document.getElementById('delivery_date')?.value;
        const time          = document.getElementById('delivery_time_input')?.value;
        const method        = document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
        const notes         = document.getElementById('notes')?.value?.trim() || '';
        const type          = document.getElementById('order_type')?.value || 'standard';
        const BASE          = window.location.origin + '/FoodBites';

        // Validate delivery location
        if (!locationInput || !locationInput.value.trim()) {
            locationInput?.focus();
            locationInput?.classList.add('shake');
            window.showToast?.('Please enter a delivery location.', 'error');
            setTimeout(() => locationInput?.classList.remove('shake'), 600);
            return;
        }

        // Validate mobile money phone
        if (['mpesa', 'tigopesa', 'airtel'].includes(method)) {
            const phone = document.getElementById('mm_phone')?.value?.trim();
            if (!phone) {
                document.getElementById('mm_phone')?.focus();
                window.showToast?.('Please enter your mobile money number.', 'error');
                return;
            }
        }

        const btn         = document.getElementById('placeOrderBtn');
        const btnText     = document.getElementById('placeOrderText');
        const btnSpinner  = document.getElementById('placeOrderSpinner');

        btn.disabled            = true;
        if (btnText)    btnText.style.display    = 'none';
        if (btnSpinner) btnSpinner.style.display = 'inline';

        const deliveryDateTime = date && time ? `${date}T${time}:00` : '';

        try {
            const res  = await fetch(`${BASE}/api/order.php`, {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    new URLSearchParams({
                    action:            'place_order',
                    delivery_location: locationInput.value.trim(),
                    delivery_time:     deliveryDateTime,
                    payment_method:    method,
                    notes,
                    order_type:        type,
                })
            });

            const data = await res.json();

            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.showToast?.(data.message || 'Failed to place order. Please try again.', 'error');
                btn.disabled            = false;
                if (btnText)    btnText.style.display    = 'inline';
                if (btnSpinner) btnSpinner.style.display = 'none';
            }
        } catch (e) {
            window.showToast?.('Network error. Please check your connection and try again.', 'error');
            btn.disabled            = false;
            if (btnText)    btnText.style.display    = 'inline';
            if (btnSpinner) btnSpinner.style.display = 'none';
        }
    };

})();
