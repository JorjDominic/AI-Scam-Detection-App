<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="ss-landing-header">
    <nav class="ss-landing-navbar">
        <div class="container">
            <div class="ss-landing-navbar-container">
                <!-- Logo with Icon Only -->
                <div class="ss-landing-nav-logo">
                    <a href="index.php" class="ss-landing-logo-link">
                        <div class="ss-landing-logo-icon">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <span class="ss-landing-logo-text">SureShop</span>
                    </a>
                </div>

                <!-- Center Navigation Links -->
                <div class="ss-landing-nav-center">
                    <ul class="ss-landing-nav-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how">How It Works</a></li>
                        <li><a href="#community">Community</a></li>
                        <li><a href="#demo">Extension Demo</a></li>
                    </ul>
                </div>

                <!-- Right Navigation Actions -->
                <div class="ss-landing-nav-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Logged in state -->
                        <div class="ss-landing-auth-links">
                            <a href="/php/sureshopwebsite/app/user/dashboard.php" class="ss-landing-nav-link">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="logout.php" class="btn btn-secondary ss-landing-btn-small">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Logged out state -->
                        <div class="ss-landing-auth-links">
                            <a href="/php/sureshopwebsite/app/auth/login.php" class="ss-landing-nav-link">
                                <i class="fas fa-sign-in-alt"></i> Sign in
                            </a>
                            <a href="/php/sureshopwebsite/app/auth/register.php" class="btn btn-primary ss-landing-btn-small">
                                <i class="fas fa-rocket"></i> Get Started
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button class="ss-landing-mobile-menu-btn" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Navigation Menu -->
            <div class="ss-landing-mobile-menu">
                <ul class="ss-landing-mobile-nav-links">
                    <li><a href="#features"><i class="fas fa-star"></i> Features</a></li>
                    <li><a href="#how"><i class="fas fa-play-circle"></i> How It Works</a></li>
                    <li><a href="#community"><i class="fas fa-users"></i> Community</a></li>
                    <li><a href="#demo"><i class="fas fa-video"></i> Extension Demo</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/php/sureshopwebsite/app/user/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <li><a href="/php/sureshopwebsite/app/controller/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Sign in</a></li>
                        <li><a href="register.php"><i class="fas fa-user-plus"></i> Get Started</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>