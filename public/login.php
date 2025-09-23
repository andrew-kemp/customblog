<?php
session_start();
require_once '../inc/dbconfig.php';

$pageTitle = "Login";
$currentPage = '';
$error = '';

// Check if user is already logged in
if (!empty($_SESSION['user_id']) && !empty($_SESSION['is_admin'])) {
    header('Location: /admin/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        // Connect to database for proper authentication
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_errno) {
            $error = 'Database connection failed. Please try again later.';
        } else {
            $stmt = $mysqli->prepare("SELECT user_id, username, password_hash, is_admin FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    
                    header('Location: /admin/');
                    exit;
                } else {
                    $error = 'Invalid username or password.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
            $stmt->close();
            $mysqli->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
    <style>
        body {
            background: linear-gradient(120deg, #e0e7ff 0%, #fff 100%);
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .login-card {
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
        .login-card h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            color: #2d3748;
            text-align: center;
        }
        .login-card p {
            color: #6366f1;
            font-weight: 500;
            margin-bottom: 1.3rem;
            text-align: center;
        }
        .login-card form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }
        .login-card label {
            font-weight: 500;
            color: #374151;
        }
        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.2rem;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            transition: border .2s;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .login-card input:focus {
            border: 1.5px solid #6366f1;
            outline: none;
        }
        .login-card button {
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
        .login-card button:hover {
            background: linear-gradient(90deg, #4f46e5 60%, #6366f1 100%);
        }
        .login-card .error {
            color: #dc2626;
            background: #fee2e2;
            border-radius: 5px;
            padding: 0.7em 1em;
            margin-bottom: 0.8em;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: #6366f1;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link a:hover {
            color: #4f46e5;
        }
        @media (max-width: 500px) {
            .login-card {
                padding: 1.5rem;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>🔐 Admin Login</h1>
        <p>Welcome back! Please sign in to access the admin panel.</p>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post" action="login.php" autocomplete="off">
            <label for="username">
                Username:
                <input name="username" id="username" type="text" required autofocus autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </label>
            <label for="password">
                Password:
                <input name="password" id="password" type="password" required autocomplete="current-password">
            </label>
            <button type="submit">✨ Sign In</button>
        </form>
        
        <div class="back-link">
            <a href="/">← Back to Home</a>
        </div>
    </div>
</body>
</html>