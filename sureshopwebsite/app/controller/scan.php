<?php
// =========================
// CORS
// =========================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");
ini_set('display_errors', 0);
error_reporting(0);

require_once __DIR__ . '/../config/db.php';

// =========================
// SERVER SECRET (DO NOT STORE IN DB)
// =========================
const SERVER_SECRET = 'CHANGE_THIS_TO_LONG_RANDOM_SECRET';

// =========================
// 1️⃣ Authorization header
// =========================
$authHeader = null;

if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
}
elseif (function_exists('getallheaders')) {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $authHeader = $headers['authorization'];
    }
}
elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}

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

// =========================
// 2️⃣ Resolve token → user_id
// =========================
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

// =========================
// 3️⃣ Convert to anonymized ID
// =========================
$anonUserId = hash_hmac('sha256', (string)$userId, SERVER_SECRET);

// =========================
// 4️⃣ Read scan payload
// =========================
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid scan data"]);
    exit;
}

// =========================
// 5️⃣ Heuristic risk scoring
// =========================
$riskScore = 0;

if (empty($data['seller_name'])) $riskScore += 25;
if (empty($data['profile_url'])) $riskScore += 20;
if (($data['rating'] ?? 5) < 4.0) $riskScore += 20;
if (($data['response_rate'] ?? 100) < 80) $riskScore += 15;
if (($data['image_count'] ?? 0) < 3) $riskScore += 10;

$riskScore = min(100, $riskScore);

$riskLevel =
    $riskScore >= 70 ? 'High' :
    ($riskScore >= 40 ? 'Medium' : 'Low');

// =========================
// 6️⃣ Insert anonymized scan
// =========================
$productName = $data['product_name'] ?? 'Unknown Product';

$stmt = $pdo->prepare("
    INSERT INTO scans (
        anon_user_id,
        scan_type,
        product_name,
        platform,
        listing_id,
        seller_has_rating,
        price,
        review_count,
        risk_score,
        risk_level,
        scan_trigger
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'manual')
");

$stmt->execute([
    $anonUserId,
    'product',
    $productName,
    'Shopee',
    null,
    !empty($data['rating']),
    $data['price'] ?? null,
    $data['rating_count'] ?? null,
    $riskScore,
    $riskLevel
]);

// =========================
// 7️⃣ Return result
// =========================
echo json_encode([
    "risk_score" => $riskScore,
    "risk_level" => $riskLevel
]);