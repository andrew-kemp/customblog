<?php
require_once __DIR__ . '/header.php';
if (empty($_SESSION['user_id'])) {
    header("Location: /admin/login.php");
    exit;
}

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_title = trim($_POST['site_title']);
    $stmt = $conn->prepare("UPDATE settings SET value=? WHERE name='site_title'");
    $stmt->bind_param('s', $new_title);
    $stmt->execute();
    $success = "Settings updated!";
}
$current_title = get_site_title();
?>
<main class="content">
    <h1>Site Settings</h1>
    <?php if ($success): ?>
        <div class="setup-error" style="background:#d8f5d0;color:#147a21"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="post">
        <label for="site_title">Site Name:</label>
        <input type="text" name="site_title" id="site_title" value="<?= htmlspecialchars($current_title) ?>" required>
        <button type="submit" class="setup-btn">Save</button>
    </form>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>