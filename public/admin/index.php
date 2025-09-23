<?php
session_start();
require_once __DIR__ . '/../../inc/dbconfig.php';

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

// Set variables for includes
$page_title = "Admin Dashboard";
$is_admin_page = true;

// Include header
include __DIR__ . '/../../includes/header.php';

// Include navbar
include __DIR__ . '/../../includes/navbar.php';
?>
    <main>
        <h1>Admin Dashboard</h1>
        <p>Welcome, admin! (Basic dashboard — add edit/create/delete features as you wish.)</p>
    </main>
<?php
// Include footer
include __DIR__ . '/../../includes/footer.php';