<?php
session_start();
require_once '../inc/dbconfig.php';

$page_slug = $_GET['page'] ?? 'home';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

$pages_result = $mysqli->query("SELECT slug, title FROM pages ORDER BY id ASC");
$menu_pages = [];
while ($row = $pages_result->fetch_assoc()) {
    $menu_pages[] = $row;
}

if ($page_slug === 'blog') {
    $posts_res = $mysqli->query("SELECT * FROM posts ORDER BY created_at DESC");
    $posts = $posts_res->fetch_all(MYSQLI_ASSOC);
    $page_title = "Blog";
} else {
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
    <title><?= htmlspecialchars($page_title) ?> - <?= constant('SITE_NAME') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="banner-wrapper">
                <img src="/assets/banner.jpg" alt="Banner" class="banner-img">
                <span class="site-title-over-banner"><?= constant('SITE_NAME') ?></span>
            </div>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <?php foreach ($menu_pages as $menu_page): ?>
                    <li>
                        <a href="/?page=<?= htmlspecialchars($menu_page['slug']) ?>">
                            <?= htmlspecialchars($menu_page['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><a href="/?page=blog">Blog</a></li>
                <?php if (!empty($_SESSION['is_admin'])): ?>
                    <li><a href="/admin/">Admin</a></li>
                    <li><a href="/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main>
        <?php if ($page_slug === 'blog'): ?>
            <h1>Blog</h1>
            <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    <article>
                        <h2><?= htmlspecialchars($post['title']) ?></h2>
                        <div><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No blog posts yet.</p>
            <?php endif; ?>
        <?php elseif ($page): ?>
            <h1><?= htmlspecialchars($page['title']) ?></h1>
            <div><?= nl2br(htmlspecialchars($page['content'])) ?></div>
        <?php else: ?>
            <h1>Page Not Found</h1>
            <p>Sorry, that page does not exist.</p>
        <?php endif; ?>
    </main>
</body>
</html>