<?php
session_start();
require_once __DIR__ . '/../inc/dbconfig.php';
require_once __DIR__ . '/../includes/functions.php';

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
$menu_pages = getMenuPages($mysqli);

// Special handling for 'blog'
if ($page_slug === 'blog') {
    $posts = getAllPosts($mysqli);
    $page_title = "Blog";
} else {
    // Load from pages table, or home content if slug is 'home'  
    $page = getPageBySlug($mysqli, $page_slug);
    $page_title = $page['title'] ?? ucfirst($page_slug);
}
// Include header
include __DIR__ . '/../includes/header.php';

// Include navbar
include __DIR__ . '/../includes/navbar.php';
?>
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
<?php
// Include footer
include __DIR__ . '/../includes/footer.php';