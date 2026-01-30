<footer class="ss-landing-footer">
    <div class="container">
        <div class="ss-landing-footer-content">
            <!-- Column 1: Logo & Description -->
            <div class="ss-landing-footer-column">
                <div class="ss-landing-footer-logo">
                    <div class="ss-landing-logo-image">
                        <?php if (file_exists('assets/images/logo.png')): ?>
                            <img src="assets/images/logo.png" alt="SureShop Logo" class="ss-landing-logo-img">
                        <?php elseif (file_exists('assets/images/logo.svg')): ?>
                            <img src="assets/images/logo.svg" alt="SureShop Logo" class="ss-landing-logo-img">
                        <?php elseif (file_exists('assets/images/logo.jpg')): ?>
                            <img src="assets/images/logo.jpg" alt="SureShop Logo" class="ss-landing-logo-img">
                        <?php else: ?>
                            <div class="ss-landing-logo-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="ss-landing-logo-text">SureShop</span>
                </div>
                <p class="ss-landing-footer-description">
                    Advanced scam detection and prevention system for safe online shopping. Protecting users since 2023.
                </p>
                <div class="ss-landing-footer-social">
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    <a href="#" aria-label="Discord"><i class="fab fa-discord"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <!-- Column 2: Product -->
            <div class="ss-landing-footer-column">
                <h3 class="ss-landing-footer-heading">Product</h3>
                <ul class="ss-landing-footer-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how">How It Works</a></li>
                    <li><a href="#demo">Extension Demo</a></li>
                    <li><a href="https://github.com/JorjDominic/Browser-Extension" target="_blank">Download Extension</a></li>
                    <li><a href="#community">Community</a></li>
                </ul>
            </div>

            <!-- Column 3: Support -->
            <div class="ss-landing-footer-column">
                <h3 class="ss-landing-footer-heading">Support</h3>
                <ul class="ss-landing-footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">API Reference</a></li>
                    <li><a href="#">Contact Support</a></li>
                    <li><a href="#">Status</a></li>
                </ul>
            </div>

            <!-- Column 4: Legal -->
            <div class="ss-landing-footer-column">
                <h3 class="ss-landing-footer-heading">Legal</h3>
                <ul class="ss-landing-footer-links">
                    <li><a href="privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="privacy-policy.php">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                    <li><a href="#">GDPR Compliance</a></li>
                    <li><a href="#">Security</a></li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="ss-landing-footer-bottom">
            <div class="ss-landing-footer-copyright">
                <p>&copy; 2023-2026 SureShop. All rights reserved.</p>
            </div>
            <div class="ss-landing-footer-legal">
                <a href="privacy-policy.php">Privacy</a>
                <span class="ss-landing-footer-divider">•</span>
                <a href="privacy-policy.php">Terms</a>
                <span class="ss-landing-footer-divider">•</span>
                <a href="#">Cookies</a>
                <span class="ss-landing-footer-divider">•</span>
                <a href="#">Sitemap</a>
            </div>
        </div>
    </div>
</footer>