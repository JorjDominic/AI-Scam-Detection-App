<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - ScamGuard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">ScamGuard</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container legal-container">
        <h1>Privacy Policy & Terms of Service</h1>

        <section class="legal-section">
            <h2>1. Privacy Policy</h2>
            
            <h3>1.1 Introduction</h3>
            <p>ScamGuard ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and browser extension.</p>

            <h3>1.2 Information We Collect</h3>
            <p>We collect the following types of information:</p>
            <ul>
                <li><strong>Account Information:</strong> Email address, password (hashed), account creation date</li>
                <li><strong>Scan Data:</strong> URLs, products, and sellers you scan through our extension</li>
                <li><strong>Usage Data:</strong> How you interact with our service, scan frequency, analysis views</li>
                <li><strong>Technical Data:</strong> Browser type, IP address (anonymized), timestamps</li>
            </ul>

            <h3>1.3 How We Use Your Information</h3>
            <p>We use collected information for the following purposes:</p>
            <ul>
                <li>Providing and improving our scam detection service</li>
                <li>Training and validating our detection models</li>
                <li>Sending service updates and security alerts</li>
                <li>Preventing fraud and maintaining security</li>
                <li>Legal compliance and dispute resolution</li>
            </ul>

            <h3>1.4 Data Protection</h3>
            <p>We implement industry-standard security measures including:</p>
            <ul>
                <li>HTTPS encryption for all data in transit</li>
                <li>Database encryption at rest</li>
                <li>Regular security audits and penetration testing</li>
                <li>Strict access controls for employee data access</li>
            </ul>

            <h3>1.5 Data Retention</h3>
            <p>We retain your scan data for 90 days to improve our detection models. Account information is retained until account deletion. You can request data deletion at any time.</p>

            <h3>1.6 Third-Party Sharing</h3>
            <p>We do NOT sell your personal information. We may share aggregated, anonymized data with security researchers to improve threat detection. We comply with all applicable data protection regulations.</p>
        </section>

        <section class="legal-section">
            <h2>2. Terms of Service</h2>

            <h3>2.1 User Agreement</h3>
            <p>By using ScamGuard, you agree to these Terms of Service. If you do not agree, please do not use our service.</p>

            <h3>2.2 Acceptable Use</h3>
            <p>You agree NOT to:</p>
            <ul>
                <li>Attempt to bypass our security measures</li>
                <li>Use the service for illegal activities</li>
                <li>Harass other users or misuse the platform</li>
                <li>Reverse engineer or attempt to access our algorithms</li>
                <li>Submit false or misleading information</li>
            </ul>

            <h3>2.3 Limitation of Liability</h3>
            <p>ScamGuard provides its service on an "as-is" basis. While we strive for accuracy, we do not guarantee that our detection will catch all scams. Users are responsible for exercising caution when making purchases or sharing information online.</p>

            <h3>2.4 Disclaimer</h3>
            <p>ScamGuard is not a law enforcement agency. Our detection results are for informational purposes only and should not be solely relied upon for making financial decisions. Always exercise due diligence.</p>

            <h3>2.5 Termination</h3>
            <p>We reserve the right to terminate accounts that violate these terms or engage in harmful activities.</p>

            <h3>2.6 Changes to Terms</h3>
            <p>We may update these policies at any time. Continued use of the service after changes constitutes acceptance of the new terms.</p>
        </section>

        <section class="legal-section">
            <h2>3. Contact Us</h2>
            <p>If you have questions about this Privacy Policy or Terms of Service, please contact us at:</p>
            <p>
                <strong>Email:</strong> privacy@scamguard.com<br>
                <strong>Address:</strong> ScamGuard Inc., Security Division<br>
                <strong>Last Updated:</strong> January 2024
            </p>
        </section>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 ScamGuard. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
