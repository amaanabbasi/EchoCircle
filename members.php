<?php // Example 09: members.php
require_once 'header.php';

if (!$loggedin) die("</div></body></html>");

if (isset($_GET['view'])) {
    $view = sanitizeString($_GET['view']);
    
    if ($view == $user) $name = "Your";
    else $name = "$view's";
    
    echo "<h3>$name Profile</h3>";
    showProfile($view);
    echo "<a data-role='button' data-transition='slide'
          href='messages.php?view=$view&r=$randstr'>View $name messages</a>";
    die("</div></body></html>");
}

if (isset($_GET['add'])) {
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends
      WHERE user='$add' AND friend='$user'");
    if ($result->rowCount() == 0)
        queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
} elseif (isset($_GET['remove'])) {
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends
      WHERE user='$remove' AND friend='$user'");
}

// Fetch all members with location data
$result = queryMysql("SELECT user, latitude, longitude FROM members ORDER BY user");
$num = $result->rowCount();

while ($row = $result->fetch()) {
    if ($row['user'] == $user) continue;

    $username = htmlspecialchars($row['user']);
    $latitude = htmlspecialchars($row['latitude']);
    $longitude = htmlspecialchars($row['longitude']);
    
    echo "<li><a data-transition='slide' href='members.php?view=$username&$randstr'>$username</a>";
    $follow = "follow";

    $result1 = queryMysql("SELECT * FROM friends WHERE user='" . $row['user'] . "' AND friend='$user'");
    $t1 = $result1->rowCount();
    
    $result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'");
    $t2 = $result1->rowCount();

    if (($t1 + $t2) > 1) echo " &harr; is a mutual friend";
    elseif ($t1) echo " &larr; you are following";
    elseif ($t2) {
        echo " &rarr; is following you";
        $follow = "recip";
    }
    
    if (!$t1) echo " [<a data-transition='slide'
      href='members.php?add=$username&r=$randstr'>$follow</a>]";
    else echo " [<a data-transition='slide'
      href='members.php?remove=$username&r=$randstr'>drop</a>]";

    // Display location if available
    if (!empty($latitude) && !empty($longitude)) {
        echo " <br><small>Location: 
            <a href='https://www.google.com/maps?q=$latitude,$longitude' target='_blank'>
            View on Google Maps</a></small>";
    } else {
        echo " <br><small>Location: Not Available</small>";
    }

    echo "</li>";
}
?>
    </ul></div>
</body>
</html>
