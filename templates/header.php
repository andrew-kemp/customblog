<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$site_title = get_site_title();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($site_title) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="main-wrapper">