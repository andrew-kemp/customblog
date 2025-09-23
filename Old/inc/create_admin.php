<?php
// Usage: php create_admin.php username email password
if ($argc != 4) {
    exit("Usage: php create_admin.php username email password\n");
}
require_once 'dbconfig.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    exit("Failed to connect: " . $mysqli->connect_error . "\n");
}
$username = $mysqli->real_escape_string($argv[1]);
$email = $mysqli->real_escape_string($argv[2]);
$password_hash = password_hash($argv[3], PASSWORD_DEFAULT);
// Create user
$mysqli->query("INSERT INTO users (username, email, password_hash, is_admin) VALUES ('$username', '$email', '$password_hash', 1)");
if ($mysqli->error) exit("User creation failed: " . $mysqli->error . "\n");

// Optionally create sample post for this admin user
$admin_id = $mysqli->insert_id;
$title = 'Hello World!';
$slug = 'hello-world';
$content = 'Welcome to your first blog post. Edit or delete this post in the admin panel.';
$stmt = $mysqli->prepare("INSERT INTO posts (title, slug, content, author_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $title, $slug, $content, $admin_id);
$stmt->execute();
$stmt->close();

echo "Admin user and welcome post created\n";
$mysqli->close();
?>
