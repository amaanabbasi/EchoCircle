<?php
// Example: profile.php
require_once 'header.php';
require_once 'functions.php';

if (!$loggedin) die("</div></body></html>");

// Display user profile
echo "<h3>Your Profile</h3>";

// Fetch current profile data
$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

// Process profile form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['text'])) {
        $text = sanitizeString($_POST['text']);
        $text = preg_replace('/\s\s+/', ' ', $text); // Remove extra whitespace

        // Insert or update profile text
        if ($result->rowCount()) {
            queryMysql("UPDATE profiles SET text='$text' WHERE user='$user'");
        } else {
            queryMysql("INSERT INTO profiles (user, text) VALUES ('$user', '$text')");
        }
    }

    // Handle file uploads
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $saveto = "$user.jpg";
        move_uploaded_file($_FILES['image']['tmp_name'], $saveto);

        $typeok = TRUE;

        // Validate file type and handle processing
        switch ($_FILES['image']['type']) {
            case "image/gif":
                if (function_exists('imagecreatefromgif')) {
                    $src = imagecreatefromgif($saveto);
                } else {
                    $typeok = FALSE;
                }
                break;
            case "image/jpeg":
            case "image/pjpeg":
                if (function_exists('imagecreatefromjpeg')) {
                    $src = imagecreatefromjpeg($saveto);
                } else {
                    echo "JPEG support is unavailable. Uploading without resizing.<br>";
                    $typeok = FALSE;
                }
                break;
            case "image/png":
                if (function_exists('imagecreatefrompng')) {
                    $src = imagecreatefrompng($saveto);
                } else {
                    $typeok = FALSE;
                }
                break;
            default:
                $typeok = FALSE;
                break;
        }

        // If image processing is supported, resize the image
        if ($typeok) {
            list($w, $h) = getimagesize($saveto);
            $max = 100; // Maximum dimension
            $tw = $w;
            $th = $h;

            if ($w > $h && $max < $w) {
                $th = $max / $w * $h;
                $tw = $max;
            } elseif ($h > $w && $max < $h) {
                $tw = $max / $h * $w;
                $th = $max;
            } elseif ($max < $w) {
                $tw = $th = $max;
            }

            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imagejpeg($tmp, $saveto); // Save resized image
            imagedestroy($tmp);
            imagedestroy($src);
        }
    }
} else {
    // Load existing profile data
    if ($result->rowCount()) {
        $row = $result->fetch();
        $text = stripslashes($row['text']);
    } else {
        $text = "";
    }
}

// Sanitize output text
$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

// Display profile form
echo <<<_END
<form data-ajax='false' method='post' action='profile.php' enctype='multipart/form-data'>
    <h3>Enter or edit your details and/or upload an image</h3>
    <textarea name='text'>$text</textarea><br>
    Image: <input type='file' name='image' size='14'><br>
    <input type='submit' value='Save Profile'>
</form>
_END;

// Show the updated profile
showProfile($user);

echo "</div><br></body></html>";
?>
