<?php
require_once __DIR__ . '/../config/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
if ($user && password_verify($password, $user['password_hash'])) {

    // Login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role_id'] = $user['role_id'];
    $_SESSION['username'] = $user['username'] ?? null;
    $_SESSION['auth_provider'] = $user['auth_provider'];

    // ROLE-BASED REDIRECT
    if ($user['role_id'] == 1) {
        // ADMIN → always go to admin dashboard
        header('Location: ../admin/admin-dashboard.php');
        exit;
    }

    // USER FLOW
    if ($user['auth_provider'] === 'google' && empty($user['username'])) {
        // Google user with incomplete profile
        header('Location: complete_profile.php');
        exit;
    }

    // Regular user with complete profile
    header('Location: ../user/dashboard.php');
    exit;

} else {
    $error = 'Invalid email or password';
}

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SureShop</title>
    
    <!-- CSS Only -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/landing.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Header/Navbar - NO ICONS IN HEADER LINKS -->
<?php include '../view/layouts/header.php'; ?>

<!-- Auth Container -->
<main class="ss-auth-main">
    <div class="auth-container">
        <div class="auth-card">
            <h1>Welcome Back</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="you@example.com">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="register.php">Sign up</a></p>
                <p><a href="forgot-password.php">Forgot password?</a></p>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<?php include '../view/layouts/footer.php'; ?>


</body>
</html>