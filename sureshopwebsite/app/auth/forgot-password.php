<?php
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        $message = '<div class="alert alert-error">Email is required</div>';
    } else {
        $message = '<div class="alert alert-success">Password reset link sent to ' . htmlspecialchars($email) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ScamGuard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">ScamGuard</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
            </div>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card">
            <h1>Forgot Password</h1>
            <?php echo $message; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Enter your email address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
            </form>

            <div class="auth-links">
                <p><a href="login.php">Back to login</a></p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>