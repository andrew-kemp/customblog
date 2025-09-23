<?php
$sitename = "Andy's Tech Blog"; // Set dynamically after install
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($sitename) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav>
  <div><?= htmlspecialchars($sitename) ?></div>
  <div>
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
    <a href="blog.php">Blog</a>
  </div>
</nav>