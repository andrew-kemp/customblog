<?php
session_start();
require_once '../inc/dbconfig.php';

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

$page_slug = $_GET['page'] ?? 'home';

// Fetch all pages for the dynamic menu
$pages_result = $mysqli->query("SELECT slug, title FROM pages ORDER BY id ASC");
$menu_pages = [];
while ($row = $pages_result->fetch_assoc()) {
    $menu_pages[] = $row;
}

// Special handling for 'blog'
if ($page_slug === 'blog') {
    $posts_res = $mysqli->query("SELECT * FROM posts ORDER BY created_at DESC");
    $posts = $posts_res->fetch_all(MYSQLI_ASSOC);
    $page_title = "Blog";
} else {
    // Load from pages table, or home content if slug is 'home'
    $stmt = $mysqli->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->bind_param('s', $page_slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    $page_title = $page['title'] ?? ucfirst($page_slug);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($page_title) ?> - <?= defined('SITE_NAME') ? SITE_NAME : "Site" ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="banner-wrapper">
                <img src="/assets/banner.jpg" alt="Banner" class="banner-img">
                <span class="site-title-over-banner"><?= defined('SITE_NAME') ? SITE_NAME : "Site" ?></span>
            </div>
        </div>
    </nav>
    <nav class="public-navbar">
        <div class="public-nav-container">
            <ul class="public-nav-links">
                <li><a href="/">Home</a></li>
                <?php foreach ($menu_pages as $menu_page): ?>
                    <li>
                        <a href="/?page=<?= htmlspecialchars($menu_page['slug']) ?>">
                            <?= htmlspecialchars($menu_page['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <?php if (!empty($_SESSION['is_admin'])): ?>
                    <li><a href="/admin/">Admin</a></li>
                    <li><a href="/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login.php">Login</a></li>
                <?php endif; ?>
                <li><a href="/?page=blog">Blog</a></li>
            </ul>
        </div>
    </nav>
    <main>
        <?php if ($page_slug === 'blog'): ?>
            <h1>Blog</h1>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <article>
                        <h2><?= htmlspecialchars($post['title']) ?></h2>
                        <div><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No blog posts yet.</p>
            <?php endif; ?>
        <?php elseif (!empty($page)): ?>
            <h1><?= htmlspecialchars($page['title']) ?></h1>
            <div><?= nl2br(htmlspecialchars($page['content'])) ?></div>
        <?php else: ?>
            <h1>Page Not Found</h1>
            <p>Sorry, that page does not exist.</p>
        <?php endif; ?>
    </main>
</body>
</html>