<?php
session_start();
require_once 'functions.php';
require_once 'header.php';

$error = "";

if (isset($_SESSION['user'])) {
    // User is already logged in, redirect to members page
    header("Location: members.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    if ($user == "" || $pass == "") {
        $error = "All fields must be filled out.";
    } else {
        $result = queryMysql("SELECT user, pass FROM members WHERE user='$user' AND pass='$pass'");

        if ($result->rowCount() > 0) {
            $_SESSION['user'] = $user;
            header("Location: capture_location.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<form method="POST" action="login.php">
    <label>Username:</label>
    <input type="text" name="user" required>
    <label>Password:</label>
    <input type="password" name="pass" required>
    <input type="submit" value="Login">
</form>
<p><?php echo $error; ?></p>
</body>
</html>
