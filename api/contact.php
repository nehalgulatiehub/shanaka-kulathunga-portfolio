<?php
declare(strict_types=1);
require __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['message' => 'Method not allowed.'], 405);
$name = post('name'); $email = filter_var(post('email'), FILTER_VALIDATE_EMAIL); $message = post('message');
if (!$name || !$email || !$message) json_response(['message' => 'Please enter your name, email and message.'], 422);
try {
    db()->prepare('INSERT INTO enquiries (name,email,phone,subject,message) VALUES (?,?,?,?,?)')
        ->execute([$name, $email, post('phone'), post('subject'), $message]);
    json_response(['message' => 'Thank you — your enquiry has been received.']);
} catch (Throwable $e) { json_response(['message' => 'Unable to send your enquiry right now.'], 500); }
