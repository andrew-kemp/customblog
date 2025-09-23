<?php
$pageTitle = "Login";
$currentPage = '';
include __DIR__ . '/../includes/header.php';
?>
<div class="login-card">
    <h2>Login</h2>
    <form method="post" action="/login.php">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
<?php
include __DIR__ . '/../includes/footer.php';