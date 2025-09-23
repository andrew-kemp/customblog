<?php
session_start();
require_once '../../inc/dbconfig.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

// --- First-run setup check ---
$check = $mysqli->query("SELECT COUNT(*) as n FROM users");
$row = $check->fetch_assoc();
if ($row['n'] == 0 && basename($_SERVER['PHP_SELF']) !== 'setup.php') {
    header("Location: /setup.php");
    exit;
}

// --- Auth check ---
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
    <nav class="navbar admin-navbar">
        <div class="nav-container">
            <div class="admin-title">
                <span><?= defined('SITE_NAME') ? SITE_NAME : "Site" ?> - Admin</span>
            </div>
            <ul class="nav-links admin-nav-links">
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