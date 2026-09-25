/**
 * FoodBites — Global JavaScript
 * Toast helper, cart count, flash auto-dismiss.
 * Navbar scroll / user dropdown / mobile drawer are handled by the
 * inline script in includes/header.php — do not duplicate them here
 * (a duplicate click listener previously canceled out the dropdown's
 * own toggle, making the account menu / logout unreachable).
 */

(function () {
    'use strict';

    // ── Flash message auto-dismiss ────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .4s ease, transform .4s ease';
            alert.style.opacity    = '0';
            alert.style.transform  = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });

    // ── Global toast helper ───────────────────────────────────
    window.showToast = function (message, type = 'info') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id        = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        const toast     = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        container.appendChild(toast);
        requestAnimationFrame(() => {
            requestAnimationFrame(() => toast.classList.add('show'));
        });
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    };

    // ── Update nav cart count ─────────────────────────────────
    window.updateNavCartCount = function (count) {
        const el = document.getElementById('cartCount');
        if (el) {
            el.textContent = count > 0 ? count : 0;
            el.style.transform  = 'scale(1.4)';
            el.style.transition = 'transform .2s ease';
            setTimeout(() => el.style.transform = 'scale(1)', 200);
        }
    };

})();
