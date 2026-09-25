<?php
/**
 * FoodBites — Kitchen Display System (KDS)
 * Dark-mode real-time dashboard for kitchen staff.
 */
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth_guard.php';
requireRole(['admin','kitchen'], BASE_URL . '/admin/login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display System — FoodBites</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/kds.css">
</head>
<body class="kds-body">

<!-- ── KDS Header ─────────────────────────────────────────── -->
<header class="kds-header">
    <div class="kds-logo"><i class="fa-solid fa-utensils"></i> FoodBites <span>KITCHEN</span></div>
    <div class="kds-clock" id="kdsClock"></div>
    <div class="kds-controls">
        <div class="kds-status-dot" id="statusDot" title="Live"></div>
        <span id="orderCount" class="kds-order-count">— orders</span>
        <button class="kds-btn" id="soundToggle" onclick="toggleSound()" title="Toggle sound alerts"><i class="fa-solid fa-bell" id="soundIcon"></i></button>
        <button class="kds-btn" onclick="loadOrders()" title="Refresh now"><i class="fa-solid fa-rotate"></i></button>
        <?php if (isAdmin()): ?>
        <a href="<?= BASE_URL ?>/admin/index.php" class="kds-btn"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/admin/logout.php" class="kds-btn kds-btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
</header>

<!-- ── Status Legend ──────────────────────────────────────── -->
<div class="kds-legend">
    <span class="kds-legend-item pending"><i class="fa-solid fa-circle"></i> Pending</span>
    <span class="kds-legend-item preparing"><i class="fa-solid fa-circle"></i> Preparing</span>
    <span class="kds-legend-item ready"><i class="fa-solid fa-circle"></i> Ready</span>
    <span class="kds-legend-item delayed"><i class="fa-solid fa-circle"></i> Delayed (20+ min)</span>
</div>

<!-- ── Orders Grid ────────────────────────────────────────── -->
<main class="kds-grid" id="kdsGrid">
    <div class="kds-loading">
        <div class="kds-spinner"></div>
        <p>Loading orders…</p>
    </div>
</main>

<!-- ── Empty State ────────────────────────────────────────── -->
<div class="kds-empty" id="kdsEmpty" style="display:none">
    <div class="kds-empty-icon"><i class="fa-solid fa-circle-check"></i></div>
    <h2>All caught up!</h2>
    <p>No active orders right now. Waiting for new ones…</p>
</div>

<!-- Sound alert (new order) — uses Web Audio API as fallback if file missing -->
<audio id="alertSound" preload="auto">
    <source src="<?= BASE_URL ?>/assets/sounds/alert.wav" type="audio/wav">
</audio>

<script>
const BASE         = '<?= BASE_URL ?>';
const POLL_MS      = <?= KDS_POLL_INTERVAL * 1000 ?>;
let soundEnabled   = true;
let knownOrderIds  = new Set();
let pollTimer      = null;

// ── Clock ──────────────────────────────────────────────────
function updateClock() {
    document.getElementById('kdsClock').textContent =
        new Date().toLocaleTimeString('sw-TZ', { hour:'2-digit', minute:'2-digit', second:'2-digit', hour12: false });
}
setInterval(updateClock, 1000);
updateClock();

// ── Sound Toggle ───────────────────────────────────────────
function toggleSound() {
    soundEnabled = !soundEnabled;
    const btn  = document.getElementById('soundToggle');
    const icon = document.getElementById('soundIcon');
    icon.className = soundEnabled ? 'fa-solid fa-bell' : 'fa-solid fa-bell-slash';
    btn.title = soundEnabled ? 'Sound: ON' : 'Sound: OFF';
}

function playAlert() {
    if (!soundEnabled) return;
    const audio = document.getElementById('alertSound');
    if (audio.src) {
        audio.currentTime = 0;
        audio.play().catch(() => {
            // Fallback: Web Audio API beep
            try {
                const ctx = new AudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.type = 'sine'; osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.4, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
                osc.start(); osc.stop(ctx.currentTime + 0.8);
            } catch(e) {}
        });
    }
}

// ── Load Orders ────────────────────────────────────────────
async function loadOrders() {
    try {
        const res  = await fetch(`${BASE}/kds/api.php?action=orders`, { cache: 'no-store' });
        const data = await res.json();

        document.getElementById('statusDot').classList.toggle('offline', !data.success);

        if (!data.success) return;

        const orders  = data.orders;
        document.getElementById('orderCount').textContent = `${orders.length} active order${orders.length !== 1 ? 's' : ''}`;

        // Detect new orders
        let hasNew = false;
        orders.forEach(o => {
            if (!knownOrderIds.has(o.id) && o.status === 'pending') {
                knownOrderIds.add(o.id);
                hasNew = true;
            }
        });
        if (hasNew) playAlert();

        // Update known IDs set
        const currentIds = new Set(orders.map(o => o.id));
        knownOrderIds = new Set([...knownOrderIds].filter(id => currentIds.has(id)));

        renderOrders(orders);
    } catch(e) {
        document.getElementById('statusDot').classList.add('offline');
    }
}

// ── Render ─────────────────────────────────────────────────
function renderOrders(orders) {
    const grid  = document.getElementById('kdsGrid');
    const empty = document.getElementById('kdsEmpty');

    if (!orders.length) {
        grid.innerHTML  = '';
        empty.style.display = 'flex';
        return;
    }
    empty.style.display = 'none';

    // DOM diffing — only update changed cards
    const existingCards = new Map();
    grid.querySelectorAll('.kds-card').forEach(c => existingCards.set(Number(c.dataset.orderId), c));

    // Remove cards for orders no longer in list
    existingCards.forEach((card, id) => {
        if (!orders.find(o => o.id === id)) card.remove();
    });

    // Insert/update cards
    orders.forEach((order, idx) => {
        const card = existingCards.get(order.id) || null;
        const html = buildCard(order);
        if (!card) {
            const el = document.createElement('div');
            el.innerHTML = html;
            grid.appendChild(el.firstElementChild);
            // Animate in
            setTimeout(() => grid.querySelector(`[data-order-id="${order.id}"]`)?.classList.add('visible'), 50);
        } else {
            // Update just the timer and status if changed
            const timerEl  = card.querySelector('.kds-timer');
            const statusEl = card.querySelector('.kds-status');
            const actEl    = card.querySelector('.kds-actions');
            if (timerEl)  timerEl.textContent  = formatElapsed(order.elapsed_mins);
            if (statusEl) statusEl.className = `kds-status kds-status-${order.status}`;
            if (statusEl) statusEl.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);
            card.classList.toggle('delayed', order.is_delayed);
            card.classList.toggle('kds-card-new', knownOrderIds.has(order.id) && order.status === 'pending');
        }
    });
}

