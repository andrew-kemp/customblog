<?php
require_once '../inc/dbconfig.php';
session_start();

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$error = '';
$success = false;

// Check if an admin already exists
$res = $mysqli->query("SELECT COUNT(*) as n FROM users");
$row = $res->fetch_assoc();
if ($row['n'] > 0) {
    header("Location: /");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$username || !$email || !$password) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password_hash, is_admin) VALUES (?, ?, ?, 1)");
        $stmt->bind_param('sss', $username, $email, $password_hash);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $mysqli->insert_id;
            $_SESSION['is_admin'] = 1;
            header("Location: /admin/");
            exit;
        } else {
            $error = "Failed to create admin user: " . $mysqli->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Site Setup - Create Admin</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <main>
        <h1>Site Setup: Create Admin User</h1>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label>Username:<br><input name="username" required></label><br>
            <label>Email:<br><input type="email" name="email" required></label><br>
            <label>Password:<br><input type="password" name="password" required></label><br>
            <label>Confirm Password:<br><input type="password" name="confirm" required></label><br>
            <button type="submit">Create Admin Account</button>
        </form>
    </main>
</body>
</html>