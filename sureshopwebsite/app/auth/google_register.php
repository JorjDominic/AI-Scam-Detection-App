<?php
require_once __DIR__ . '/../config/db.php';
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['credential'])) {
    echo json_encode(['success' => false, 'error' => 'No credential']);
    exit;
}

$token = $data['credential'];

$response = file_get_contents(
    "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($token)
);
$payload = json_decode($response, true);

if (!isset($payload['email'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid token']);
    exit;
}

$email = strtolower($payload['email']);

/* 1. Check if user already exists */
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    /* 2. Insert Google user */
    $stmt = $pdo->prepare("
        INSERT INTO users (email, auth_provider, role_id)
        VALUES (?, 'google', 2)
    ");
    $stmt->execute([$email]);
    $userId = $pdo->lastInsertId();
} else {
    $userId = $user['id'];
}

/* 3. Create session */
$_SESSION['user_id'] = $userId;
$_SESSION['email'] = $email;
$_SESSION['user_role'] = 'user';
$_SESSION['user_id'] = $userId;
$_SESSION['email'] = $email;

/* redirect to complete profile */
echo json_encode([
    'success' => true,
    'redirect' => 'complete_profile.php'
]);


