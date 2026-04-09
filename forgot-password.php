<?php
require_once 'config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        // Generate token
        $token = bin2hex(random_bytes(50));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);
        
        // In a real application, send email here
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password.php?token=" . $token;
        
        $message = "Password reset link has been sent to your email. (Demo: <a href='$reset_link'>Click here to reset</a>)";
    } else {
        $error = "No account found with this email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="theme-color" content="#0077cc">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Healthcare Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .forgot-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            width: 450px;
            max-width: 90%;
        }
        .forgot-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .forgot-header h1 {
            color: #0077cc;
            margin-bottom: 10px;
        }
        .forgot-header i {
            font-size: 2.5em;
            color: #0077cc;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .reset-button {
            width: 100%;
            padding: 14px;
            background: #0077cc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .reset-button:hover {
            background: #005599;
        }
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-login a {
            color: #0077cc;
            font-weight: bold;
        }
        .message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <i class="fas fa-key"></i>
            <h1>Forgot Password</h1>
            <p>Enter your email address to receive a password reset link</p>
        </div>
        
        <?php if($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your registered email" required>
            </div>
            <button type="submit" class="reset-button">Send Reset Link</button>
        </form>
        
        <div class="back-to-login">
            <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
    </div>
</body>
</html>