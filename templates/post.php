<main class="content">
  <?php if (!$post): ?>
    <h2>Post Not Found</h2>
  <?php else: ?>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <div class="blog-meta">
      Posted on <?=date('Y-m-d', strtotime($post['created_at']))?>
    </div>
    <div class="blog-body">
      <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>
    <p><a href="index.php?page=blog">&larr; Back to blog</a></p>
  <?php endif; ?>
</main>