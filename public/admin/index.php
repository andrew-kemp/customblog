<?php
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - <?= defined('SITE_NAME') ? SITE_NAME : "Site" ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="banner-wrapper">
                <img src="/assets/banner.jpg" alt="Banner" class="banner-img">
                <span class="site-title-over-banner"><?= defined('SITE_NAME') ? SITE_NAME : "Site" ?></span>
            </div>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/admin/">Dashboard</a></li>
                <li><a href="/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <main>
        <h1>Admin Dashboard</h1>
        <p>Welcome, admin! (Basic dashboard — add edit/create/delete features as you wish.)</p>
    </main>
</body>
</html>