<?php
// Get the database URL from the environment variable
$database_url = getenv("postgresql://aadi:lv4qCVE5eAvcE6oPwcsnMlZzpYTefxd4@dpg-cveuelvnoe9s73bakqr0-a/project1_szfd");

if (!$database_url) {
    die("DATABASE_URL not set in environment variables.");
}

// Parse the URL
$db = parse_url($database_url);

$servername = $db["dpg-cveuelvnoe9s73bakqr0-a"];
$username = $db["aadi"];
$password = $db["lv4qCVE5eAvcE6oPwcsnMlZzpYTefxd4"];
$dbname = ltrim($db["project1_szfd"], '/');
$port = $db["5432"] ?? 3306;

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>
