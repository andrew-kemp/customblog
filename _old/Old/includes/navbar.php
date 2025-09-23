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
        <?php foreach ($menuPages as $page): ?>
            <li>
                <a href="/?page=<?= urlencode($page['slug']) ?>"
                   class="<?= $currentPage === $page['slug'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($page['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
        <li>
            <a href="/?page=blog" class="<?= $currentPage === 'blog' ? 'active' : '' ?>">Blog</a>
        </li>
    </ul>
</nav>