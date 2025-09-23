<?php if (isset($home)): ?>
  <main class="content">
    <h1><?= htmlspecialchars($home['title']) ?></h1>
    <div><?= $home['content'] ?></div>
    <hr>
    <h2>Latest Blog Posts</h2>
    <ul class="blog-list">
      <?php foreach (get_all_posts() as $post): ?>
        <li>
          <a href="index.php?page=post&slug=<?= htmlspecialchars($post['slug']) ?>">
            <?= htmlspecialchars($post['title']) ?>
          </a>
          <span class="blog-date"><?= date('Y-m-d', strtotime($post['created_at'])) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </main>
<?php elseif (isset($posts)): ?>
  <main class="content">
    <h1>Blog</h1>
    <ul class="blog-list">
      <?php foreach ($posts as $post): ?>
        <li>
          <a href="index.php?page=post&slug=<?= htmlspecialchars($post['slug']) ?>">
            <?= htmlspecialchars($post['title']) ?>
          </a>
          <span class="blog-date"><?= date('Y-m-d', strtotime($post['created_at'])) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </main>
<?php endif; ?>