<?php
require_once __DIR__ . '/header.php';

if (empty($_SESSION['user_id'])) {
    header("Location: /admin/login.php");
    exit;
}
?>
<main class="content">
    <h1>Welcome to the Admin Dashboard</h1>
    <p>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
    <ul>
        <li><a href="/admin/">Dashboard</a></li>
        <!-- Add more admin links here (manage posts, pages, users, etc.) -->
        <li><a href="/admin/logout.php">Logout</a></li>
    </ul>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>