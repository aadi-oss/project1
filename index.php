<?php
// Start the session
session_start();

// Include the database configuration file
@include 'config.php';

// Fetch bus details from the database
$select_buses = "SELECT * FROM buses";
$result = mysqli_query($conn, $select_buses);

// Fetch user details (assuming there's a session with user ID)
$user_id = $_SESSION['user_id'] ?? null;
$user_info = null;

if ($user_id) {
    // Fetch user info from the database
    $query = "SELECT * FROM user WHERE id = $user_id";
    $user_result = mysqli_query($conn, $query);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_info = mysqli_fetch_assoc($user_result);
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Ticketing System</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      background-image: url('img/images.jpg');
      background-size: cover;
      background-position: center center;
      background-attachment: fixed;
      overflow: hidden;
    }

    .header-title {
      font-size: 32px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      text-transform: uppercase;
      color: #4CAF50;
    }

    .input-box {
      margin-bottom: 15px;
      width: 100%;
    }

    .input-box label {
      font-weight: bold;
      margin-bottom: 5px;
      display: block;
    }

    .input-box input, .input-box select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    .button {
      text-align: center;
      margin-top: 20px;
    }

    .button button {
      padding: 12px 25px;
      background: linear-gradient(135deg, #4CAF50, #2196F3);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .button button:hover {
      background: linear-gradient(-135deg, #4CAF50, #2196F3);
    }

    .contact-us {
      margin-top: 30px;
      padding: 10px;
      background: #2196F3;
      color: white;
      border-radius: 5px;
      text-align: center;
      font-weight: 500;
    }

    .contact-us a {
      color: white;
      text-decoration: none;
      font-weight: 600;
    }

    .error-message {
      color: red;
      font-size: 0.8em;
      margin-top: -10px;
      margin-bottom: 10px;
      display: none;
    }

    .bus-option {
      margin-bottom: 15px;
    }

    .profile-menu {
      display: none;
      position: absolute;
      top: 70px;
      right: 20px;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .profile-icon {
      position: absolute;
      top: 20px;
      right: 20px;
      cursor: pointer;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #4CAF50;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      transition: background-color 0.3s;
    }

    .profile-icon:hover {
      background-color: #2196F3;
    }

    .profile-menu a {
      color: #2196F3;
      text-decoration: none;
      font-weight: bold;
      margin-top: 10px;
      display: block;
    }

    .profile-menu button {
      margin-top: 10px;
      padding: 10px 20px;
      background: #F44336;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .profile-menu button:hover {
      background: #D32F2F;
    }

    /* Seat selection styles */
    .seat-selection-container {
      display: none;
      margin-top: 20px;
      text-align: center;
    }

    .seat-selection-container .seat {
      display: inline-block;
      width: 30px;
      height: 30px;
      margin: 5px;
      background-color: #2196F3;
      color: white;
      text-align: center;
      line-height: 30px;
      border-radius: 5px;
      cursor: pointer;
    }

    .seat-selection-container .seat.selected {
      background-color: #4CAF50;
    }

    .seat-selection-container .seat.window {
      background-color: #FF5722;
    }

    /* Phone and email toggle styles */
    #phoneSection,
    #emailSection {
      display: none; /* Hide both fields by default */
    }

    #phoneSection.active,
    #emailSection.active {
      display: block; /* Show the active field */
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header-title">Bus Ticketing System</div>
    
    <form id="bookingForm" method="POST" action="book_ticket.php">
      <!-- Bus Selection -->
      <div class="input-box">
        <label><b>Select Bus</b></label>
        <select id="busSelect" name="busSelect" required>
          <option value="">-- Select Bus --</option>
          <?php
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<option value="' . $row['id'] . '">' . $row['bus_name'] . ' (' . $row['bus_number'] . ')</option>';
              }
          } else {
              echo '<option value="">No buses available</option>';
          }
          ?>
        </select>
      </div>

      <!-- Full Name -->
      <div class="input-box">
        <label><b>Full Name</b></label>
        <input type="text" id="fullName" name="fullName" required>
      </div>

      <!-- Contact Method -->
      <div class="input-box">
        <label><b>Contact Method</b></label>
        <select id="contactMethod" name="contactMethod">
          <option value="phone">Phone</option>
          <option value="email">Email</option>
        </select>
      </div>

      <!-- Phone Number -->
      <div class="input-box" id="phoneSection">
        <label><b>Phone Number</b></label>
        <input type="tel" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}">
        <div id="phoneError" class="error-message">Please enter a valid 10-digit phone number</div>
      </div>

      <!-- Email Address -->
      <div class="input-box" id="emailSection">
        <label><b>Email Address</b></label>
        <input type="email" id="emailAddress" name="emailAddress">
        <div id="emailError" class="error-message">Please enter a valid email address</div>
      </div>

      <!-- Bus Type -->
      <div class="input-box">
        <label><b>Bus Type</b></label>
        <select id="busType" name="busType" required>
          <option value="ac">AC</option>
          <option value="non-ac">Non-AC</option>
        </select>
      </div>

      <!-- Seat Selection -->
      <div class="seat-selection-container" id="seatSelectionContainer">
        <label><b>Select Seats</b></label>
        <div id="seats"></div>
        <div class="error-message" id="seatError">You can only select up to 5 seats.</div>
      </div>

      <!-- Hidden input for selected seats -->
      <input type="hidden" id="selectedSeats" name="seats">

      <!-- Submit Button -->
      <div class="button">
        <button type="submit">Book Tickets</button>
      </div>
    </form>

    <div class="contact-us">
      <p>Need help? <a href="mailto:support@busticketsystem.com">Contact Us</a></p>
    </div>
  </div>

  <!-- Profile Icon -->
  <div class="profile-icon" id="profileIcon" onclick="toggleProfileMenu()">
      <i class="fa fa-user"></i> <!-- You can replace this with an icon or image -->
  </div>

  <!-- Profile Menu -->
  <div class="profile-menu" id="profileMenu">
      <?php if ($user_info) { ?>
          <p>Name: <?php echo htmlspecialchars($user_info['name']); ?></p>
          <p>Email: <?php echo htmlspecialchars($user_info['email']); ?></p>
          <p>User Type: <?php echo htmlspecialchars($user_info['user_type']); ?></p> <!-- Display user type -->
          <a href="reset_password.php">Reset Password</a>
      <?php } ?>
      <!-- Always show the logout option -->
      <form action="logout.php" method="POST">
          <button type="submit">Logout</button>
      </form>
  </div>

  <script>
    // Toggle phone and email fields
    const contactMethod = document.getElementById('contactMethod');
    const phoneSection = document.getElementById('phoneSection');
    const emailSection = document.getElementById('emailSection');

    function toggleContactFields() {
      if (contactMethod.value === 'phone') {
        phoneSection.style.display = 'block';
        emailSection.style.display = 'none';
      } else if (contactMethod.value === 'email') {
        phoneSection.style.display = 'none';
        emailSection.style.display = 'block';
      }
    }

    // Initial call to set the correct field visibility
    toggleContactFields();

    // Add event listener to toggle fields when contact method changes
    contactMethod.addEventListener('change', toggleContactFields);

    // Seat selection logic
    const busSelect = document.getElementById('busSelect');
    const seatSelectionContainer = document.getElementById('seatSelectionContainer');
    const seatsContainer = document.getElementById('seats');
    const seatError = document.getElementById('seatError');

    busSelect.addEventListener('change', function () {
      const selectedBus = busSelect.value;
      if (selectedBus) {
        seatSelectionContainer.style.display = 'block';
        renderSeats(30); // Assuming each bus has 30 seats
      } else {
        seatSelectionContainer.style.display = 'none';
      }
    });

    function renderSeats(seatCount) {
      seatsContainer.innerHTML = ''; // Clear previous seat selections
      for (let i = 1; i <= seatCount; i++) {
        const seat = document.createElement('div');
        seat.classList.add('seat');
        seat.innerText = i;
        seat.onclick = function () {
          seat.classList.toggle('selected');
          checkSeatLimit();
          updateSelectedSeats();
        };
        seatsContainer.appendChild(seat);
      }
    }

    function checkSeatLimit() {
      const selectedSeats = document.querySelectorAll('.seat.selected');
      if (selectedSeats.length > 5) {
        seatError.style.display = 'block';
      } else {
        seatError.style.display = 'none';
      }
    }

    function updateSelectedSeats() {
      const selectedSeats = document.querySelectorAll('.seat.selected');
      const selectedSeatsArray = Array.from(selectedSeats).map(seat => seat.innerText);
      document.getElementById('selectedSeats').value = selectedSeatsArray.join(',');
    }

    // Toggle profile menu
    function toggleProfileMenu() {
      const profileMenu = document.getElementById('profileMenu');
      if (profileMenu.style.display === 'block') {
        profileMenu.style.display = 'none';
      } else {
        profileMenu.style.display = 'block';
      }
    }

    // Close the profile menu if clicked outside
    document.addEventListener('click', function(event) {
      const profileIcon = document.getElementById('profileIcon');
      const profileMenu = document.getElementById('profileMenu');
      if (event.target !== profileIcon && !profileIcon.contains(event.target) && event.target !== profileMenu && !profileMenu.contains(event.target)) {
        profileMenu.style.display = 'none';
      }
    });
  </script>
</body>
</html>