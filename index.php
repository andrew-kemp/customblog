<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!is_admin_exists()) {
    header('Location: setup.php');
    exit;
}

// Routing logic
$page = $_GET['page'] ?? 'home';
$slug = $_GET['slug'] ?? null;

include 'templates/header.php';
include 'templates/navbar.php';

// Show banner only on home page if it exists
if ($page === 'home' && file_exists('assets/banner.jpg')) {
    echo '<div class="banner"><img src="assets/banner.jpg" alt="Banner"></div>';
}

if ($page === 'home') {
    $home = get_home_page();
    include 'templates/home.php';

} elseif ($page === 'post' && $slug) {
    $post = get_post_by_slug($slug);
    include 'templates/post.php';

} elseif ($page === 'page' && $slug) {
    $pg = get_page_by_slug($slug);
    include 'templates/page.php';

} elseif ($page === 'blog') {
    $posts = get_all_posts();
    include 'templates/home.php';

} elseif ($page && !in_array($page, ['home', 'post', 'blog'])) {
    $pg = get_page_by_slug($page);
    include 'templates/page.php';

} else {
    echo "<main class='content'><h2>404 Not Found</h2></main>";
}

include 'templates/footer.php';