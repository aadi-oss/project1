<?php
session_start();
@include 'config.php'; // Include the database configuration

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch the user data from the database
$user_id = $_SESSION['user_id'];

// Use prepared statements for security
$query = $conn->prepare("SELECT id, name, email, password, user_type, created_at FROM user WHERE id = ?");
$query->bind_param("i", $user_id); // Bind user_id as an integer
$query->execute();
$result = $query->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // If no user is found, redirect to the login page
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .profile-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-details {
            margin: 20px 0;
        }

        .profile-details p {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .logout-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>User Profile</h2>

    <div class="profile-details">
        <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Password:</strong> <?php echo htmlspecialchars($user['password']); ?></p>
        <p><strong>User Type:</strong> <?php echo htmlspecialchars($user['user_type']); ?></p>
        <p><strong>Account Created At:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>

    <!-- Logout button -->
    <form method="POST" action="logout.php">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

</body>
</html>
