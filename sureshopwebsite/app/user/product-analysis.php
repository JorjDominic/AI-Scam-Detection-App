<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$product_id = $_GET['id'] ?? 1;

// Mock product data
$product = [
    'name' => 'Fake Designer Watch',
    'source_website' => 'suspicious-store.com',
    'risk_score' => 82,
    'risk_level' => 'High',
    'listed_price' => '$29.99',
    'market_price' => '$1,200.00',
    'price_anomaly' => 'Extremely underpriced (97.5% below legitimate market value)',
    'review_count' => 1247,
    'average_rating' => 4.8,
    'review_patterns' => [
        'All 5-star reviews (no 1-4 star reviews)',
        'Reviews all posted within 2-week period',
        'Generic review text repeated multiple times',
        'Reviewers have no purchase history'
    ],
    'seller_risk' => 'High',
    'seller_name' => 'unknown_seller_2024',
    'analysis' => 'This product shows classic signs of a counterfeit item. The price is drastically below market value, reviews appear fake with suspicious patterns, and the seller has a history of fraudulent listings.'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Analysis - ScamGuard</title>
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

        <h1>Product Analysis</h1>

        <div class="detail-header">
            <div>
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="meta">Source: <?php echo htmlspecialchars($product['source_website']); ?></p>
            </div>
            <div class="risk-indicator">
                <span class="risk-score risk-<?php echo strtolower($product['risk_level']); ?>">
                    <?php echo $product['risk_score']; ?>
                </span>
                <p><?php echo htmlspecialchars($product['risk_level']); ?> Risk</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h4>Pricing Analysis</h4>
                <p><strong>Listed Price:</strong> <?php echo htmlspecialchars($product['listed_price']); ?></p>
                <p><strong>Market Price:</strong> <?php echo htmlspecialchars($product['market_price']); ?></p>
                <p class="anomaly"><strong>Anomaly:</strong> <?php echo htmlspecialchars($product['price_anomaly']); ?></p>
            </div>

            <div class="info-card">
                <h4>Seller Information</h4>
                <p><strong>Seller:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></p>
                <p><strong>Seller Risk:</strong> <span class="risk-badge risk-<?php echo strtolower($product['seller_risk']); ?>"><?php echo htmlspecialchars($product['seller_risk']); ?></span></p>
                <a href="seller-analysis.php?id=1" class="link-btn">View Seller Profile</a>
            </div>
        </div>

        <section class="detail-section">
            <h3>Review Analysis</h3>
            <div class="review-stats">
                <p><strong>Total Reviews:</strong> <?php echo htmlspecialchars($product['review_count']); ?></p>
                <p><strong>Average Rating:</strong> <?php echo htmlspecialchars($product['average_rating']); ?> ⭐</p>
            </div>
            <h4>Suspicious Patterns Detected:</h4>
            <ul class="indicator-list">
                <?php foreach ($product['review_patterns'] as $pattern): ?>
                <li class="indicator-item">
                    <span class="indicator-icon">🚩</span>
                    <?php echo htmlspecialchars($pattern); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="detail-section">
            <h3>Analysis</h3>
            <div class="alert alert-warning">
                <?php echo htmlspecialchars($product['analysis']); ?>
            </div>
        </section>

        <section class="detail-section">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>
