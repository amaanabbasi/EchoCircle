<?php
require_once 'header.php';
$error = $user = $pass = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user']) && isset($_POST['pass'])) {
        $user = sanitizeString($_POST['user']);
        $pass = sanitizeString($_POST['pass']);

        if ($user == "" || $pass == "") {
            $error = "All fields must be filled out.";
        } else {
            $result = queryMysql("SELECT * FROM members WHERE user='$user' AND pass='$pass'");

            if ($result->rowCount() > 0) {
                // Start the session and store the logged-in user's information
                session_start();
                $_SESSION['user'] = $user;

                // Generate a random string for the `r` parameter
                $randstr = bin2hex(random_bytes(8));

                // Redirect to the members page with `view` and `r` parameters
                header("Location: members.php?view=$user&r=$randstr");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        }
    }
}

echo <<<_END
<form method='POST' action='login.php'>
    <div><label>Username:</label><input type='text' name='user' required></div>
    <div><label>Password:</label><input type='password' name='pass' required></div>
    <div><input type='submit' value='Login'></div>
    <p>$error</p>
</form>
_END;
?>
