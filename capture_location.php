<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Capture Location</title>
</head>
<body>
<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;

        // Send location data to the server
        fetch("save_location.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ user: "<?php echo $user; ?>", latitude: lat, longitude: lon })
        }).then(response => response.text())
          .then(data => {
              console.log(data);
              // Redirect to members page after saving location
              window.location.href = "members.php";
          }).catch(error => {
              console.error("Error saving location:", error);
              window.location.href = "members.php";
          });
    }, function (error) {
        console.error("Geolocation error:", error);
        // Redirect even if location isn't shared
        window.location.href = "members.php";
    });
} else {
    alert("Geolocation is not supported by this browser.");
    window.location.href = "members.php";
}
</script>
</body>
</html>