function buildCard(order) {
    const isNew     = order.status === 'pending';
    const delayed   = order.is_delayed;
    const elapsed   = formatElapsed(order.elapsed_mins);
    const delivTime = order.delivery_time
        ? new Date(order.delivery_time).toLocaleTimeString('en-TZ', {hour:'2-digit', minute:'2-digit'})
        : 'ASAP';

    const itemsHtml = order.items.map(item =>
        `<div class="kds-item">
            <span class="kds-item-qty">×${item.quantity}</span>
            <span class="kds-item-name">${escHtml(item.product_name)}</span>
         </div>`
    ).join('');

    const actionBtn = order.status === 'pending'
        ? `<button class="kds-action-btn kds-btn-start" onclick="updateStatus(${order.id}, 'preparing', this)"><i class="fa-solid fa-play"></i> Start Preparing</button>`
        : order.status === 'preparing'
        ? `<button class="kds-action-btn kds-btn-done" onclick="updateStatus(${order.id}, 'ready', this)"><i class="fa-solid fa-check"></i> Mark Ready</button>`
        : '';

    return `
    <div class="kds-card kds-card-${order.status} ${delayed ? 'delayed' : ''} ${isNew ? 'kds-card-new visible' : 'visible'}"
         data-order-id="${order.id}">
        <div class="kds-card-header">
            <div class="kds-order-id">#${order.id}</div>
            <div class="kds-status kds-status-${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</div>
        </div>

        <div class="kds-customer">${escHtml(order.customer_name)}</div>

        <div class="kds-items">${itemsHtml}</div>

        <div class="kds-meta">
            <div class="kds-meta-row">
                <span class="kds-meta-label"><i class="fa-solid fa-location-dot"></i></span>
                <span>${escHtml(order.delivery_location)}</span>
            </div>
            <div class="kds-meta-row">
                <span class="kds-meta-label"><i class="fa-solid fa-clock"></i></span>
                <span>Deliver by <strong>${delivTime}</strong></span>
            </div>
            ${order.notes ? `<div class="kds-meta-row kds-notes"><i class="fa-solid fa-note-sticky"></i> ${escHtml(order.notes)}</div>` : ''}
        </div>

        <div class="kds-card-footer">
            <div class="kds-timer ${delayed ? 'timer-delayed' : ''}" title="Time since order placed">
                <i class="fa-solid fa-hourglass-half"></i> ${elapsed}
            </div>
            <div class="kds-actions">${actionBtn}</div>
        </div>
    </div>`;
}

// ── Status Update ──────────────────────────────────────────
async function updateStatus(orderId, newStatus, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating…';

    const res  = await fetch(`${BASE}/kds/api.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    new URLSearchParams({ order_id: orderId, status: newStatus })
    });
    const data = await res.json();

    if (data.success) {
        loadOrders();
    } else {
        btn.disabled   = false;
        btn.innerHTML  = '<i class="fa-solid fa-rotate"></i> Retry';
        alert(data.message || 'Update failed');
    }
}

// ── Helpers ────────────────────────────────────────────────
function formatElapsed(mins) {
    if (mins < 1)  return '< 1 min';
    if (mins < 60) return `${mins} min`;
    return `${Math.floor(mins/60)}h ${mins%60}m`;
}

function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = String(str);
    return d.innerHTML;
}

// ── Start Polling ──────────────────────────────────────────
loadOrders();
pollTimer = setInterval(loadOrders, POLL_MS);

// Update elapsed timers every second (lightweight)
setInterval(() => {
    document.querySelectorAll('.kds-timer').forEach(el => {
        const card     = el.closest('.kds-card');
        const orderId  = Number(card?.dataset.orderId);
        // Just increment display by 1 min for smoothness (real data refreshes on poll)
    });
}, 1000);
</script>
</body>
</html>
