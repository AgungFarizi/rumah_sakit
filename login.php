<?php
require 'config.php';

if (isset($_SESSION['login'])) header('Location: index.php');

if ($_POST) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    if ($user == 'admin' && $pass == 'admin') {
        $_SESSION['login'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Rumah Sakit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container login-box">
    <h2>🏥 Rumah Sakit Sehat</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="👤 Username" required>
        <input type="password" name="password" placeholder="🔒 Password" required>
        <button type="submit" style="width:100%; margin-top:10px;">🚀 LOGIN</button>
    </form>
    <div class="info-login">
        <p>👤 <b>Username:</b> admin</p>
        <p>🔒 <b>Password:</b> admin</p>
    </div>
</div>
</body>
</html>