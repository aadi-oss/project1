<?php
session_start();
@include 'config.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: welcome.php');  // Redirect to welcome.php if the user is already logged in
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect login details from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details from the database based on the entered email
    $stmt = $conn->prepare("SELECT id, name, email, password, user_type FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password entered by the user
        if (password_verify($password, $user['password'])) {
            // Store user ID and user type in session
            $_SESSION['user_id'] = $user['id'];  
            $_SESSION['user_type'] = $user['user_type'];  // Could be 'admin' or 'normal'
            
            // Redirect based on user type or to welcome page by default
            if ($_SESSION['user_type'] == 'admin') {
                header('Location: admin/admin.php');  // Redirect to admin page if user is admin
            } else {
                header('Location: welcome.php');  // Redirect to normal user welcome page
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Bus Ticketing System</title>
  <style>
    /* Basic styles for demonstration */
    body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
    .login-form { width: 300px; margin: 100px auto; padding: 20px; background: #fff; border: 1px solid #ccc; border-radius: 5px; }
    .login-form input { width: 100%; padding: 10px; margin: 5px 0; }
    .login-form button { width: 100%; padding: 10px; background: #2c3e50; color: #fff; border: none; border-radius: 5px; }
    .login-form p { text-align: center; }
  </style>
</head>
<body>
  <div class="login-form">
    <h2>Login to Your Account</h2>
    <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" required><br>
      <input type="password" name="password" placeholder="Enter your password" required><br>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>
