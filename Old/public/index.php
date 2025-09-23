<?php include '../inc/header.php'; ?>
<div class="container">
  <h1>Welcome to <?= htmlspecialchars($sitename ?? "Your Site") ?>!</h1>
  <img src="assets/banner.jpg" alt="Banner" style="width:100%;border-radius:8px;">
  <p>This is your new modern, custom CMS. Edit this front page in <code>public/index.php</code>.</p>
</div>
<?php include '../inc/footer.php'; ?>