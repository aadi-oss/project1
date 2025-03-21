<?php
session_start();
@include 'config.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';

// Fetch user details if logged in
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT name, email, user_type FROM user WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $user_query);
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_data = mysqli_fetch_assoc($user_result);
        $user_name = $user_data['name'];
        $user_email = $user_data['email'];
        $user_type = $user_data['user_type']; // Admin check
    } else {
        $user_name = 'Unknown';
        $user_email = 'Unknown';
        $user_type = 'guest'; // Default to guest
    }
}

// Fetch buses from the database (from the 'buses' table)
$buses = [];
$fetch_buses = "SELECT * FROM buses";
$bus_result = mysqli_query($conn, $fetch_buses);
if ($bus_result && mysqli_num_rows($bus_result) > 0) {
    while ($bus = mysqli_fetch_assoc($bus_result)) {
        $buses[] = $bus;
    }
}

// Handle review submission
if ($isLoggedIn && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $review_text = htmlspecialchars(trim($_POST['review_text']));
    $rating = intval($_POST['rating']);
    $bus_id = intval($_POST['bus_id']);  // Bus ID from the form
    if (!empty($review_text) && $rating >= 1 && $rating <= 5) {
        $review_text = mysqli_real_escape_string($conn, $review_text);
        $insert_review = "INSERT INTO comment (user_id, review_text, rating, bus_id) 
                          VALUES ('$user_id', '$review_text', '$rating', '$bus_id')";
        if (mysqli_query($conn, $insert_review)) {
            $review_success = "Thank you for your review!";
        } else {
            $review_error = "Error submitting review: " . mysqli_error($conn);
        }
    } else {
        $review_error = "Invalid review or rating. Please try again.";
    }
}

// Fetch reviews for a specific bus (default bus_id = 1; adjust as needed)
$reviews = [];
$bus_review_id = isset($_GET['bus_id']) ? intval($_GET['bus_id']) : 1; // Default to bus_id = 1
$fetch_reviews = "SELECT user.name, comment.review_text, comment.rating, comment.created_at 
                  FROM comment 
                  JOIN user ON comment.user_id = user.id 
                  WHERE comment.bus_id = '$bus_review_id'
                  ORDER BY comment.created_at DESC";
