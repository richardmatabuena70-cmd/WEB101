<?php
session_start();
include_once 'User.php';

$db = new mysqli("localhost", "root", "", "my_database");
$userObj = new User($db);
$error = "";

if (isset($_POST['login'])) {
    $auth = $userObj->login($_POST['username'], $_POST['password']);
    if ($auth) {
        $_SESSION['user_id'] = $auth['user_id'];
        $_SESSION['username'] = $auth['username'];
        header("Location: index.php");
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Student System</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f7f6; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #333; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        
        /* Primary Login Button */
        .btn-login { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-bottom: 10px; }
        .btn-login:hover { background: #0056b3; }
        
        /* Secondary Register Button */
        .btn-register { 
            display: block; 
            width: 100%; 
            padding: 10px; 
            background: #6c757d; 
            color: white; 
            text-align: center; 
            text-decoration: none; 
            border-radius: 4px; 
            font-size: 16px; 
            box-sizing: border-box;
        }
        .btn-register:hover { background: #5a6268; }
        
        .error { color: #dc3545; font-size: 14px; text-align: center; margin-bottom: 10px; }
        .divider { text-align: center; margin: 10px 0; color: #888; font-size: 12px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>System Login</h2>
        <?php if($error): ?> <p class="error"><?php echo $error; ?></p> <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" name="login" class="btn-login">Login</button>
            
            <div class="divider">OR</div>
            
            <a href="register.php" class="btn-register">Register New Account</a>
        </form>
    </div>
</body>
</html>