<main class="content">
  <?php if (empty($pg)): ?>
    <h2>Page Not Found</h2>
  <?php else: ?>
    <h1><?= htmlspecialchars($pg['title']) ?></h1>
    <div><?= $pg['content'] ?></div>
  <?php endif; ?>
</main>