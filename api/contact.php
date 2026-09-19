<?php
declare(strict_types=1);
require __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(['message' => 'Method not allowed.'], 405);
$name = post('name'); $email = filter_var(post('email'), FILTER_VALIDATE_EMAIL); $message = post('message');
if (!$name || !$email || !$message) json_response(['message' => 'Please enter your name, email and message.'], 422);
try {
    $phone = post('phone');
    $subject = post('subject') ?: 'Website enquiry';
    db()->prepare('INSERT INTO enquiries (name,email,phone,subject,message) VALUES (?,?,?,?,?)')
        ->execute([$name, $email, $phone, $subject, $message]);

    $contactEmail = (string)($config['contact_email'] ?? 'info@shanakakulathunga.com');
    $safeName = preg_replace('/[\r\n]+/', ' ', $name) ?: 'Website visitor';
    $safeSubject = preg_replace('/[\r\n]+/', ' ', $subject) ?: 'Website enquiry';
    $body = "New website enquiry\n\nName: {$safeName}\nEmail: {$email}\nPhone: {$phone}\nSubject: {$safeSubject}\n\nMessage:\n{$message}";
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: Shanaka Website <website@shanakakulathunga.com>',
        "Reply-To: {$safeName} <{$email}>",
    ];
    @mail($contactEmail, "Shanaka website: {$safeSubject}", $body, implode("\r\n", $headers));

    json_response(['message' => 'Thank you — your enquiry has been received.']);
} catch (Throwable $e) { json_response(['message' => 'Unable to send your enquiry right now.'], 500); }
