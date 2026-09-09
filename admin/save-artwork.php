<?php
require __DIR__ . '/auth.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /admin/'); exit; }
$title = trim($_POST['title'] ?? ''); $category = $_POST['category'] ?? ''; $medium = trim($_POST['medium'] ?? '');
$allowed = ['oil','charcoal','commission','conceptual'];
if (!$title || !in_array($category, $allowed, true) || empty($_FILES['image']['tmp_name'])) { http_response_code(422); exit('Please complete the artwork form.'); }
$mime = mime_content_type($_FILES['image']['tmp_name']); $extensions = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
if (!isset($extensions[$mime]) || $_FILES['image']['size'] > 8_000_000) { http_response_code(422); exit('Use a JPG, PNG or WebP image under 8 MB.'); }
$folder = dirname(__DIR__) . '/public/uploads'; if (!is_dir($folder)) mkdir($folder, 0755, true);
$name = bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
if (!move_uploaded_file($_FILES['image']['tmp_name'], "$folder/$name")) { http_response_code(500); exit('Image upload failed.'); }
db()->prepare('INSERT INTO artworks (title,category,medium,image_path,sort_order,is_published) VALUES (?,?,?,?,?,?)')->execute([$title,$category,$medium,'/uploads/'.$name,(int)($_POST['sort_order'] ?? 0),isset($_POST['is_published']) ? 1 : 0]);
header('Location: /admin/');
