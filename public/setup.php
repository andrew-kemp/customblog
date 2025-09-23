<?php
require_once '../inc/dbconfig.php';
session_start();

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$error = '';
$success = false;

// Check if an admin already exists
$res = $mysqli->query("SELECT COUNT(*) as n FROM users");
$row = $res->fetch_assoc();
if ($row['n'] > 0) {
    header("Location: /");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$username || !$email || !$password) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password_hash, is_admin) VALUES (?, ?, ?, 1)");
        $stmt->bind_param('sss', $username, $email, $password_hash);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $mysqli->insert_id;
            $_SESSION['is_admin'] = 1;
            header("Location: /admin/");
            exit;
        } else {
            $error = "Failed to create admin user: " . $mysqli->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Site Setup - Create Admin</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        body {
            background: linear-gradient(120deg, #e0e7ff 0%, #fff 100%);
            min-height: 100vh;
        }
        .setup-card {
            background: #fff;
            max-width: 380px;
            margin: 60px auto;
            box-shadow: 0 6px 32px rgba(31,41,55,.18), 0 1.5px 4px rgba(0,0,0,.07);
            border-radius: 18px;
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .setup-card h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            color: #2d3748;
        }
        .setup-card form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }
        .setup-card label {
            font-weight: 500;
            color: #374151;
        }
        .setup-card input[type="text"],
        .setup-card input[type="email"],
        .setup-card input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.2rem;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            transition: border .2s;
            font-size: 1rem;
        }
        .setup-card input:focus {
            border: 1.5px solid #6366f1;
            outline: none;
        }
        .setup-card button {
            padding: 0.75rem 0;
            background: linear-gradient(90deg, #6366f1 60%, #818cf8 100%);
            color: white;
            font-weight: 700;
            border: none;
            border-radius: 7px;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 0.5rem;
            box-shadow: 0 2px 8px rgba(99,102,241,0.07);
            transition: background 0.2s;
        }
        .setup-card button:hover {
            background: linear-gradient(90deg, #4f46e5 60%, #6366f1 100%);
        }
        .setup-card .error {
            color: #dc2626;
            background: #fee2e2;
            border-radius: 5px;
            padding: 0.7em 1em;
            margin-bottom: 0.8em;
            width: 100%;
            text-align: center;
        }
        @media (max-width: 500px) {
            .setup-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <h1>👋 Site Setup</h1>
        <p style="color:#6366f1; font-weight:500; margin-bottom:1.3rem;">
            Create your first admin account to get started!
        </p>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label>
                Username:
                <input name="username" type="text" required autocomplete="off">
            </label>
            <label>
                Email:
                <input type="email" name="email" required autocomplete="off">
            </label>
            <label>
                Password:
                <input type="password" name="password" required autocomplete="off">
            </label>
            <label>
                Confirm Password:
                <input type="password" name="confirm" required autocomplete="off">
            </label>
            <button type="submit">✨ Create Admin Account</button>
        </form>
    </div>
</body>
</html>