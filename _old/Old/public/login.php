<?php
session_start();

$pageTitle = "Login";
$currentPage = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        // Replace with your real authentication logic
        if ($username === 'admin' && $password === 'password') {
            $_SESSION['user'] = $username;
            header('Location: /admin.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="login-card">
    <h2>Login</h2>
    <?php if ($error): ?>
        <div class="error-message" style="color: #c00; margin-bottom: 1rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="post" action="login.php" autocomplete="off">
        <input name="username" id="username" type="text" placeholder="Username" required autofocus autocomplete="username">
        <input name="password" id="password" type="password" placeholder="Password" required autocomplete="current-password">
        <button type="submit">Login</button>
    </form>
</div>
<?php
include __DIR__ . '/../includes/footer.php';
?>