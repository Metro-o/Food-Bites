/**
 * FoodBites — Cart JavaScript
 * addToCart function used on home page and menu page
 */

const BASE_URL = window.BASE_URL || (window.location.origin + '/FoodBites');

/**
 * Add a product to the cart via AJAX
 * @param {number} productId
 * @param {HTMLElement} btn - The button that was clicked
 */
async function addToCart(productId, btn) {
    // Check if user is logged in (redirect if not)
    if (!productId) return;

    const originalContent = btn.innerHTML;
    btn.classList.add('adding');
    btn.disabled    = true;
    btn.innerHTML   = '<i class="fa-solid fa-check"></i> Added!';

    try {
        const res = await fetch(`${BASE_URL}/api/cart.php`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    new URLSearchParams({ action: 'add', product_id: productId, quantity: 1 })
        });

        const data = await res.json();

        if (data.success) {
            window.updateNavCartCount?.(data.count);
            window.showToast?.('Added to cart!', 'success');
        } else if (data.redirect) {
            window.showToast?.('Please login to add items to cart.', 'info');
            setTimeout(() => window.location.href = data.redirect, 1000);
            return;
        } else {
            window.showToast?.(data.message || 'Could not add to cart.', 'error');
        }
    } catch (e) {
        window.showToast?.('Network error. Please try again.', 'error');
    } finally {
        setTimeout(() => {
            btn.classList.remove('adding');
            btn.disabled  = false;
            btn.innerHTML = originalContent;
        }, 1200);
    }
}
