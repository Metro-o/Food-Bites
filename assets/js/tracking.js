/**
 * FoodBites — Order Tracking JavaScript
 * Polls /api/order.php?action=get_status every 5 seconds
 * Updates timeline steps and status badge in real-time
 */

(function () {
    'use strict';

    // These are set inline in order_tracking.php
    const BASE    = window.FOODBITES_BASE    || (window.location.origin + '/FoodBites');
    const orderId = window.FOODBITES_ORDER_ID;

    if (!orderId) return;  // Guard — only active on tracking page

    // Status → numeric index for timeline progression
    const STATUS_IDX = {
        pending:   0,
        preparing: 1,
        ready:     2,
        delivered: 3,
    };

    let lastStatus   = window.FOODBITES_LAST_STATUS || 'pending';
    let pollInterval = null;

    // ── Poll for status changes ───────────────────────────────
    async function pollStatus() {
        try {
            const res  = await fetch(`${BASE}/api/order.php?action=get_status&order_id=${orderId}`, {
                cache: 'no-store'
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();

            if (!data.success) return;

            if (data.status !== lastStatus) {
                lastStatus = data.status;
                updateTimeline(data.status);

                // Stop polling once delivered
                if (data.status === 'delivered') {
                    stopPolling();
                    onDelivered();
                }
            }

            // Update refresh indicator pulse
            flashRefreshDot();

        } catch (e) {
            // Silent fail — will retry next interval
            const dot = document.querySelector('.refresh-dot');
            if (dot) dot.style.background = 'var(--cancelled)';
            setTimeout(() => { if (dot) dot.style.background = ''; }, 2000);
        }
    }

    // ── Update the visual timeline ────────────────────────────
    function updateTimeline(status) {
        const idx = STATUS_IDX[status] ?? 0;

        // Update step classes
        document.querySelectorAll('.timeline-step').forEach((step, i) => {
            step.classList.toggle('done',    i <= idx);
            step.classList.toggle('current', i === idx);
        });

        // Update status badge
        const badge = document.getElementById('statusBadge');
        if (badge) {
            badge.className    = `badge badge-${status}`;
            badge.textContent  = status.charAt(0).toUpperCase() + status.slice(1);

            // Animate badge change
            badge.style.transform  = 'scale(1.2)';
            badge.style.transition = 'transform .2s ease';
            setTimeout(() => badge.style.transform = 'scale(1)', 200);
        }

        // Show toast for user feedback
        const messages = {
            preparing: '👨‍🍳 Kitchen is now preparing your order!',
            ready:     '✅ Your order is ready and on its way!',
            delivered: '🎉 Order delivered! Enjoy your meal!',
        };
        if (messages[status]) {
            window.showToast?.(messages[status], 'success');
        }
    }

    // ── Handle delivered state ────────────────────────────────
    function onDelivered() {
        const indicator = document.getElementById('refreshIndicator');
        if (indicator) {
            indicator.innerHTML = `
                <span style="color:var(--success);font-size:1rem">
                    ✅ Your order has been delivered! Enjoy your meal 🎉
                </span>`;
        }
    }

    // ── Flash the refresh dot ─────────────────────────────────
    function flashRefreshDot() {
        const dot = document.querySelector('.refresh-dot');
        if (!dot) return;
        dot.style.opacity   = '1';
        dot.style.transform = 'scale(1.4)';
        setTimeout(() => {
            dot.style.transition = 'all .3s ease';
            dot.style.transform  = 'scale(1)';
        }, 150);
    }

    // ── Stop polling ──────────────────────────────────────────
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    // ── Countdown timer to next poll ──────────────────────────
    let countdown = 5;
    function updateCountdown() {
        countdown--;
        if (countdown <= 0) {
            countdown = 5;
        }
        const el = document.getElementById('refreshIndicator');
        const span = el?.querySelector('span:last-child');
        if (span && span.textContent.includes('Auto-updating')) {
            span.textContent = `Auto-updating every 5 seconds (next in ${countdown}s)`;
        }
    }

    // ── Init ──────────────────────────────────────────────────
    if (lastStatus !== 'delivered' && lastStatus !== 'cancelled') {
        pollInterval = setInterval(() => {
            pollStatus();
            updateCountdown();
        }, 5000);
    } else if (lastStatus === 'delivered') {
        onDelivered();
    }

})();
