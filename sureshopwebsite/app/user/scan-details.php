<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$scan_id = $_GET['id'] ?? 1;

// Mock data based on scan ID
$scan_details = [
    'type' => 'Website',
    'target' => 'suspicious-store.com',
    'risk_score' => 87,
    'risk_level' => 'High',
    'date_scanned' => '2024-01-15 14:32:00',
    'indicators' => [
        'Newly registered domain (5 days old)',
        'No HTTPS certificate',
        'Fake customer reviews detected',
        'Copied product images from legitimate retailers',
        'Suspicious payment methods only (wire transfer)',
        'Multiple spelling errors in product descriptions'
    ],
    'explanation' => 'This website exhibits multiple characteristics commonly associated with scams. The domain is very new, lacks proper security certificates, and shows signs of content plagiarism. The payment methods are atypical for legitimate e-commerce.',
    'recommendation' => 'Avoid this website. Do not make any purchases or provide personal information.'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Details - ScamGuard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">ScamGuard</div>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container detail-container">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <h1>Scan Details</h1>

        <div class="detail-header">
            <div>
                <h2><?php echo htmlspecialchars($scan_details['target']); ?></h2>
                <p class="meta">Type: <?php echo htmlspecialchars($scan_details['type']); ?> | Scanned: <?php echo htmlspecialchars($scan_details['date_scanned']); ?></p>
            </div>
            <div class="risk-indicator">
                <span class="risk-score risk-<?php echo strtolower($scan_details['risk_level']); ?>">
                    <?php echo $scan_details['risk_score']; ?>
                </span>
                <p><?php echo htmlspecialchars($scan_details['risk_level']); ?> Risk</p>
            </div>
        </div>

        <section class="detail-section">
            <h3>Analysis Explanation</h3>
            <p><?php echo htmlspecialchars($scan_details['explanation']); ?></p>
        </section>

        <section class="detail-section">
            <h3>Detected Indicators</h3>
            <ul class="indicator-list">
                <?php foreach ($scan_details['indicators'] as $indicator): ?>
                <li class="indicator-item">
                    <span class="indicator-icon">⚠️</span>
                    <?php echo htmlspecialchars($indicator); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="detail-section">
            <h3>Recommendation</h3>
            <div class="alert alert-warning">
                <?php echo htmlspecialchars($scan_details['recommendation']); ?>
            </div>
        </section>

        <section class="detail-section">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>
