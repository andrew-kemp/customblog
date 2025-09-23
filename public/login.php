<?php
session_start();
require_once '../inc/dbconfig.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $username = $mysqli->real_escape_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = $mysqli->query("SELECT * FROM users WHERE username = '$username' LIMIT 1");
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header('Location: /admin/');
            exit;
        }
    }
    $error = "Invalid credentials.";
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Login</title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="login-container">
<h2>Admin Login</h2>
<?php if ($error): ?>
<p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post">
    <label>Username: <input name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
</div>
</body>
</html>