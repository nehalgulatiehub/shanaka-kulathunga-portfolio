<?php
require __DIR__ . '/auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $statement = db()->prepare('SELECT id, password_hash FROM admins WHERE email = ? LIMIT 1');
  $statement->execute([trim($_POST['email'] ?? '')]); $admin = $statement->fetch();
  if ($admin && password_verify($_POST['password'] ?? '', $admin['password_hash'])) { $_SESSION['admin_id'] = $admin['id']; header('Location: /admin/'); exit; }
  $error = 'Incorrect email or password.';
}
?><!doctype html><html><head><meta charset="utf-8"><title>Admin login</title><link rel="stylesheet" href="/admin/admin.css"></head><body><main class="login"><h1>Shanaka Admin</h1><?php if ($error): ?><p class="error"><?= escape($error) ?></p><?php endif; ?><form method="post"><label>Email<input type="email" name="email" required></label><label>Password<input type="password" name="password" required></label><button>Sign in</button></form></main></body></html>
