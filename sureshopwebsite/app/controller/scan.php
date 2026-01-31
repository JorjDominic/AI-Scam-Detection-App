<?php
// CORS headers - add these at the very beginning
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept");
header("Access-Control-Max-Age: 3600");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config/db.php';

/* =========================
   1️⃣ Get Authorization header - FIXED
   ========================= */

// Try multiple ways to get the Authorization header
$authHeader = null;

// Method 1: Standard HTTP_AUTHORIZATION
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
}
// Method 2: getallheaders() function
elseif (function_exists('getallheaders')) {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $authHeader = $headers['authorization'];
    }
}
// Method 3: Check for Authorization in $_SERVER with different formats
elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}

// Debug logging
error_log("Authorization header: " . ($authHeader ? $authHeader : 'NOT FOUND'));
error_log("All headers: " . print_r(getallheaders(), true));

if (!$authHeader) {
    http_response_code(401);
    echo json_encode(["error" => "Missing Authorization header"]);
    exit;
}

if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token format"]);
    exit;
}

$rawToken = $matches[1];

/* =========================
   2️⃣ Resolve token → user_id
   ========================= */

$stmt = $pdo->prepare("
    SELECT user_id, token_hash
    FROM access_tokens
    WHERE revoked = 0
");
$stmt->execute();

$userId = null;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (password_verify($rawToken, $row['token_hash'])) {
        $userId = $row['user_id'];
        break;
    }
}

if (!$userId) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or revoked token"]);
    exit;
}

/* =========================
   3️⃣ Read scan payload
   ========================= */

$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid scan data"]);
    exit;
}

/* =========================
   4️⃣ Heuristic risk scoring (v1)
   ========================= */

$riskScore = 0;

// Seller signals
if (empty($data['seller']['name']['value'])) $riskScore += 25;
if (empty($data['seller']['profile_url'])) $riskScore += 20;
if (($data['seller']['rating']['value'] ?? 5) < 4.0) $riskScore += 20;
if (($data['seller']['response_rate']['value'] ?? 100) < 80) $riskScore += 15;

// Listing signals
if (($data['product']['image_count'] ?? 0) < 3) $riskScore += 10;

// Clamp
$riskScore = min(100, $riskScore);

// Risk level
$riskLevel =
    $riskScore >= 70 ? 'High' :
    ($riskScore >= 40 ? 'Medium' : 'Low');

/* =========================
   5️⃣ Insert scan record
   ========================= */

$stmt = $pdo->prepare("
    INSERT INTO scans (
        user_id,
        platform,
        listing_id,
        seller_account_age_days,
        seller_has_rating,
        price,
        review_count,
        risk_score,
        risk_level,
        scan_trigger
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'manual')
");

$stmt->execute([
    $userId,
    'Shopee',
    null,
    null,
    !empty($data['seller']['rating']['value']),
    $data['product']['price']['value'] ?? null,
    $data['seller']['rating_count']['value'] ?? null,
    $riskScore,
    $riskLevel
]);

/* =========================
   6️⃣ Return result
   ========================= */

echo json_encode([
    "risk_score" => $riskScore,
    "risk_level" => $riskLevel
]);
