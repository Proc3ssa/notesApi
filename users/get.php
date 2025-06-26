<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, username, email, token FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo json_encode(['message' => 'No users found']);
    exit;
}

echo json_encode($users);
