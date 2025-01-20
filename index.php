<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require 'config.php';

$admin_message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $db = connect_db();
        $stmt = $db->prepare("SELECT * FROM professors WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);

        if ($user && $password === $user['password']) { 
            $_SESSION['professor'] = $user['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }

    if (isset($_POST['forgot_password_email'])) {
        $email = trim($_POST['email']);
        $admin_message = "If this email is registered, a reset link has been sent to your email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f3f4f7, #dce6f1); 
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1); 
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .card {
            background: linear-gradient(135deg, #a2c2e5, #c8e0f2); 
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 8px 50px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .card h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6f88c1, #8caee6); 
            border: none;
            font-weight: bold;
            padding: 0.75rem;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(37, 117, 252, 0.5);
        }

        .btn-link {
            color: #6f88c1;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 0 10px 10px 0;
        }

        .password-eye {
            cursor: pointer;
        }

        .modal-content {
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
        }

        .alert {
            margin-top: 1rem;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center text-dark mb-4">Attendance Tracker</h1>
    <?php if ($admin_message): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($admin_message) ?></div>
    <?php endif; ?>
    <div class="card mx-auto" style="max-width: 420px;">
        <h2 class="text-center mb-4">Professor Login</h2>
        <form method="POST">
            <div class="form-group mb-3">
                <label for="username" class="form-label text-dark">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label text-dark">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    <span class="input-group-text password-eye" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <button class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                Forgot your password?
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please enter your email address to reset your password:</p>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" name="forgot_password_email" class="btn btn-primary w-100">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const passwordEye = document.querySelector('.password-eye i');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordEye.classList.remove('fa-eye');
            passwordEye.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            passwordEye.classList.remove('fa-eye-slash');
            passwordEye.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>
