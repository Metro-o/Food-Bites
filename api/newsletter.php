<?php
/**
 * FoodBites — Newsletter Subscribe API
 */
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json');

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = strtolower(trim($body['email'] ?? ''));
$lang  = $body['lang'] ?? 'en';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Please enter a valid email address.');
}

if (!in_array($lang, ['en', 'sw', 'ar', 'fr'], true)) {
    $lang = 'en';
}

$db = getDB();
try {
    $stmt = $db->prepare(
        'INSERT INTO newsletter_subscribers (email, lang) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE lang = VALUES(lang)'
    );
    $stmt->execute([$email, $lang]);
    jsonResponse(true, 'Subscribed!');
} catch (Exception $e) {
    error_log('[FoodBites] Newsletter error: ' . $e->getMessage());
    jsonResponse(false, 'Something went wrong. Please try again.');
}
