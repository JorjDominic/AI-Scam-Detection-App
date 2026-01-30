<?php
session_start();

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role_id']) ||
    $_SESSION['role_id'] != 1
) {
    // Not logged in OR not admin
    header('Location: ../auth/login.php');
    exit;
}


// Mock admin data
$stats = [
    'total_scans' => 1247,
    'website_scans' => 523,
    'product_scans' => 456,
    'seller_scans' => 268,
    'high_risk_detections' => 342,
    'active_users' => 156,
    'total_users' => 892,
    'flagged_items' => 89
];

$recent_detections = [
    ['type' => 'Website', 'target' => 'malicious-domain.net', 'risk' => 'High', 'time' => '2 mins ago'],
    ['type' => 'Product', 'target' => 'Counterfeit Electronics', 'risk' => 'High', 'time' => '15 mins ago'],
    ['type' => 'Seller', 'target' => 'scam_seller_123', 'risk' => 'High', 'time' => '1 hour ago'],
    ['type' => 'Website', 'target' => 'phishing-attempt.com', 'risk' => 'Critical', 'time' => '2 hours ago'],
];

$pending_reviews = [
    ['id' => 1, 'type' => 'Product', 'target' => 'Suspicious Item', 'reports' => 12],
    ['id' => 2, 'type' => 'Seller', 'target' => 'Unknown Vendor', 'reports' => 8],
    ['id' => 3, 'type' => 'Website', 'target' => 'questionable-site.com', 'reports' => 5],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ScamGuard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">ScamGuard</div>
            <div class="nav-links">
                <span class="admin-badge">ADMIN</span>
                <a href="admin-scan-review.php">Review Scans</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container dashboard-container">
        <h1>Admin Dashboard</h1>

        <div class="stats-grid admin-stats">
            <div class="stat-card">
                <h3>Total Scans</h3>
                <p class="stat-number"><?php echo $stats['total_scans']; ?></p>
                <p class="stat-meta"><?php echo $stats['website_scans']; ?> websites | <?php echo $stats['product_scans']; ?> products | <?php echo $stats['seller_scans']; ?> sellers</p>
            </div>
            <div class="stat-card risk-high">
                <h3>High Risk Detections</h3>
                <p class="stat-number"><?php echo $stats['high_risk_detections']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Active Users</h3>
                <p class="stat-number"><?php echo $stats['active_users']; ?></p>
                <p class="stat-meta">of <?php echo $stats['total_users']; ?> total users</p>
            </div>
            <div class="stat-card risk-high">
                <h3>Flagged Items</h3>
                <p class="stat-number"><?php echo $stats['flagged_items']; ?></p>
                <p class="stat-meta">Pending Review</p>
            </div>
        </div>

        <section class="recent-detections">
            <h2>Recent High-Risk Detections</h2>
            <table class="scan-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Risk Level</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_detections as $detection): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detection['type']); ?></td>
                        <td><?php echo htmlspecialchars($detection['target']); ?></td>
                        <td>
                            <span class="risk-badge risk-<?php echo strtolower($detection['risk']); ?>">
                                <?php echo htmlspecialchars($detection['risk']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($detection['time']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="pending-reviews">
            <h2>Items Pending Review</h2>
            <table class="scan-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Target</th>
                        <th>User Reports</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_reviews as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['type']); ?></td>
                        <td><?php echo htmlspecialchars($item['target']); ?></td>
                        <td><?php echo $item['reports']; ?></td>
                        <td>
                            <a href="admin-scan-review.php?id=<?php echo $item['id']; ?>" class="link-btn">Review</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>
