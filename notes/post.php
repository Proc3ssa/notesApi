<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

// Get note ID from route
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing note ID']);
    exit;
}

// Parse JSON body
$body = json_decode(file_get_contents('php://input'), true);

// Validate input
if (
    !isset($body['title']) ||
    !isset($body['content']) ||
    !isset($body['isArchived']) ||
    !isset($body['createdAt'])
) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Optional tag
$tag = $body['tag'] ?? null;

// Prepare update query
$stmt = $pdo->prepare("
    UPDATE notes
    SET title = :title,
        content = :content,
        isArchived = :isArchived,
        tag = :tag,
        createdAt = :createdAt
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

// Check if update affected any rows
if ($stmt->rowCount() === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Note not found or no changes made']);
    exit;
}

echo json_encode(['message' => 'Note updated successfully']);
