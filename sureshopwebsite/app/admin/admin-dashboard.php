<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
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
    <title>Admin Dashboard - SureShop</title>
    
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* DASHBOARD HEADER */
        .dashboard-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 70px;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #1f2937;
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .header-logo i {
            color: #22c55e;
            font-size: 1.5rem;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-name {
            color: #6b7280;
            font-weight: 500;
        }
        
        .admin-badge {
            background: #fee2e2;
            color: #991b1b;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .header-link {
            color: #4b5563;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.95rem;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        
        .header-link:hover {
            background: #f3f4f6;
            color: #22c55e;
        }
        
        .header-link.logout {
            color: #dc2626;
        }
        
        .header-link.logout:hover {
            background: #fee2e2;
        }
        
        /* DASHBOARD FOOTER */
        .dashboard-footer {
            background: #1f2937;
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .footer-copyright {
            color: #9ca3af;
            font-size: 0.9rem;
        }
        
        .footer-links {
            display: flex;
            gap: 1.5rem;
        }
        
        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        /* MAIN CONTENT SPACING */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: #f9fafb;
        }
        
        .dashboard-main {
            flex: 1;
            padding-top: 90px; /* Space for fixed header */
            padding-bottom: 2rem;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* ADMIN SPECIFIC */
        .admin-controls {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .admin-controls h2 {
            color: #92400e;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .controls-grid {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .control-btn {
            background: #92400e;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .control-btn:hover {
            background: #7c3412;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- DASHBOARD HEADER -->
    <header class="dashboard-header">
        <div class="header-container">
            <a href="../index.php" class="header-logo">
                <i class="fas fa-shield-check"></i>
                <span>SureShop</span>
            </a>
            
            <div class="header-user">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                <div class="admin-badge">
                    <i class="fas fa-crown"></i> ADMIN
                </div>
                <a href="admin-dashboard.php" class="header-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="user-management.php" class="header-link">
                    <i class="fas fa-users"></i> Users
                </a>
                <a href="../controller/logout.php" class="header-link logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-main">
        <div class="dashboard-container">
            <!-- Admin Title -->
            <div class="dashboard-title">
                <h1><i class="fas fa-crown"></i> Admin Dashboard</h1>
                <p class="welcome-text">System Administration Panel</p>
            </div>
            
            <!-- Admin Controls -->
            <div class="admin-controls">
                <h2><i class="fas fa-tools"></i> Admin Controls</h2>
                <div class="controls-grid">
                    <a href="admin-scan-review.php" class="control-btn">
                        <i class="fas fa-search"></i> Review Scans
                    </a>
                    <a href="user-management.php" class="control-btn">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a href="system-logs.php" class="control-btn">
                        <i class="fas fa-clipboard-list"></i> System Logs
                    </a>
                    <a href="settings.php" class="control-btn">
                        <i class="fas fa-cog"></i> System Settings
                    </a>
                </div>
            </div>
            
            <!-- Admin Stats -->
            <section class="stats-section">
                <h2><i class="fas fa-chart-network"></i> System Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h3>Total Scans</h3>
                            <div class="stat-icon">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="stat-number"><?php echo number_format($stats['total_scans']); ?></div>
                        <div class="stat-meta">
                            <?php echo $stats['website_scans']; ?> websites • 
                            <?php echo $stats['product_scans']; ?> products • 
                            <?php echo $stats['seller_scans']; ?> sellers
                        </div>
                    </div>
                    
                    <div class="stat-card risk-high">
                        <div class="stat-card-header">
                            <h3>High Risk</h3>
                            <div class="stat-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="stat-number"><?php echo $stats['high_risk_detections']; ?></div>
                        <div class="stat-meta">Potential threats</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h3>Active Users</h3>
                            <div class="stat-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="stat-number"><?php echo $stats['active_users']; ?></div>
                        <div class="stat-meta">of <?php echo $stats['total_users']; ?> total</div>
                    </div>
                    
                    <div class="stat-card risk-high">
                        <div class="stat-card-header">
                            <h3>Flagged Items</h3>
                            <div class="stat-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                        </div>
                        <div class="stat-number"><?php echo $stats['flagged_items']; ?></div>
                        <div class="stat-meta">Pending Review</div>
                    </div>
                </div>
            </section>
            
            <!-- Recent Detections -->
            <section class="table-section">
                <h2><i class="fas fa-exclamation-circle"></i> Recent High-Risk Detections</h2>
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Target</th>
                            <th>Risk Level</th>
                            <th>Time</th>
                            <th>Action</th>
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
                            <td>
                                <a href="admin-scan-review.php" class="action-btn btn-secondary">
                                    <i class="fas fa-external-link-alt"></i> Investigate
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            
            <!-- Pending Reviews -->
            <section class="table-section">
                <h2><i class="fas fa-clock"></i> Items Pending Review</h2>
                <table class="dashboard-table">
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
                            <td><?php echo $item['reports']; ?> reports</td>
                            <td>
                                <a href="admin-scan-review.php?id=<?php echo $item['id']; ?>" class="action-btn btn-primary">
                                    <i class="fas fa-search"></i> Review
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>
    
    <!-- DASHBOARD FOOTER -->
    <footer class="dashboard-footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-copyright">
                    &copy; 2024 SureShop. Admin System v1.0
                </div>
                <div class="footer-links">
                    <a href="../index.php">Home</a>
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>