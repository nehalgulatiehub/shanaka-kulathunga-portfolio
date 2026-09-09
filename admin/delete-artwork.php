<?php
require __DIR__ . '/auth.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { db()->prepare('DELETE FROM artworks WHERE id = ?')->execute([(int)($_POST['id'] ?? 0)]); }
header('Location: /admin/');
