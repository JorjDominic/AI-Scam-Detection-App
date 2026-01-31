<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}
$extension_key = $_SESSION['extension_key'] ?? null;
unset($_SESSION['extension_key']);

// Enforce profile completion
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user['username']) {
    header('Location: complete_profile.php');
    exit;
}
// Check if extension is already activated
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM access_tokens 
    WHERE user_id = ? AND revoked = 0
");
$stmt->execute([$_SESSION['user_id']]);
$is_extension_activated = $stmt->fetchColumn() > 0;
// Mock user data
$user_stats = [
    'total_scans' => 47,
    'website_scans' => 23,
    'product_scans' => 15,
    'seller_scans' => 9,
    'high_risk_found' => 8,
    'protected_items' => 39
];

$recent_scans = [
    ['type' => 'Website', 'target' => 'amazon.com', 'risk' => 'Low', 'time' => '2 hours ago'],
    ['type' => 'Product', 'target' => 'Wireless Earbuds', 'risk' => 'Medium', 'time' => '1 day ago'],
    ['type' => 'Seller', 'target' => 'TechDealsPro', 'risk' => 'Low', 'time' => '2 days ago'],
    ['type' => 'Website', 'target' => 'suspicious-shop.net', 'risk' => 'High', 'time' => '3 days ago'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SureShop</title>
    
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
                <span class="user-name">Hi, <?php echo htmlspecialchars($user['username']); ?></span>
                <a href="dashboard.php" class="header-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="settings.php" class="header-link">
                    <i class="fas fa-cog"></i> Settings
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
            <!-- Dashboard Title -->
            <div class="dashboard-title">
                <h1><i class="fas fa-tachometer-alt"></i> Your Dashboard</h1>
                <p class="welcome-text">Welcome back! Here's your security overview.</p>
            </div>
            
            <!-- Quick Actions -->
            <section class="quick-actions">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                <div class="actions-grid">
                    <a href="scan.php" class="action-card">
                        <i class="fas fa-search"></i>
                        <h3>New Scan</h3>
                        <p>Scan a website, product, or seller</p>
                    </a>
                    <a href="scan-history.php" class="action-card">
                        <i class="fas fa-history"></i>
                        <h3>Scan History</h3>
                        <p>View your previous scans</p>
                    </a>
                    <a href="settings.php" class="action-card">
                        <i class="fas fa-cog"></i>
                        <h3>Settings</h3>
                        <p>Manage your account</p>
                    </a>
                    <a href="https://github.com/JorjDominic/Browser-Extension" target="_blank" class="action-card">
                        <i class="fas fa-puzzle-piece"></i>
                        <h3>Extension</h3>
                        <p>Install browser extension</p>
                    </a>
                </div>
            </section>
<section class="table-section">
    <h2><i class="fas fa-puzzle-piece"></i> Browser Extension</h2>

    <?php if ($is_extension_activated): ?>
        <div class="activation-success">
            <p><strong>✅ Extension Activated</strong></p>
            <p>Your browser extension is successfully linked to your account.</p>
        </div>

    <?php elseif (!empty($extension_key)): ?>
        <div class="activation-key-box">
            <p><strong>Your Activation Key</strong> (valid for 10 minutes)</p>
            <code><?php echo htmlspecialchars($extension_key); ?></code>
            <p class="warning-text">
                Paste this key into the SureShop browser extension.
                This key can only be used once.
            </p>
        </div>

    <?php else: ?>
        <p>Activate your browser extension to scan Shopee products.</p>
        <form method="POST" action="../controller/generate_extension_key.php">
            <button type="submit" class="action-btn btn-primary">
                <i class="fas fa-key"></i> Generate Activation Key
            </button>
        </form>
    <?php endif; ?>
</section>

            <!-- Stats -->
            <section class="stats-section">
                <h2><i class="fas fa-chart-bar"></i> Your Stats</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h3>Total Scans</h3>
                            <div class="stat-icon">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="stat-number"><?php echo $user_stats['total_scans']; ?></div>
                        <div class="stat-meta">
                            <?php echo $user_stats['website_scans']; ?> websites • 
                            <?php echo $user_stats['product_scans']; ?> products • 
                            <?php echo $user_stats['seller_scans']; ?> sellers
                        </div>
                    </div>
                    
                    <div class="stat-card <?php echo $user_stats['high_risk_found'] > 0 ? 'risk-high' : 'risk-low'; ?>">
                        <div class="stat-card-header">
                            <h3>Scams Detected</h3>
                            <div class="stat-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                        <div class="stat-number"><?php echo $user_stats['high_risk_found']; ?></div>
                        <div class="stat-meta">Potential scams blocked</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h3>Protected Items</h3>
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="stat-number"><?php echo $user_stats['protected_items']; ?></div>
                        <div class="stat-meta">Safe items in your history</div>
                    </div>
                </div>
            </section>
            
            <!-- Recent Scans -->
            <section class="table-section">
                <h2><i class="fas fa-clock"></i> Recent Scans</h2>
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
                        <?php foreach ($recent_scans as $scan): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($scan['type']); ?></td>
                            <td><?php echo htmlspecialchars($scan['target']); ?></td>
                            <td>
                                <span class="risk-badge risk-<?php echo strtolower($scan['risk']); ?>">
                                    <?php echo htmlspecialchars($scan['risk']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($scan['time']); ?></td>
                            <td>
                                <a href="scan-details.php" class="action-btn btn-secondary">
                                    <i class="fas fa-eye"></i> View
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
                    &copy; 2024 SureShop. Protecting users from online scams.
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