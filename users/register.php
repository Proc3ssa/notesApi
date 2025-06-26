<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json');

// Parse request body
$body = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (
    !isset($body['username']) ||
    !isset($body['email']) ||
    !isset($body['password'])
) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing username, email, or password']);
    exit;
}

// Extract values
$username = trim($body['username']);
$email = trim($body['email']);
$password = trim($body['password']);

// Check if user already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);

if ($stmt->fetch()) {
    http_response_code(409); // Conflict
    echo json_encode(['error' => 'User with that email already exists']);
    exit;
}

// Hash password (recommended)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Generate a simple token (you can use JWT or stronger logic later)
$token = bin2hex(random_bytes(16)); // 32-char secure token

// Insert new user
$stmt = $pdo->prepare("
    INSERT INTO users (username, email, password, token)
    VALUES (:username, :email, :password, :token)
");

$stmt->execute([
    ':username' => $username,
    ':email' => $email,
    ':password' => $hashedPassword,
    ':token' => $token
]);

http_response_code(201);
echo json_encode([
    'message' => 'User registered successfully',
    'username' => $username,
    'email' => $email,
    'token' => $token
]);
exit;
