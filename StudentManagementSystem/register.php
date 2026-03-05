<?php
include_once 'User.php';

$db = new mysqli("localhost", "root", "", "my_database");
$userObj = new User($db);
$message = "";

if (isset($_POST['register'])) {
    if ($userObj->register($_POST['username'], $_POST['password'])) {
        $message = "<p style='color: green;'>Registration successful! <a href='login.php'>Login here</a></p>";
    } else {
        $message = "<p style='color: red;'>Registration failed. Username might be taken.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Student System</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f7f6; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 350px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .link { display: block; text-align: center; margin-top: 15px; font-size: 14px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="text-align: center;">Create Account</h2>
        <?php echo $message; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="password" name="password" placeholder="Choose Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <a href="login.php" class="link">Already have an account? Login</a>
    </div>
</body>
</html>