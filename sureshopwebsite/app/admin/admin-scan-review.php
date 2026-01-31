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
    <title>Review Scan - SureShop Admin</title>
    
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ===== OVERRIDES ===== */
        :root {
            --dash-primary: #22c55e;
            --dash-primary-dark: #15803d;
            --dash-primary-light: #dcfce7;
            --dash-secondary: #3b82f6;
            --dash-dark: #1f2937;
            --dash-light: #f9fafb;
            --dash-gray: #6b7280;
            --dash-gray-light: #f3f4f6;
            --dash-border: #e5e7eb;
            --dash-danger: #dc2626;
            --dash-warning: #f59e0b;
            --dash-success: #16a34a;
            --dash-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --dash-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --dash-radius: 12px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dash-light);
            color: var(--dash-dark);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
        }

        /* ===== HEADER ===== */
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
            color: var(--dash-dark);
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .header-logo i {
            color: var(--dash-primary);
            font-size: 1.5rem;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-name {
            color: var(--dash-gray);
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
            background: var(--dash-gray-light);
            color: var(--dash-primary);
        }
        
        .header-link.logout {
            color: var(--dash-danger);
        }
        
        .header-link.logout:hover {
            background: #fee2e2;
        }

        /* ===== MAIN CONTENT ===== */
        .dashboard-main {
            flex: 1;
            padding-top: 90px;
            padding-bottom: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f0f9ff 100%);
            min-height: calc(100vh - 300px);
        }

        .detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--dash-gray);
            text-decoration: none;
            margin-bottom: 1.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: var(--dash-radius);
            background: white;
            border: 1px solid var(--dash-border);
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: var(--dash-shadow);
        }

        .back-link:hover {
            background: var(--dash-primary-light);
            color: var(--dash-primary-dark);
            border-color: var(--dash-primary);
            transform: translateX(-5px);
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--dash-radius);
            margin-bottom: 2rem;
            border-left: 4px solid transparent;
            box-shadow: var(--dash-shadow);
            font-weight: 500;
        }

        .alert-success {
            background-color: var(--dash-primary-light);
            color: var(--dash-success);
            border-left-color: var(--dash-success);
            border: 1px solid var(--dash-primary);
        }

        /* ===== REVIEW SECTION ===== */
        .review-section {
            background: white;
            border-radius: var(--dash-radius);
            padding: 2.5rem;
            box-shadow: var(--dash-shadow);
            border: 1px solid var(--dash-border);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--dash-border);
        }

        .review-header h2 {
            color: var(--dash-dark);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .meta {
            color: var(--dash-gray);
            font-size: 0.95rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .risk-indicator {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .risk-score {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.1rem;
            min-width: 80px;
            height: 45px;
        }

        .risk-high {
            background-color: #fee2e2;
            color: var(--dash-danger);
            border: 2px solid #fecaca;
        }

        /* ===== DETAIL SECTIONS ===== */
        .detail-section {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--dash-border);
        }

        .detail-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        h3 {
            color: var(--dash-dark);
            margin-bottom: 1.25rem;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h3 i {
            color: var(--dash-primary);
            font-size: 1.1rem;
        }

        .indicator-list {
            list-style: none;
            display: grid;
            gap: 1rem;
        }

        .indicator-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem 1.25rem;
            background-color: #fef3c7;
            border-radius: 8px;
            border-left: 4px solid var(--dash-warning);
            transition: all 0.2s ease;
        }

        .indicator-item:hover {
            transform: translateX(5px);
            box-shadow: var(--dash-shadow);
        }

        .indicator-icon {
            margin-right: 12px;
            color: var(--dash-warning);
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* ===== TABLES ===== */
        .scan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--dash-border);
            box-shadow: var(--dash-shadow);
        }

        .scan-table th,
        .scan-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--dash-border);
        }

        .scan-table th {
            background-color: var(--dash-dark);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .scan-table tr:last-child td {
            border-bottom: none;
        }

        .scan-table tr:hover {
            background-color: var(--dash-gray-light);
        }

        /* ===== DETAILS/SUMMARY ===== */
        details {
            margin-top: 1rem;
            border-radius: 8px;
            overflow: hidden;
        }

        summary {
            cursor: pointer;
            padding: 1rem;
            background-color: var(--dash-gray-light);
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        summary:hover {
            background-color: #e5e7eb;
        }

        summary:after {
            content: "▼";
            font-size: 0.8rem;
            transition: transform 0.3s;
        }

        details[open] summary:after {
            transform: rotate(180deg);
        }

        details ul {
            margin-top: 0;
            padding: 1.5rem;
            background-color: white;
            border: 1px solid var(--dash-border);
            border-top: none;
            border-radius: 0 0 8px 8px;
        }

        details li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
            color: var(--dash-gray);
        }

        details li:last-child {
            border-bottom: none;
        }

        /* ===== REVIEW FORM ===== */
        .review-form {
            background-color: #f9fafb;
            padding: 2rem;
            border-radius: var(--dash-radius);
            border: 1px solid var(--dash-border);
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dash-dark);
            font-size: 0.95rem;
        }

        select,
        textarea {
            width: 100%;
            padding: 0.875rem;
            border: 1px solid var(--dash-border);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background-color: white;
            font-family: 'Poppins', sans-serif;
        }

        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--dash-primary);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            font-size: 0.95rem;
            min-height: 44px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background-color: var(--dash-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--dash-primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--dash-shadow-lg);
        }

        .btn-secondary {
            background-color: white;
            color: var(--dash-primary);
            border-color: var(--dash-primary);
        }

        .btn-secondary:hover {
            background-color: var(--dash-primary-light);
            transform: translateY(-2px);
            box-shadow: var(--dash-shadow);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--dash-border);
        }

        .action-buttons .btn {
            flex: 1;
        }

        .dashboard-button-container {
            display: flex;
            justify-content: center;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid var(--dash-border);
        }

        .dashboard-button-container .btn {
            min-width: 220px;
            font-size: 1rem;
            padding: 1rem 2rem;
        }

        /* ===== FOOTER ===== */
        .dashboard-footer {
            background: var(--dash-dark);
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

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .review-section {
                padding: 1.5rem;
            }
            
            .review-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .risk-indicator {
                align-self: flex-start;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .header-user {
                gap: 10px;
            }
            
            .header-link span {
                display: none;
            }
            
            .dashboard-button-container .btn {
                width: 100%;
                min-width: auto;
            }
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
                <span class="user-name"><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'admin@sureshop.com'; ?></span>
                <div class="admin-badge">
                    <i class="fas fa-crown"></i> ADMIN
                </div>
                <a href="admin-dashboard.php" class="header-link">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
                <a href="user-management.php" class="header-link">
                    <i class="fas fa-users"></i> <span>Users</span>
                </a>
                <a href="../controller/logout.php" class="header-link logout">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </div>
        </div>
    </header>
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-main">
        <div class="container detail-container">
            <a href="admin-dashboard.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Admin Dashboard
            </a>

            <h1 style="color: var(--dash-dark); margin-bottom: 1.5rem; font-size: 2rem; font-weight: 700; border-bottom: 2px solid var(--dash-border); padding-bottom: 0.75rem;">
                <i class="fas fa-search" style="color: var(--dash-primary);"></i> Review & Label Item
            </h1>
            <?php echo $message; ?>

            <div class="review-section">
                <div class="review-header">
                    <div>
                        <h2><?php echo htmlspecialchars($review_item['target']); ?></h2>
                        <div class="meta">
                            <span><i class="fas fa-tag"></i> Type: <?php echo htmlspecialchars($review_item['type']); ?></span>
                            <span><i class="fas fa-hashtag"></i> ID: #<?php echo $review_item['id']; ?></span>
                            <span><i class="fas fa-flag"></i> Reports: <?php echo $review_item['user_reports']; ?></span>
                        </div>
                    </div>
                    <div class="risk-indicator">
                        <span class="risk-score risk-high"><?php echo $review_item['risk_score']; ?>% Risk</span>
                    </div>
                </div>

                <section class="detail-section">
                    <h3><i class="fas fa-exclamation-triangle"></i> Detected Indicators</h3>
                    <ul class="indicator-list">
                        <?php foreach ($review_item['indicators'] as $indicator): ?>
                        <li class="indicator-item">
                            <span class="indicator-icon"><i class="fas fa-exclamation-circle"></i></span>
                            <?php echo htmlspecialchars($indicator); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>

                <section class="detail-section">
                    <h3><i class="fas fa-users"></i> User Reports</h3>
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
                    <h3><i class="fas fa-history"></i> Previous Labels</h3>
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
                                <td>
                                    <span class="risk-badge" style="background: var(--dash-primary-light); color: var(--dash-primary-dark); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">
                                        <?php echo htmlspecialchars($label['label']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($label['notes']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
                <?php endif; ?>

                <section class="detail-section review-form">
                    <h3><i class="fas fa-tag"></i> Add Label & Notes</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="label">Classification Label</label>
                            <select id="label" name="label" required>
                                <option value="">-- Select Classification --</option>
                                <option value="Confirmed Scam">🚫 Confirmed Scam</option>
                                <option value="Likely Scam">⚠️ Likely Scam</option>
                                <option value="Suspicious">🔍 Suspicious</option>
                                <option value="Legitimate">✅ Legitimate</option>
                                <option value="Needs More Data">📊 Needs More Data</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notes">Review Notes</label>
                            <textarea id="notes" name="notes" rows="4" placeholder="Add detailed notes for model training and team review..."></textarea>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Label & Notes
                            </button>
                            <a href="admin-dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel & Return
                            </a>
                        </div>
                    </form>
                </section>

                <div class="dashboard-button-container">
                    <a href="admin-dashboard.php" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Back to Admin Dashboard
                    </a>
                </div>
            </div>
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

    <script>
        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const labelSelect = document.getElementById('label');
            
            form.addEventListener('submit', function(e) {
                if (!labelSelect.value) {
                    e.preventDefault();
                    labelSelect.focus();
                    labelSelect.style.borderColor = '#dc2626';
                    labelSelect.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
                    
                    // Remove error style after 2 seconds
                    setTimeout(() => {
                        labelSelect.style.borderColor = '#e5e7eb';
                        labelSelect.style.boxShadow = 'none';
                    }, 2000);
                }
            });
            
            // Add emoji to selected options for better UX
            labelSelect.addEventListener('change', function() {
                const emojiMap = {
                    'Confirmed Scam': '🚫',
                    'Likely Scam': '⚠️',
                    'Suspicious': '🔍',
                    'Legitimate': '✅',
                    'Needs More Data': '📊'
                };
                
                if (this.value && emojiMap[this.value]) {
                    const selectedOption = this.options[this.selectedIndex];
                    if (!selectedOption.textContent.includes(emojiMap[this.value])) {
                        selectedOption.textContent = emojiMap[this.value] + ' ' + this.value;
                    }
                }
            });
        });
    </script>
</body>
</html>