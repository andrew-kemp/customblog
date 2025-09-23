<?php
// Header component for all pages
// Should be included after session_start() and any required setup
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($page_title ?? 'Page') ?> - <?= defined('SITE_NAME') ? SITE_NAME : "Site" ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>