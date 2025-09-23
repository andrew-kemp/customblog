<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - My Custom Blog</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="main-wrapper">
        <nav class="navbar">
            <div class="navbar-left">
                <a href="/admin/" class="site-title">Admin Dashboard</a>
            </div>
            <div class="navbar-right">
                <a href="/">View Site</a>
                <a href="/admin/">Dashboard</a>
                <a href="/admin/logout.php">Logout</a>
            </div>
        </nav>