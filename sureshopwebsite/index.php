<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SureShop - Scam Detection System</title>
    <link rel="stylesheet" href="app/assets/css/styles.css">
    <link rel="stylesheet" href="app/assets/css/landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'app/view/layouts/header.php'; ?>

    <main class="ss-landing-main">
        <!-- Hero Section -->
        <section class="ss-landing-hero" id="home">
            <div class="ss-landing-hero-bg"></div>
            <div class="container">
                <div class="ss-landing-hero-content">
                    <div class="ss-landing-hero-text">
                        <h1 class="ss-landing-hero-title">Stay One Step Ahead of Online Scams</h1>
                        <p class="ss-landing-hero-subtitle">Real-time scam detection for websites, products, and sellers. Protect your online shopping with AI-powered security.</p>
                        <div class="ss-landing-hero-stats">
                            <div class="ss-landing-stat-item">
                                <i class="fas fa-shield-check"></i>
                                <span>99.7% detection accuracy</span>
                            </div>
                            <div class="ss-landing-stat-item">
                                <i class="fas fa-bolt"></i>
                                <span>Real-time protection</span>
                            </div>
                        </div>
                        <div class="ss-landing-hero-buttons">
                            <a href="https://github.com/JorjDominic/Browser-Extension" 
                               class="btn btn-primary ss-landing-btn-primary" 
                               target="_blank" 
                               rel="noopener noreferrer">
                               <i class="fas fa-download"></i> Install Browser Extension
                            </a>
                            <a href="#demo" class="btn btn-secondary ss-landing-btn-secondary">
                                <i class="fas fa-play-circle"></i> Watch Demo
                            </a>
                            <a href="#features" class="ss-landing-btn-tertiary">
                                <i class="fas fa-chart-line"></i> Explore All Features
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="ss-landing-section ss-landing-features" id="features">
            <div class="container">
                <div class="ss-landing-section-header">
                    <h2 class="ss-landing-section-title">Comprehensive Scam Protection</h2>
                    <p class="ss-landing-section-subtitle">Protect yourself across all aspects of online shopping</p>
                </div>
                <div class="ss-landing-features-grid">
                    <div class="ss-landing-feature-card">
                        <div class="ss-landing-feature-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3>Website Analysis</h3>
                        <p>Detect fake websites and malicious domains in real-time with advanced URL scanning.</p>
                    </div>
                    <div class="ss-landing-feature-card">
                        <div class="ss-landing-feature-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h3>Product Verification</h3>
                        <p>Identify counterfeit products and suspicious pricing patterns across multiple platforms.</p>
                    </div>
                    <div class="ss-landing-feature-card">
                        <div class="ss-landing-feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3>Seller Assessment</h3>
                        <p>Evaluate seller reputation and detect fraudulent behavior with trust scoring.</p>
                    </div>
                    <div class="ss-landing-feature-card">
                        <div class="ss-landing-feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Real-time Alerts</h3>
                        <p>Get instant warnings while browsing with our browser extension notifications.</p>
                    </div>
                    <div class="ss-landing-feature-card">
                        <div class="ss-landing-feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3>Risk Analytics</h3>
                        <p>Advanced analytics dashboard showing scam trends and protection insights.</p>
                    </div>
                    <div class="ss-landing-feature-card">
                        <div class="ss-landing-feature-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>Historical Data</h3>
                        <p>Access historical scam reports and patterns to make informed decisions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="ss-landing-section ss-landing-how" id="how">
            <div class="container">
                <div class="ss-landing-section-header">
                    <h2 class="ss-landing-section-title">How SureShop Works</h2>
                    <p class="ss-landing-section-subtitle">Simple steps to complete protection</p>
                </div>
                <div class="ss-landing-steps">
                    <div class="ss-landing-step-card">
                        <div class="ss-landing-step-number">01</div>
                        <div class="ss-landing-step-icon">
                            <i class="fas fa-puzzle-piece"></i>
                        </div>
                        <h3>Install Extension</h3>
                        <p>Add our browser extension to Chrome, Firefox, or Edge in under 30 seconds.</p>
                    </div>
                    <div class="ss-landing-step-card">
                        <div class="ss-landing-step-number">02</div>
                        <div class="ss-landing-step-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Browse Securely</h3>
                        <p>Shop online as you normally would. Our extension works silently in the background.</p>
                    </div>
                    <div class="ss-landing-step-card">
                        <div class="ss-landing-step-number">03</div>
                        <div class="ss-landing-step-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Get Protected</h3>
                        <p>Receive real-time alerts about scams, fakes, and suspicious sellers instantly.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Community Section -->
        <section class="ss-landing-section ss-landing-community" id="community">
            <div class="container">
                <div class="ss-landing-section-header">
                    <h2 class="ss-landing-section-title">Join Our Security Community</h2>
                    <p class="ss-landing-section-subtitle">Thousands of users protecting each other online</p>
                </div>
                <div class="ss-landing-community-stats">
                    <div class="ss-landing-community-stat">
                        <div class="ss-landing-community-number">50K+</div>
                        <p>Active Users</p>
                    </div>
                    <div class="ss-landing-community-stat">
                        <div class="ss-landing-community-number">1M+</div>
                        <p>Websites Scanned</p>
                    </div>
                    <div class="ss-landing-community-stat">
                        <div class="ss-landing-community-number">25K+</div>
                        <p>Scams Prevented</p>
                    </div>
                </div>
                <div class="ss-landing-community-content">
                    <div class="ss-landing-community-text">
                        <h3>Collective Protection Power</h3>
                        <p>Every user report and detection makes our system smarter. Join a community that works together to make online shopping safer for everyone.</p>
                        <ul class="ss-landing-community-list">
                            <li><i class="fas fa-check-circle"></i> Contribute to scam detection database</li>
                            <li><i class="fas fa-check-circle"></i> Access community trust ratings</li>
                            <li><i class="fas fa-check-circle"></i> Participate in security discussions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Demo Section -->
        <section class="ss-landing-section ss-landing-demo" id="demo">
            <div class="container">
                <div class="ss-landing-demo-content">
                    <div class="ss-landing-demo-text">
                        <h2 class="ss-landing-section-title">See It In Action</h2>
                        <p class="ss-landing-demo-subtitle">Watch how SureShop protects you in real-time while you shop</p>
                        <div class="ss-landing-demo-features">
                            <div class="ss-landing-demo-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Live detection demonstrations</span>
                            </div>
                            <div class="ss-landing-demo-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Extension walkthrough</span>
                            </div>
                            <div class="ss-landing-demo-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>Dashboard tour</span>
                            </div>
                        </div>
                        <div class="ss-landing-demo-video">
                            <div class="ss-landing-video-placeholder">
                                <i class="fas fa-play-circle"></i>
                                <p>Demo Video Coming Soon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="ss-landing-section ss-landing-cta">
            <div class="container">
                <div class="ss-landing-cta-content">
                    <h2>Ready to Browse Safely?</h2>
                    <p>Join thousands of protected users and shop with confidence today.</p>
                    <div class="ss-landing-cta-buttons">
                        <a href="https://github.com/JorjDominic/Browser-Extension" 
                           class="btn btn-primary ss-landing-btn-primary" 
                           target="_blank" 
                           rel="noopener noreferrer">
                           <i class="fas fa-download"></i> Install Extension
                        </a>
                        <a href="register.php" class="btn btn-secondary ss-landing-btn-secondary">
                            <i class="fas fa-user-plus"></i> Create Free Account
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'app/view/layouts/footer.php'; ?>

    <script src="app/assets/js/landing.js"></script>
</body>
</html>