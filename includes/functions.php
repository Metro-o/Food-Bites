<?php
/**
 * FoodBites — Global Helper Functions
 */

require_once dirname(__DIR__) . '/config/db.php';

// ─── Session ──────────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── Security Helpers ─────────────────────────────────────────────────────────

/** Sanitize output to prevent XSS */
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/** Generate a CSRF token (stored in session) */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Validate CSRF token from POST */
function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die(json_encode(['success' => false, 'message' => 'Invalid CSRF token.']));
    }
}

// ─── Format Helpers ───────────────────────────────────────────────────────────

/** Format a number as Tanzanian Shillings */
function formatPrice(float $amount): string
{
    return CURRENCY . ' ' . number_format($amount, 0, '.', ',');
}

/** Time elapsed since timestamp */
function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60)       return $diff . 's ago';
    if ($diff < 3600)     return floor($diff / 60) . 'm ago';
    if ($diff < 86400)    return floor($diff / 3600) . 'h ago';
    return date('d M', strtotime($datetime));
}

/** Time elapsed in minutes */
function elapsedMinutes(string $datetime): int
{
    return (int) floor((time() - strtotime($datetime)) / 60);
}

/** Status badge HTML */
function statusBadge(string $status): string
{
    $map = [
        'pending'   => ['label' => 'Pending',   'class' => 'badge-pending'],
        'preparing' => ['label' => 'Preparing',  'class' => 'badge-preparing'],
        'ready'     => ['label' => 'Ready',      'class' => 'badge-ready'],
        'delivered' => ['label' => 'Delivered',  'class' => 'badge-delivered'],
        'cancelled' => ['label' => 'Cancelled',  'class' => 'badge-cancelled'],
    ];
    $info = $map[$status] ?? ['label' => ucfirst($status), 'class' => ''];
    return '<span class="badge ' . $info['class'] . '">' . $info['label'] . '</span>';
}

// ─── Image Helper ─────────────────────────────────────────────────────────────

/** Return product image URL or default */
function productImage(?string $image): string
{
    if ($image && file_exists(UPLOAD_DIR . $image)) {
        return UPLOAD_URL . rawurlencode($image);
    }
    return BASE_URL . '/assets/images/default_food.jpg';
}

// ─── Auth Helpers ─────────────────────────────────────────────────────────────

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return ($_SESSION['user_role'] ?? '') === 'admin';
}

function isKitchen(): bool
{
    return in_array($_SESSION['user_role'] ?? '', ['kitchen', 'admin']);
}

function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

// ─── Cart Helpers ─────────────────────────────────────────────────────────────

/** Get cart item count for the logged-in user */
function cartCount(): int
{
    if (!isLoggedIn()) return 0;
    $db   = getDB();
    $stmt = $db->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?');
    $stmt->execute([currentUserId()]);
    return (int) $stmt->fetchColumn();
}

/** Get full cart with product details for the logged-in user */
function getCart(): array
{
    if (!isLoggedIn()) return [];
    $db   = getDB();
    $stmt = $db->prepare(
        'SELECT c.*, p.name, p.price AS unit_price, p.image, p.status
         FROM cart c
         JOIN products p ON c.product_id = p.id
         WHERE c.user_id = ?
         ORDER BY c.created_at ASC'
    );
    $stmt->execute([currentUserId()]);
    return $stmt->fetchAll();
}

/** Get cart subtotal */
function cartSubtotal(array $items): float
{
    return array_sum(array_map(fn($i) => $i['unit_price'] * $i['quantity'], $items));
}

// ─── JSON Response ────────────────────────────────────────────────────────────

function jsonResponse(bool $success, string $message, array $data = []): never
{
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

// ─── Redirect ────────────────────────────────────────────────────────────────

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

// ─── Flash Messages ──────────────────────────────────────────────────────────

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function renderFlash(): void
{
    $flash = getFlash();
    if ($flash) {
        echo '<div class="alert alert-' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
    }
}
