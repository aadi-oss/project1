<?php
// Get database connection details from environment variables
$servername = getenv('DB_HOST'); // Render database host
$username = getenv('DB_USER');   // Render database username
$password = getenv('DB_PASSWORD'); // Render database password
$dbname = getenv('DB_NAME');     // Render database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
