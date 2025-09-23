<?php
require_once __DIR__ . '/header.php';

if (!empty($_SESSION['user_id'])) {
    header("Location: /admin/");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: /admin/");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<main class="content">
    <h1>Admin Login</h1>
    <?php if ($error): ?>
        <div class="setup-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="setup-form">
            <label for="username">Username or Email:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="setup-btn">Login</button>
        </div>
    </form>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>