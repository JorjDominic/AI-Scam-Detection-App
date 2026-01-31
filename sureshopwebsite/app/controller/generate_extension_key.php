<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Generate random key
$rawKey = 'SURESHOP-' . strtoupper(bin2hex(random_bytes(4)));
$keyHash = password_hash($rawKey, PASSWORD_DEFAULT);

// Store key
$stmt = $pdo->prepare("
    INSERT INTO activation_keys (user_id, key_hash, expires_at)
    VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))
");
$stmt->execute([$userId, $keyHash]);

// Temporarily store plain key for display (session only)
$_SESSION['extension_key'] = $rawKey;

header('Location: dashboard.php');
exit;
