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
        // 1. Get user by email (LOCAL users only)
        $stmt = $pdo->prepare("
            SELECT id, email, password_hash, auth_provider, role_id
            FROM users
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = 'Invalid email or password';
        }
        elseif ($user['auth_provider'] === 'google' && empty($user['password_hash'])) {
            $error = 'This account uses Google Sign-In. Please sign in with Google or set a password.';
        }
        elseif (!password_verify($password, $user['password_hash'])) {
            $error = 'Invalid email or password';
        }
        else {
            // 2. Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_id'] = $user['role_id'];
            // 3. Role-based redirect
            if ($user['role_id'] == 1) {
                // Admin
                header('Location: ../admin/admin-dashboard.php');
            } else {
                // Regular user
                header('Location: ../user/dashboard.php');
            }
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ScamGuard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <div class="logo">ScamGuard</div>
        <div class="nav-links">
            <a href="../index.php">Home</a>
        </div>
    </div>
</nav>

<div class="auth-container">
    <div class="auth-card">
        <h1>Login</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Login
            </button>
        </form>

        <div class="auth-links">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
            <p><a href="forgot-password.php">Forgot password?</a></p>
        </div>
    </div>
</div>

</body>
</html>