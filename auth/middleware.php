<?php
require_once __DIR__ . '/../db.php';

function authenticateToken() {
    $headers = apache_request_headers();
    $token = $headers['Authorization'] ?? '';

    // Remove "Bearer " prefix if it exists
    $token = str_replace('Bearer ', '', $token);

    if (empty($token)) {
        http_response_code(401);
        echo json_encode(['error' => 'Missing token']);
        exit;
    }

    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = :token");
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
        exit;
    }

    return $user; // Optionally return user info if needed
}
