<?php
/**
 * FoodBites — Database Configuration & App Constants
 * XAMPP Setup: MySQL running on localhost, default root user
 * ─────────────────────────────────────────────────────────────
 * NOTE: To use this project with XAMPP, either:
 *    (a) Copy/symlink d:\Projects\FoodBites → C:\xampp\htdocs\FoodBites
 *    (b) Or add a VirtualHost in XAMPP's httpd-vhosts.conf pointing
 *        DocumentRoot to d:/Projects/FoodBites
 */

// ─── Application Settings ────────────────────────────────────────────────────
define('APP_NAME',    'FoodBites');
define('APP_TAGLINE', 'Taste of Tanzania, Delivered to You');
define('BASE_URL',    'http://localhost/FoodBites');     // ← adjust if using a vhost

// ─── Database Credentials ────────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'foodbites');
define('DB_USER',    'root');
define('DB_PASS',    '');              // Default XAMPP password is empty
define('DB_CHARSET', 'utf8mb4');

// ─── File Uploads ────────────────────────────────────────────────────────────
define('UPLOAD_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR);
define('UPLOAD_URL', BASE_URL . '/uploads/products/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);   // 5 MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// ─── Business Rules ──────────────────────────────────────────────────────────
define('CURRENCY',      'TZS');
define('DELIVERY_FEE',  2000);     // TZS
define('MIN_BULK_QTY',  10);       // Minimum persons for bulk order

// ─── KDS Refresh Interval (seconds) ──────────────────────────────────────────
define('KDS_POLL_INTERVAL', 4);

/**
 * Returns a singleton PDO database connection.
 * Uses prepared statements (ATTR_EMULATE_PREPARES = false) to prevent SQL injection.
 */
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST, DB_NAME, DB_CHARSET
            );
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log('[FoodBites] DB Error: ' . $e->getMessage());

            // Return JSON error for AJAX requests
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                http_response_code(500);
                die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
            }

            die('
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
                <style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:#0f1117;color:#fff;}
                .box{text-align:center;padding:2rem;border:1px solid #333;border-radius:1rem;}</style>
                <div class="box">
                    <h2><i class="fa-solid fa-triangle-exclamation"></i> Database Offline</h2>
                    <p>Make sure XAMPP MySQL is running, then <a href="javascript:location.reload()" style="color:#16A34A">reload</a>.</p>
                </div>
            ');
        }
    }

    return $pdo;
}
