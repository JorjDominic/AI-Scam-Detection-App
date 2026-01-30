<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$seller_id = $_GET['id'] ?? 1;

// Mock seller data
$seller = [
    'name' => 'unknown_seller_2024',
    'risk_score' => 85,
    'risk_level' => 'High',
    'account_age' => '3 months',
    'flagged_products' => 23,
    'total_products' => 156,
    'historical_risk' => 'Increasing',
    'behavior_patterns' => [
        'Frequent store name changes (5 times in 3 months)',
        'Copied product listings from legitimate sellers',
        'Multiple payment processing failures',
        'High dispute rate with buyers (12%)',
        'Rapid inventory turnover',
        'Uses multiple seller accounts for same products'
    ],
    'account_status' => 'Active',
    'trust_score' => 15,
    'recommendation' => 'Avoid purchasing from this seller. Exercise extreme caution.'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Analysis - ScamGuard</title>
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

        <h1>Seller Analysis</h1>

        <div class="detail-header">
            <div>
                <h2><?php echo htmlspecialchars($seller['name']); ?></h2>
                <p class="meta">Account Age: <?php echo htmlspecialchars($seller['account_age']); ?> | Status: <?php echo htmlspecialchars($seller['account_status']); ?></p>
            </div>
            <div class="risk-indicator">
                <span class="risk-score risk-<?php echo strtolower($seller['risk_level']); ?>">
                    <?php echo $seller['risk_score']; ?>
                </span>
                <p><?php echo htmlspecialchars($seller['risk_level']); ?> Risk</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h4>Account Statistics</h4>
                <p><strong>Flagged Products:</strong> <?php echo $seller['flagged_products']; ?> / <?php echo $seller['total_products']; ?></p>
                <p><strong>Flagged Rate:</strong> <?php echo round(($seller['flagged_products'] / $seller['total_products']) * 100, 1); ?>%</p>
                <p><strong>Trust Score:</strong> <span class="trust-score"><?php echo $seller['trust_score']; ?>/100</span></p>
            </div>

            <div class="info-card">
                <h4>Risk Metrics</h4>
                <p><strong>Historical Risk Trend:</strong> <?php echo htmlspecialchars($seller['historical_risk']); ?></p>
                <p><strong>Account Created:</strong> <?php echo htmlspecialchars($seller['account_age']); ?> ago</p>
            </div>
        </div>

        <section class="detail-section">
            <h3>Behavior Patterns</h3>
            <ul class="indicator-list">
                <?php foreach ($seller['behavior_patterns'] as $pattern): ?>
                <li class="indicator-item">
                    <span class="indicator-icon">🚨</span>
                    <?php echo htmlspecialchars($pattern); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="detail-section">
            <h3>Recommendation</h3>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($seller['recommendation']); ?>
            </div>
        </section>

        <section class="detail-section">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>
