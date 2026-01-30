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
<html>
<head>
    <title>Complete Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h2>Complete Your Profile</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password (optional)</label>
                <input type="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Save & Continue
            </button>
        </form>
    </div>
</div>

</body>
</html>
