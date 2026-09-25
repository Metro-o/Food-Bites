<?php
/**
 * FoodBites — Auth Guard
 * Include at the top of any protected page.
 * Usage:
 *   require_once '../includes/auth_guard.php';
 *   requireRole('customer');          // redirect if not logged in as customer
 *   requireRole('admin');             // redirect if not admin
 *   requireRole(['admin','kitchen']); // allow either role
 */

require_once __DIR__ . '/functions.php';

/**
 * Redirect to login if user doesn't have required role.
 *
 * @param string|array $roles Required role(s)
 * @param string       $loginUrl  Redirect destination
 */
function requireRole(string|array $roles, string $loginUrl = null): void
{
    $roles = (array) $roles;

    if (!isLoggedIn()) {
        // Save intended URL and redirect to appropriate login page
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        $url = $loginUrl ?? (in_array('admin', $roles) || in_array('kitchen', $roles)
            ? BASE_URL . '/admin/login.php'
            : BASE_URL . '/auth/login.php');
        redirect($url);
    }

    if (!in_array($_SESSION['user_role'] ?? '', $roles)) {
        // Logged in but wrong role
        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'kitchen') {
            redirect(BASE_URL . '/admin/index.php');
        }
        redirect(BASE_URL . '/index.php');
    }
}

/**
 * Redirect logged-in users away from guest-only pages (login, register).
 */
function guestOnly(): void
{
    if (isLoggedIn()) {
        if (isAdmin() || isKitchen()) {
            redirect(BASE_URL . '/admin/index.php');
        }
        redirect(BASE_URL . '/index.php');
    }
}
