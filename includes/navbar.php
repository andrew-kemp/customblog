<?php
// Example stub for dynamic menu items
function getMenuPages() {
    // Replace this with DB fetch for real use
    return [
        ['slug' => '', 'title' => 'Home'],
        ['slug' => 'about', 'title' => 'About'],
        ['slug' => 'contact', 'title' => 'Contact'],
    ];
}
$menuPages = getMenuPages();
$currentPage = isset($currentPage) ? $currentPage : '';
?>
<nav class="navbar">
    <ul>
        <?php foreach ($menuPages as $menuPage): ?>
            <li>
                <a href="/?page=<?= urlencode($menuPage['slug']) ?>"
                   class="<?= $currentPage === $menuPage['slug'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($menuPage['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
        <li>
            <a href="/?page=blog" class="<?= $currentPage === 'blog' ? 'active' : '' ?>">Blog</a>
        </li>
    </ul>
</nav>