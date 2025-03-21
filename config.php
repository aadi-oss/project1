<?php
// Get PostgreSQL connection details from environment variables
$host = getenv('dpg-cveuelvnoe9s73bakqr0-a');       // Database host, like 'dpg-cveuelvnoe9s73bakqr0-a.render.com'
$dbname = getenv('project1_szfd');     // Database name
$user = getenv('aadi');       // Database username
$password = getenv('lv4qCVE5eAvcE6oPwcsnMlZzpYTefxd4'); // Database password

// Create PostgreSQL connection string
$conn_string = "host=$host dbname=$dbname user=$user password=$password";

// Connect to PostgreSQL database
$conn = pg_connect($conn_string);

// Check connection
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
