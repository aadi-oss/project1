<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'bus_ticketing_system');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
