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
$password = password_hash($argv[3], PASSWORD_DEFAULT);
// Create user
$mysqli->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
if ($mysqli->error) exit("User creation failed: " . $mysqli->error . "\n");

// Optionally create sample post for this admin user
$admin_id = $mysqli->insert_id;
$title = 'Welcome!';
$content = 'This is your first post!';
$stmt = $mysqli->prepare("INSERT INTO posts (title, content, author_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $title, $content, $admin_id);
$stmt->execute();
$stmt->close();

echo "Admin user and welcome post created\n";
$mysqli->close();
?>
