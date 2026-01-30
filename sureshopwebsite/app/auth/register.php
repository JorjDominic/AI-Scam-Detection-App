<?php
require_once __DIR__ . '/../config/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = 'Email already registered';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (email, password_hash, auth_provider, role_id)
                VALUES (?, ?, 'local', 2)
            ");
            $stmt->execute([$email, $hash]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['email'] = $email;
            $_SESSION['role_id'] = 2;

            header('Location: ../user/dashboard.php');
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
    <title>Register - ScamGuard</title>

    <link rel="stylesheet" href="../assets/css/styles.css">

    <!-- Google Identity -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
        <h1>Create Account</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- LOCAL REGISTER -->
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Sign Up
            </button>
        </form>

        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>

        <hr>

        <!-- GOOGLE REGISTER -->
        <div id="g_id_onload"
             data-client_id="53957248599-0qmgurtc3555tav2jrj1hom7t2o5b77d.apps.googleusercontent.com"
             data-callback="handleGoogleRegister"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-shape="rectangular"
             data-theme="outline"
             data-text="signup_with"
             data-size="large"
             data-width="100%">
        </div>
    </div>
</div>

<script>
function handleGoogleRegister(response) {
    fetch("google_register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ credential: response.credential })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert(data.error || "Google registration failed");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Server error");
    });
}
</script>

</body>
</html>
