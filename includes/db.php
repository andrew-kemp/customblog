<?php
// Load DB credentials from config.php in the project root
require_once __DIR__ . '/../config.php';

// Create the mysqli connection object
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection and fail gracefully if there's an error
if ($conn->connect_errno) {
    die("Database connection failed: " . $conn->connect_error);
}