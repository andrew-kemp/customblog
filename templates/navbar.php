<nav class="navbar">
  <div class="navbar-left">
    <a href="index.php?page=home" class="site-title">My Custom Blog</a>
  </div>
  <div class="navbar-right">
    <a href="index.php?page=home">Home</a>
    <a href="index.php?page=blog">Blog</a>
    <?php foreach(get_all_pages() as $pg): ?>
      <a href="index.php?page=<?= htmlspecialchars($pg['slug']) ?>"><?= htmlspecialchars($pg['title']) ?></a>
    <?php endforeach; ?>
  </div>
</nav>