<?php
if ($argc < 6) exit("Usage: php create_admin.php user email pass dbname dbuser dbpass\n");
$user = $argv[1];
$email = $argv[2];
$pass = password_hash($argv[3], PASSWORD_BCRYPT);
$dbname = $argv[4];
$dbuser = $argv[5];
$dbpass = $argv[6];

$db = new mysqli('localhost', $dbuser, $dbpass, $dbname);
if ($db->connect_error) exit("DB error: " . $db->connect_error);

$stmt = $db->prepare("INSERT INTO users (username, email, password_hash, is_admin) VALUES (?, ?, ?, 1)");
$stmt->bind_param('sss', $user, $email, $pass);
$stmt->execute();
$stmt->close();
$db->close();