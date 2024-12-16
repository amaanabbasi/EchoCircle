<?php
session_start();
require_once 'functions.php';
require_once 'header.php';

if (!isset($_SESSION['user'])) {
    die("Please log in first. <a href='login.php'>Login</a>");
}

$user = $_SESSION['user'];

echo "<h1>Welcome, $user!</h1>";
echo "<p>This is your members area.</p>";

// Display a list of members
$result = queryMysql("SELECT user FROM members ORDER BY user");
if ($result->rowCount() > 0) {
    echo "<h3>Member List:</h3><ul>";
    while ($row = $result->fetch()) {
        echo "<li>" . $row['user'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No members found.</p>";
}
?>
