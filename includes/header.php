<?php
if (!isset($pageTitle)) $pageTitle = "My Blog";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main>