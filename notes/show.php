<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing note ID']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
$stmt->execute([':id' => $id]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    http_response_code(404);
    echo json_encode(['error' => 'Note not found']);
    exit;
}

echo json_encode($note);
