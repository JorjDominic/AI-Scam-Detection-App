<?php
session_start();
// For demo, mock admin access
$_SESSION['user_role'] = 'admin';

$item_id = $_GET['id'] ?? 1;
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $label = $_POST['label'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if (!empty($label)) {
        $message = '<div class="alert alert-success">Item labeled as "' . htmlspecialchars($label) . '" and notes saved successfully.</div>';
    }
}

// Mock data
$review_item = [
    'id' => $item_id,
    'type' => 'Product',
    'target' => 'Suspicious Item',
    'user_reports' => 12,
    'risk_score' => 78,
    'indicators' => [
        'Fake reviews detected',
        'Pricing anomaly (95% below market)',
        'Seller has history of fraudulent activity',
        'Copied product images'
    ],
    'reporter_emails' => ['user1@example.com', 'user2@example.com', 'user3@example.com', 'user4@example.com'],
    'previous_labels' => [
        ['date' => '2024-01-10', 'label' => 'Suspicious', 'notes' => 'Under investigation']
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Review - ScamGuard Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">ScamGuard</div>
            <div class="nav-links">
                <span class="admin-badge">ADMIN</span>
                <a href="admin-dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container detail-container">
        <a href="admin-dashboard.php" class="back-link">← Back to Admin Dashboard</a>

        <h1>Review & Label Item</h1>
        <?php echo $message; ?>

        <div class="review-section">
            <div class="review-header">
                <div>
                    <h2><?php echo htmlspecialchars($review_item['target']); ?></h2>
                    <p class="meta">Type: <?php echo htmlspecialchars($review_item['type']); ?> | ID: <?php echo $review_item['id']; ?></p>
                </div>
                <div class="risk-indicator">
                    <span class="risk-score risk-high"><?php echo $review_item['risk_score']; ?></span>
                </div>
            </div>

            <section class="detail-section">
                <h3>Detected Indicators</h3>
                <ul class="indicator-list">
                    <?php foreach ($review_item['indicators'] as $indicator): ?>
                    <li class="indicator-item">
                        <span class="indicator-icon">⚠️</span>
                        <?php echo htmlspecialchars($indicator); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="detail-section">
                <h3>User Reports</h3>
                <p><strong>Total Reports:</strong> <?php echo $review_item['user_reports']; ?></p>
                <details>
                    <summary>View Reporter Emails</summary>
                    <ul>
                        <?php foreach ($review_item['reporter_emails'] as $email): ?>
                        <li><?php echo htmlspecialchars($email); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            </section>

            <?php if (!empty($review_item['previous_labels'])): ?>
            <section class="detail-section">
                <h3>Previous Labels</h3>
                <table class="scan-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Label</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($review_item['previous_labels'] as $label): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($label['date']); ?></td>
                            <td><?php echo htmlspecialchars($label['label']); ?></td>
                            <td><?php echo htmlspecialchars($label['notes']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <?php endif; ?>

            <section class="detail-section review-form">
                <h3>Add Label & Notes</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="label">Label</label>
                        <select id="label" name="label" required>
                            <option value="">-- Select Label --</option>
                            <option value="Confirmed Scam">Confirmed Scam</option>
                            <option value="Likely Scam">Likely Scam</option>
                            <option value="Suspicious">Suspicious</option>
                            <option value="Legitimate">Legitimate</option>
                            <option value="Needs More Data">Needs More Data</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Review Notes</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Add notes for model training and evaluation..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Label & Notes</button>
                </form>
            </section>

            <section class="detail-section">
                <a href="admin-dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </section>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
