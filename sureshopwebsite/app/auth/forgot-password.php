<?php
require_once __DIR__ . '/../config/db.php';
session_start();

/* Must be logged in */
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$error = '';

/* Handle form submit */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'] ?? null;

    if (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } else {
        /* Check username uniqueness */
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = 'Username already taken';
        } else {
            /* Build update dynamically */
            $sql = "UPDATE users SET username = ?";
            $params = [$username];

            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $error = 'Password must be at least 6 characters';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql .= ", password_hash = ?";
                    $params[] = $hash;
                }
            }

            if (!$error) {
                $sql .= " WHERE id = ?";
                $params[] = $userId;

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                header("Location: ../user/dashboard.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - SureShop</title>
    
    <!-- CSS Only -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/landing.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include '../view/layouts/header.php'; ?>

<!-- Auth Container -->
<main class="ss-auth-main">
    <div class="auth-container">
        <div class="auth-card">
            <h1>Complete Your Profile</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Choose a username (min. 3 characters)">
                </div>
                
                <div class="form-group">
                    <label for="password">Password (optional)</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Leave blank to keep current password">
                    <small>If you want to change your password, enter a new one here (min. 6 characters)</small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle"></i> Save & Continue
                </button>
            </form>
            
            <div class="auth-links">
                <p><a href="../user/dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a></p>
            </div>
        </div>
    </div>
</main>

<?php include '../view/layouts/footer.php'; ?>

</body>
</html>