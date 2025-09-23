<?php $site_title = get_site_title(); ?>
<nav class="navbar">
  <div class="navbar-left">
    <a href="index.php?page=home" class="site-title"><?= htmlspecialchars($site_title) ?></a>
  </div>
  <div class="navbar-right">
    <a href="index.php?page=home">Home</a>
    <a href="index.php?page=blog">Blog</a>
    <?php foreach(get_all_pages() as $pg): ?>
      <a href="index.php?page=<?= htmlspecialchars($pg['slug']) ?>"><?= htmlspecialchars($pg['title']) ?></a>
    <?php endforeach; ?>
    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="/admin/login.php">Login</a>
    <?php else: ?>
      <a href="/admin/">Admin</a>
      <a href="/admin/logout.php">Logout</a>
    <?php endif; ?>
  </div>
</nav>