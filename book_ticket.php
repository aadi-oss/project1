<?php
session_start();
@include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$success_message = $error_message = '';
$booking_info = [];

// Process the form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $bus_id = mysqli_real_escape_string($conn, $_POST['busSelect']);
    $full_name = mysqli_real_escape_string($conn, $_POST['fullName']);
    $contact_method = mysqli_real_escape_string($conn, $_POST['contactMethod']);
    $phone_number = isset($_POST['phoneNumber']) ? mysqli_real_escape_string($conn, $_POST['phoneNumber']) : null;
    $email_address = isset($_POST['emailAddress']) ? mysqli_real_escape_string($conn, $_POST['emailAddress']) : null;
    $bus_type = mysqli_real_escape_string($conn, $_POST['busType']);
    $selected_seats = isset($_POST['seats']) ? mysqli_real_escape_string($conn, $_POST['seats']) : '';

    // Insert the booking into the database
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO bookings (user_id, bus_id, full_name, contact_method, phone_number, email_address, bus_type, selected_seats) 
              VALUES ('$user_id', '$bus_id', '$full_name', '$contact_method', '$phone_number', '$email_address', '$bus_type', '$selected_seats')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Booking successful
        $booking_id = mysqli_insert_id($conn); // Get the ID of the newly created booking

        // Fetch booking details to display
        $booking_query = "SELECT b.*, bus.bus_name, bus.bus_number 
                          FROM bookings b 
                          JOIN buses bus ON b.bus_id = bus.id 
                          WHERE b.id = $booking_id";
        $booking_result = mysqli_query($conn, $booking_query);

        if ($booking_result && mysqli_num_rows($booking_result) > 0) {
            $booking_info = mysqli_fetch_assoc($booking_result);
            $success_message = "Booking successful!";
        } else {
            $error_message = "Error fetching booking details.";
        }
    } else {
        // Booking failed
        $error_message = "Booking failed. Please try again. Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Confirmation</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .confirmation-container {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 500px;
      width: 100%;
    }
    .confirmation-container h2 {
      color: #4CAF50;
    }
    .confirmation-container p {
      margin: 10px 0;
    }
    .confirmation-container a {
      color: #2196F3;
      text-decoration: none;
      font-weight: bold;
    }
    .confirmation-container .error {
      color: red;
    }
  </style>
</head>
<body>
  <div class="confirmation-container">
    <?php if (isset($success_message) && !empty($booking_info)): ?>
      <h2>Booking Successful!</h2>
      <p><?php echo $success_message; ?></p>
      <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking_info['id']); ?></p>
      <p><strong>Bus Name:</strong> <?php echo htmlspecialchars($booking_info['bus_name']); ?></p>
      <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($booking_info['bus_number']); ?></p>
      <p><strong>Full Name:</strong> <?php echo htmlspecialchars($booking_info['full_name']); ?></p>
      <p><strong>Contact Method:</strong> <?php echo htmlspecialchars($booking_info['contact_method']); ?></p>
      <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($booking_info['phone_number']); ?></p>
      <p><strong>Email Address:</strong> <?php echo htmlspecialchars($booking_info['email_address']); ?></p>
      <p><strong>Bus Type:</strong> <?php echo htmlspecialchars($booking_info['bus_type']); ?></p>
      <p><strong>Selected Seats:</strong> <?php echo htmlspecialchars($booking_info['selected_seats']); ?></p>
      <p>Thank you for booking with us.</p>
      <a href="index.php">Return to Home</a>
    <?php elseif (isset($error_message)): ?>
      <h2>Booking Failed</h2>
      <p class="error"><?php echo $error_message; ?></p>
      <a href="index.php">Try Again</a>
    <?php else: ?>
      <h2>Invalid Request</h2>
      <p>Please submit the booking form to proceed.</p>
      <a href="index.php">Return to Home</a>
    <?php endif; ?>
  </div>
</body>
</html>