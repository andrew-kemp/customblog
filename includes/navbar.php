<?php
// Navbar component - supports both regular and admin layouts
// Variables expected: $is_admin_page (boolean), $menu_pages (array), $_SESSION

$navbar_class = isset($is_admin_page) && $is_admin_page ? "navbar admin-navbar" : "navbar";
?>
<nav class="<?= htmlspecialchars($navbar_class) ?>">
    <div class="nav-container">
        <?php if (isset($is_admin_page) && $is_admin_page): ?>
            <!-- Admin navbar layout -->
            <div class="admin-title">
                <span><?= defined('SITE_NAME') ? SITE_NAME : "Site" ?> - Admin</span>
            </div>
            <ul class="nav-links admin-nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/admin/">Dashboard</a></li>
                <li><a href="/logout.php">Logout</a></li>
            </ul>
        <?php else: ?>
            <!-- Regular navbar layout with banner -->
            <div class="banner-wrapper">
                <img src="/assets/banner.jpg" alt="Banner" class="banner-img">
                <span class="site-title-over-banner"><?= defined('SITE_NAME') ? SITE_NAME : "Site" ?></span>
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
        <?php endif; ?>
    </div>
</nav>