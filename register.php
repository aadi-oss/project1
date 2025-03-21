<?php
session_start();  // Start session to check login status
@include 'config.php';  // Include the database connection

// Initialize error array
$error = [];

// Check if the form is submitted
if (isset($_POST['submit'])) {
   // Sanitize and validate user inputs
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = $_POST['password'];
   $cpass = $_POST['cpassword']; // Confirm password
   $user_type = $_POST['user_type'];

   // Name validation: Only alphabets and spaces allowed
   if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
      $error[] = 'Name should contain only alphabets and spaces!';
   }

   // Email validation: Must contain @ and a valid domain (e.g., @gmail.com)
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error[] = 'Invalid email format!';
   }

   // Password validation: Must contain at least one of #, @, or $
   if (!preg_match("/[#@$]/", $pass)) {
      $error[] = 'Password must contain at least one of #, @, or $!';
   }

   // Check if the user already exists
   $select = "SELECT * FROM user WHERE email = '$email'";
   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {
      $error[] = 'User already exists!';
   } else {
      if ($pass != $cpass) {
         $error[] = 'Passwords do not match!';
      } else {
         // Hash the password for security
         $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

         // Insert the new user into the user table
         $insert = "INSERT INTO user (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_pass', '$user_type')";

         if (mysqli_query($conn, $insert)) {
            header('Location: login.php'); // Redirect to login page after successful registration
            exit;
         } else {
            $error[] = 'Error occurred while registering user: ' . mysqli_error($conn);
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Form</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
   <form action="" method="post" onsubmit="return validateForm()">
      <h3>Register Now</h3>
      <?php
      if (isset($error)) {
         foreach ($error as $err) {
            echo '<span class="error-msg">' . $err . '</span>';
         }
      }
      ?>
      <input type="text" name="name" id="name" required placeholder="Enter your name">
      <input type="email" name="email" id="email" required placeholder="Enter your email">
      <input type="password" name="password" id="password" required placeholder="Enter your password">
      <input type="password" name="cpassword" id="cpassword" required placeholder="Confirm your password">
      <select name="user_type">
         <option value="user">User</option>
         <option value="admin">Admin</option>
      </select>
      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</div>

<script>
   function validateForm() {
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const cpassword = document.getElementById('cpassword').value;

      const nameRegex = /^[a-zA-Z ]*$/;
      if (!nameRegex.test(name)) {
         alert('Name should contain only alphabets and spaces!');
         return false;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
         alert('Invalid email format!');
         return false;
      }

      const passwordRegex = /[#@$]/;
      if (!passwordRegex.test(password)) {
         alert('Password must contain at least one of #, @, or $!');
         return false;
      }

      if (password !== cpassword) {
         alert('Passwords do not match!');
         return false;
      }

      return true;
   }
</script>

</body>
</html>
