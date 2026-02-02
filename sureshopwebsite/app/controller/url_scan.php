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
    error_log("URL Scan ERROR: Missing Authorization header");
    http_response_code(401);
    echo json_encode(["error" => "Missing Authorization header"]);
    exit;
}

if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    error_log("URL Scan ERROR: Invalid token format");
    http_response_code(401);
    echo json_encode(["error" => "Invalid token format"]);
    exit;
}

$rawToken = $matches[1];

// =========================
// 2️⃣ Resolve token → user_id
// =========================
try {
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
        error_log("URL Scan ERROR: Invalid or revoked token");
        http_response_code(401);
        echo json_encode(["error" => "Invalid or revoked token"]);
        exit;
    }

    error_log("URL Scan: Token verified successfully for user ID: {$userId}");
} catch (Exception $e) {
    error_log("URL Scan ERROR: Database error during token verification: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Internal server error"]);
    exit;
}

// =========================
// 3️⃣ Convert to anonymized ID
// =========================
$anonUserId = hash_hmac('sha256', (string)$userId, SERVER_SECRET);

// =========================
// 4️⃣ Read URL payload
// =========================
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if (!is_array($data) || empty($data['url']) || empty($data['domain'])) {
    error_log("URL Scan ERROR: Invalid URL data - received: " . $rawInput);
    http_response_code(400);
    echo json_encode(["error" => "Invalid URL data - missing url or domain"]);
    exit;
}

$url = $data['url'];
$domain = $data['domain'];

error_log("URL Scan: Processing URL: {$url}, Domain: {$domain}");

// =========================
// 5️⃣ URL Risk Assessment Heuristics
// =========================
$riskScore = 0;

// Domain-based checks
$suspiciousDomains = [
    'bit.ly', 'tinyurl.com', 'goo.gl', 't.co', 'ow.ly', 'is.gd', 'buff.ly',
    'adf.ly', 'short.link', 'tiny.cc', 'rebrand.ly', 'cutt.ly'
];

$highRiskTlds = ['.tk', '.ml', '.ga', '.cf', '.top', '.club', '.download', '.date', '.win'];

$phishingKeywords = [
    'verify', 'suspended', 'confirm', 'update', 'secure', 'urgent', 'expires',
    'click-here', 'act-now', 'limited-time', 'free-money', 'winner', 'prize'
];

// Check for URL shorteners
foreach ($suspiciousDomains as $suspicious) {
    if (strpos($domain, $suspicious) !== false) {
        $riskScore += 30;
        error_log("URL Scan: Suspicious domain detected ({$suspicious}), risk +30");
        break;
    }
}

// Check for suspicious TLDs
foreach ($highRiskTlds as $tld) {
    if (str_ends_with($domain, $tld)) {
        $riskScore += 25;
        error_log("URL Scan: High-risk TLD detected ({$tld}), risk +25");
        break;
    }
}

// Check for phishing keywords in URL
$urlLower = strtolower($url);
foreach ($phishingKeywords as $keyword) {
    if (strpos($urlLower, $keyword) !== false) {
        $riskScore += 15;
        error_log("URL Scan: Phishing keyword detected ({$keyword}), risk +15");
    }
}

// Check URL structure indicators
if (substr_count($url, '-') > 4) {
    $riskScore += 10;
    error_log("URL Scan: Too many dashes, risk +10");
}

if (substr_count($url, '.') > 4) {
    $riskScore += 15;
    error_log("URL Scan: Too many subdomains, risk +15");
}

if (strlen($domain) > 50) {
    $riskScore += 10;
    error_log("URL Scan: Very long domain, risk +10");
}

if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $domain)) {
    $riskScore += 20;
    error_log("URL Scan: IP address instead of domain, risk +20");
}

// Check for suspicious patterns
if (preg_match('/[0-9]{4,}/', $domain)) {
    $riskScore += 10;
    error_log("URL Scan: Long numbers in domain, risk +10");
}

if (preg_match('/[a-z]{20,}/', $domain)) {
    $riskScore += 15;
    error_log("URL Scan: Very long strings in domain, risk +15");
}

// HTTPS check (bonus for security)
if (strpos($url, 'https://') === 0) {
    $riskScore = max(0, $riskScore - 5);
    error_log("URL Scan: HTTPS detected, risk -5");
} else {
    $riskScore += 15;
    error_log("URL Scan: HTTP (not HTTPS), risk +15");
}

// Check for legitimate domains (whitelist)
$trustedDomains = [
    'google.com', 'facebook.com', 'amazon.com', 'microsoft.com', 'apple.com',
    'twitter.com', 'instagram.com', 'linkedin.com', 'github.com', 'stackoverflow.com',
    'wikipedia.org', 'youtube.com', 'shopee.ph', 'lazada.com.ph', 'gcash.com',
    'nature.com', 'bbc.com', 'cnn.com', 'reddit.com', 'gmail.com'
];

foreach ($trustedDomains as $trusted) {
    if (str_ends_with($domain, $trusted)) {
        $riskScore = max(0, $riskScore - 30);
        error_log("URL Scan: Trusted domain detected ({$trusted}), risk -30");
        break;
    }
}

// Cap the risk score
$riskScore = min(100, max(0, $riskScore));

// Determine risk level
$riskLevel = 
    $riskScore >= 60 ? 'High' :
    ($riskScore >= 30 ? 'Medium' : 'Low');

error_log("URL Scan: Final risk assessment - Score: {$riskScore}, Level: {$riskLevel}");

// =========================
// 6️⃣ Insert anonymized URL scan - FIXED
// =========================
try {
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
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'auto')
    ");

    $result = $stmt->execute([
        $anonUserId,
        'url',           // scan type
        $domain,         // use domain in product_name column
        'URL',          // platform for URL scans
        null,            // no listing ID
        null,            // no seller rating
        null,            // no price
        null,            // no review count
        $riskScore,
        $riskLevel
    ]);

    if ($result && $stmt->rowCount() > 0) {
        error_log("URL Scan: Database insert SUCCESS - Domain: {$domain}, Risk: {$riskLevel}");
    } else {
        error_log("URL Scan: Database insert FAILED - No rows affected");
        error_log("URL Scan: PDO Error Info: " . print_r($stmt->errorInfo(), true));
    }

} catch (Exception $e) {
    error_log("URL Scan: Database insert EXCEPTION: " . $e->getMessage());
    error_log("URL Scan: Stack trace: " . $e->getTraceAsString());
    
    // Don't fail the scan if database insert fails - still return results
    error_log("URL Scan: Continuing despite database error...");
}

// =========================
// 7️⃣ Log scan completion
// =========================
error_log("URL Scan COMPLETED: {$domain} -> Risk: {$riskLevel} ({$riskScore}/100)");

// =========================
// 8️⃣ Return result - ENHANCED
// =========================
$response = [
    "risk_score" => $riskScore,
    "risk_level" => $riskLevel,
    "domain" => $domain,
    "scan_type" => "url",
    "timestamp" => time(),
    "success" => true
];

error_log("URL Scan: Sending response: " . json_encode($response));

echo json_encode($response);
?>