$result = mysqli_query($conn, $fetch_reviews);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bus Ticketing System</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    /* Global Styles */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
    }
    /* Sidebar Styles */
    .sidebar {
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      background-color: #2c3e50;
      padding: 20px;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
      text-align: center;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      margin: 10px 0;
      padding: 10px;
      width: 100%;
      text-align: center;
      background-color: #34495e;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    .sidebar a:hover {
      background-color: #1abc9c;
    }
    /* Added Profile & Logout options in sidebar */
    .sidebar .profile-links a {
      background-color: #2c3e50;
      border: 1px solid #fff;
      margin-top: 20px;
    }
    /* Main Content */
    .main-content {
      margin-left: 270px;
      padding: 40px;
    }
    .welcome-section {
      text-align: center;
      margin-bottom: 40px;
    }
    .welcome-section h1 {
      font-size: 36px;
      color: #2c3e50;
      margin-bottom: 10px;
    }
    .welcome-section p {
      font-size: 18px;
      color: #7f8c8d;
    }
    /* Bus Slider Styles */
    .bus-slider {
      width: 80%;
      margin: 20px auto;
      position: relative;
      overflow: hidden;
    }
    .bus-slide {
      display: none;
      text-align: center;
      padding: 20px;
      border: 1px solid #ddd;
      margin-bottom: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      color: black; /* Bus text color set to black */
    }
    .bus-slide.active {
      display: block;
    }
    .bus-slide img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }
    /* Review Section */
    .review-section {
      margin-top: 40px;
    }
    .review-section h2 {
      font-size: 30px;
      text-align: center;
      color: #2c3e50;
    }
    .review-form {
      max-width: 600px;
      margin: 0 auto 40px auto;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }
    .review-form textarea {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
      margin-bottom: 15px;
      resize: vertical;
    }
    .review-form select,
    .review-form input[type="number"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
      margin-bottom: 15px;
    }
    .review-form button {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background-color: #34495e;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .review-form button:hover {
      background-color: #1abc9c;
    }
    .review-card {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .review-card h4 {
      font-size: 20px;
      color: #2c3e50;
    }
    .review-card p {
      font-size: 16px;
      color: #7f8c8d;
    }
    .review-card .rating {
      font-size: 16px;
      color: #f39c12;
    }
    .review-card small {
      display: block;
      margin-top: 10px;
      font-size: 14px;
      color: #7f8c8d;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Bus Ticketing System</h2>
    <?php if ($isLoggedIn): ?>
      <?php if ($isAdmin): ?>
        <a href="admin/admin.php">Admin Panel</a>
      <?php endif; ?>
      <a href="index.php">Bookings</a>
      <a href="about.php">About Us</a>
      <a href="contact.php">Contact Us</a>
      <div class="profile-links">
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
      </div>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
      <a href="about.php">About Us</a>
      <a href="contact.php">Contact Us</a>
    <?php endif; ?>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Welcome Section -->
    <div class="welcome-section">
      <h1>Welcome to Bus Ticketing System</h1>
      <p>Book your tickets easily and travel comfortably.</p>
    </div>

    <!-- Bus Slider Section -->
    <div class="bus-slider">
      <?php if (!empty($buses)): ?>
        <?php foreach ($buses as $index => $bus): ?>
          <div class="bus-slide <?php echo ($index === 0) ? 'active' : ''; ?>">
            <h3><?php echo htmlspecialchars($bus['bus_name']); ?></h3>
            <p><strong>Bus Number:</strong> <?php echo htmlspecialchars($bus['bus_number']); ?></p>
            <p><strong>Seats:</strong> <?php echo htmlspecialchars($bus['seats']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($bus['bus_description']); ?></p>
            <p><strong>Route:</strong> <?php echo htmlspecialchars($bus['bus_route']); ?></p>
            <?php if (!empty($bus['bus_image'])): ?>
              <img src="<?php echo htmlspecialchars($bus['bus_image']); ?>" alt="<?php echo htmlspecialchars($bus['bus_name']); ?>" />
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No buses available at the moment.</p>
      <?php endif; ?>
    </div>

    <!-- Review Section -->
    <div class="review-section">
      <h2>Customer Reviews</h2>
      <!-- Review Form -->
      <?php if ($isLoggedIn): ?>
        <div class="review-form">
          <?php if (isset($review_success)): ?>
            <p class="review-success"><?php echo $review_success; ?></p>
          <?php elseif (isset($review_error)): ?>
            <p class="review-error"><?php echo $review_error; ?></p>
          <?php endif; ?>
          <form method="POST" action="">
            <textarea name="review_text" placeholder="Write your review here..." required></textarea>
            <select name="rating" required>
              <option value="">Select Rating</option>
              <option value="1">1 Star</option>
              <option value="2">2 Stars</option>
              <option value="3">3 Stars</option>
              <option value="4">4 Stars</option>
              <option value="5">5 Stars</option>
            </select>
            <input type="hidden" name="bus_id" value="<?php echo $bus_review_id; ?>" />
            <button type="submit" name="submit_review">Submit Review</button>
          </form>
        </div>
      <?php else: ?>
        <p>You need to <a href="login.php">login</a> to leave a review.</p>
      <?php endif; ?>

      <!-- Display Reviews -->
      <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
          <div class="review-card">
            <h4><?php echo htmlspecialchars($review['name']); ?></h4>
            <p><?php echo htmlspecialchars($review['review_text']); ?></p>
            <p class="rating">Rating: <?php echo str_repeat('⭐', $review['rating']); ?></p>
            <small><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No reviews yet. Be the first to leave a review!</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
