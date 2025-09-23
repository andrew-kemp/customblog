<?php
// This file should be included after database connection and page data setup
// Expected variables:
// - $page_title: Title for the page
// - $menu_pages: Array of menu pages from database
// - $is_admin_page: Boolean indicating if this is an admin page (optional)

if (!isset($page_title)) {
    $page_title = 'Page';
}

$site_name = defined('SITE_NAME') ? SITE_NAME : 'Site';
$is_admin_page = $is_admin_page ?? false;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($page_title) ?> - <?= htmlspecialchars($site_name) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php if ($is_admin_page): ?>
        <nav class="navbar admin-navbar">
            <div class="nav-container">
                <div class="admin-title">
                    <span><?= htmlspecialchars($site_name) ?> - Admin</span>
                </div>
                <ul class="nav-links admin-nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/admin/">Dashboard</a></li>
                    <li><a href="/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    <?php else: ?>
        <nav class="navbar">
            <div class="nav-container">
                <div class="banner-wrapper">
                    <img src="/assets/banner.jpg" alt="Banner" class="banner-img">
                    <span class="site-title-over-banner"><?= htmlspecialchars($site_name) ?></span>
                </div>
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <?php if (isset($menu_pages) && is_array($menu_pages)): ?>
                        <?php foreach ($menu_pages as $menu_page): ?>
                            <li>
                                <a href="/?page=<?= htmlspecialchars($menu_page['slug']) ?>">
                                    <?= htmlspecialchars($menu_page['title']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
    <?php endif; ?>
    <main>