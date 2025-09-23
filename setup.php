<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

session_start();

if (is_admin_exists()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['admin_user'] ?? '');
    $email = trim($_POST['admin_email'] ?? '');
    $pw = $_POST['admin_pass'] ?? '';
    $pw2 = $_POST['admin_pass2'] ?? '';
    if (!$user || !$email || !$pw || !$pw2) {
        $error = "All fields are required.";
    } elseif ($pw !== $pw2) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email.";
    } else {
        create_admin_user($user, $email, $pw);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Site Setup - My Custom Blog</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="setup-bg">
<div class="setup-container">
    <h1>Site Setup</h1>
    <p class="setup-subtitle">Create your admin account</p>
    <?php if ($error): ?>
        <div class="setup-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="setup-form">
        <label>Admin Username</label>
        <input type="text" name="admin_user" required autocomplete="off">

        <label>Email Address</label>
        <input type="email" name="admin_email" required autocomplete="off">

        <label>Password</label>
        <input type="password" name="admin_pass" required>

        <label>Confirm Password</label>
        <input type="password" name="admin_pass2" required>

        <button type="submit" class="setup-btn">Finish Setup</button>
    </form>
</div>
</body>
</html>