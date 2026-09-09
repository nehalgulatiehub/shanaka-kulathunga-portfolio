<?php
declare(strict_types=1);
require __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['message' => 'Method not allowed.'], 405);
$email = filter_var(post('email'), FILTER_VALIDATE_EMAIL);
if (!$email) json_response(['message' => 'Please enter a valid email address.'], 422);
try {
    db()->prepare('INSERT INTO newsletter_subscribers (email) VALUES (?) ON DUPLICATE KEY UPDATE subscribed_at = CURRENT_TIMESTAMP')
        ->execute([$email]);
    json_response(['message' => 'You are now on the newsletter list.']);
} catch (Throwable $e) { json_response(['message' => 'Unable to subscribe right now.'], 500); }
