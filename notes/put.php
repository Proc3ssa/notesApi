<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

// Get the note ID from the router
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing note ID']);
    exit;
}

// Get request body
$body = json_decode(file_get_contents('php://input'), true);

// Validate fields
if (!isset($body['title'], $body['content'], $body['isArchived'], $body['createdAt'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Optional: support tag if provided
$tag = $body['tag'] ?? null;

$stmt = $pdo->prepare("
    UPDATE notes 
    SET title = :title, content = :content, isArchived = :isArchived, tag = :tag, createdAt = :createdAt 
    WHERE id = :id
");

$stmt->execute([
    ':title' => $body['title'],
    ':content' => $body['content'],
    ':isArchived' => $body['isArchived'],
    ':tag' => $tag,
    ':createdAt' => $body['createdAt'],
    ':id' => $id
]);

echo json_encode(['message' => 'Note updated']);
