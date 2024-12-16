<?php
require_once 'functions.php';

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$user = sanitizeString($data['user']);
$latitude = sanitizeString($data['latitude']);
$longitude = sanitizeString($data['longitude']);

// Update location in the database
$query = "UPDATE members SET latitude='$latitude', longitude='$longitude' WHERE user='$user'";
if (queryMysql($query)) {
    echo "Location updated successfully.";
} else {
    echo "Error updating location.";
}
?>
