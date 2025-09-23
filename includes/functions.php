<?php

function is_admin_exists() {
    global $conn;
    $res = $conn->query("SELECT COUNT(*) FROM users");
    $row = $res->fetch_row();
    return $row && $row[0] > 0;
}

function create_admin_user($user, $email, $pw) {
    global $conn;
    $hash = password_hash($pw, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $email, $hash);
    $stmt->execute();
}

// Pages
function get_home_page() {
    global $conn;
    $result = $conn->query("SELECT * FROM pages WHERE is_home=1 LIMIT 1");
    return $result ? $result->fetch_assoc() : null;
}
function get_page_by_slug($slug) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}
function get_all_pages() {
    global $conn;
    $result = $conn->query("SELECT * FROM pages WHERE slug NOT IN ('home','blog') ORDER BY id");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Posts
function get_all_posts() {
    global $conn;
    $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
function get_post_by_slug($slug) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM posts WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}