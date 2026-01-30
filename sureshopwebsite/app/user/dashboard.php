<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

/* Enforce profile completion */
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user['username']) {
    header('Location: user/complete_profile.php');
    exit;
}

// Mock data
$recent_scans = [
    ['id' => 1, 'type' => 'Website', 'target' => 'suspicious-store.com', 'risk' => 'High', 'date' => '2024-01-15'],
    ['id' => 2, 'type' => 'Product', 'target' => 'Fake Designer Watch', 'risk' => 'Medium', 'date' => '2024-01-14'],
    ['id' => 3, 'type' => 'Seller', 'target' => 'unknown_seller_2024', 'risk' => 'High', 'date' => '2024-01-13'],
    ['id' => 4, 'type' => 'Website', 'target' => 'legitimate-shop.com', 'risk' => 'Low', 'date' => '2024-01-12'],
];

$stats = [
    'total_scans' => 12,
    'high_risk' => 4,
    'medium_risk' => 5,
    'low_risk' => 3
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ScamGuard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">ScamGuard</div>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container dashboard-container">
        <h1>Your Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Scans</h3>
                <p class="stat-number"><?php echo $stats['total_scans']; ?></p>
            </div>
            <div class="stat-card risk-high">
                <h3>High Risk</h3>
                <p class="stat-number"><?php echo $stats['high_risk']; ?></p>
            </div>
            <div class="stat-card risk-medium">
                <h3>Medium Risk</h3>
                <p class="stat-number"><?php echo $stats['medium_risk']; ?></p>
            </div>
            <div class="stat-card risk-low">
                <h3>Low Risk</h3>
                <p class="stat-number"><?php echo $stats['low_risk']; ?></p>
            </div>
        </div>

        <section class="recent-scans">
            <h2>Recent Scans</h2>
            <table class="scan-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Risk Level</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_scans as $scan): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($scan['type']); ?></td>
                        <td><?php echo htmlspecialchars($scan['target']); ?></td>
                        <td>
                            <span class="risk-badge risk-<?php echo strtolower($scan['risk']); ?>">
                                <?php echo htmlspecialchars($scan['risk']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($scan['date']); ?></td>
                        <td>
                            <a href="scan-details.php?id=<?php echo $scan['id']; ?>" class="link-btn">View</a>
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
