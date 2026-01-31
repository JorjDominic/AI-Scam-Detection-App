<?php
// app/controller/activate_extension.php

header("Content-Type: application/json");

require_once __DIR__ . '/../config/db.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['activation_key'])) {
    http_response_code(400);
    echo json_encode(["error" => "Activation key required"]);
    exit;
}

$rawKey = trim($input['activation_key']);

if ($rawKey === '') {
    http_response_code(400);
    echo json_encode(["error" => "Invalid activation key"]);
    exit;
}

/*
  1. Find unused, non-expired activation key
*/
$stmt = $pdo->prepare("
    SELECT id, user_id, key_hash, expires_at, used
    FROM activation_keys
    WHERE used = 0
      AND expires_at > NOW()
");
$stmt->execute();

$activationRow = null;

while ($row = $stmt->fetch()) {
    if (password_verify($rawKey, $row['key_hash'])) {
        $activationRow = $row;
        break;
    }
}

if (!$activationRow) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or expired activation key"]);
    exit;
}

/*
  2. Mark activation key as used
*/
$pdo->prepare("
    UPDATE activation_keys
    SET used = 1
    WHERE id = ?
")->execute([$activationRow['id']]);

/*
  3. Generate access token (long-lived, revocable)
*/
$plainToken = bin2hex(random_bytes(32));
$tokenHash  = password_hash($plainToken, PASSWORD_DEFAULT);

$pdo->prepare("
    INSERT INTO access_tokens (user_id, token_hash)
    VALUES (?, ?)
")->execute([
    $activationRow['user_id'],
    $tokenHash
]);

/*
  4. Return token ONCE to extension
*/
echo json_encode([
    "access_token" => $plainToken
]);
