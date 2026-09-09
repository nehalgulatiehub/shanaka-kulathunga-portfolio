<?php
declare(strict_types=1);
require __DIR__ . '/db.php';
try {
    $category = trim((string)($_GET['category'] ?? ''));
    $sql = 'SELECT id,title,category,medium,image_path,created_at FROM artworks WHERE is_published = 1';
    $params = [];
    if ($category !== '') { $sql .= ' AND category = ?'; $params[] = $category; }
    $sql .= ' ORDER BY sort_order, id DESC';
    $statement = db()->prepare($sql); $statement->execute($params);
    json_response(['artworks' => $statement->fetchAll()]);
} catch (Throwable $e) { json_response(['message' => 'Gallery is unavailable.'], 500); }
