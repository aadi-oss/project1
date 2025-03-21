<?php
// Get PostgreSQL connection details from environment variables
$host = getenv('dpg-cveuelvnoe9s73bakqr0-a');       // Database host, like 'dpg-cveuelvnoe9s73bakqr0-a.render.com'
$dbname = getenv('project1_szfd');     // Database name
$user = getenv('aadi');       // Database username
$password = getenv('lv4qCVE5eAvcE6oPwcsnMlZzpYTefxd4'); // Database password

/$dsn = "pgsql:host=$host;dbname=$dbname";

// Create a PDO instance
try {
    $conn = new PDO($dsn, $user, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the PostgreSQL database successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
