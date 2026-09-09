<?php
declare(strict_types=1);
session_name('shanaka_admin'); session_start();
require_once __DIR__ . '/../api/db.php';
function require_admin(): void { if (empty($_SESSION['admin_id'])) { header('Location: /admin/login.php'); exit; } }
function escape(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
