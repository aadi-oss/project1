<?php
session_start();
@include 'config.php'; // Including the config file for database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Bus Ticketing System</title>
    <link rel="stylesheet" href="css/style.css">
<style>/* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

h1, h2 {
    color: #333;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Sidebar Styles */
.sidebar {
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #333;
    color: white;
    height: 100%;
    padding-top: 20px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
    color: #ffeb3b;
}

.sidebar a {
    display: block;
    padding: 12px;
    text-align: center;
    color: white;
    font-size: 18px;
    border-bottom: 1px solid #444;
}

.sidebar a:hover {
    background-color: #444;
}

/* Main Content Styles */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

.about-section {
    max-width: 900px;
    margin: 0 auto;
    background-color: white;
    padding: 30px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.about-section h1 {
    text-align: center;
    color: #007bff;
}

.about-section p {
    font-size: 18px;
    line-height: 1.6;
    color: #555;
}

.about-section h2 {
    color: #333;
    margin-top: 30px;
}

.about-section ul {
    list-style-type: disc;
    margin-left: 30px;
}

.about-section ul li {
    font-size: 18px;
    color: #555;
    margin-bottom: 10px;
}

/* Form Styles (if needed for login/registration) */
.form-container {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    background-color: white;
    padding: 30px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 50px;
}

.form-container h3 {
    text-align: center;
    margin-bottom: 30px;
}

.form-container input {
    width: 100%;
    padding: 15px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-container .form-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 15px;
    width: 100%;
    font-size: 18px;
    cursor: pointer;
}

.form-container .form-btn:hover {
    background-color: #0056b3;
}

/* Error Message Styles */
.error-msg {
    color: red;
    font-size: 14px;
    display: block;
    margin-top: 10px;
}
</style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Bus Ticketing System</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="admin/admin.php">Admin Panel</a>
            <?php endif; ?>
            <a href="index.php">Bookings</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact Us</a>
            <a href="logout.php">Log out</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact Us</a>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="about-section">
            <h1>About Us</h1>
            <p>Welcome to the Bus Ticketing System, the easiest way to book your bus tickets online! We are committed to providing a seamless and reliable way for you to plan your travel.</p>

            <h2>Our Mission</h2>
            <p>Our mission is to make booking bus tickets a hassle-free experience for travelers. Whether you're commuting for work or planning a vacation, our system ensures that you can easily find and book tickets for your desired routes, all from the comfort of your home.</p>

            <h2>Why Choose Us?</h2>
            <ul>
                <li><strong>Convenience:</strong> Book your tickets anytime, anywhere.</li>
                <li><strong>Affordable:</strong> Enjoy competitive prices and special discounts.</li>
                <li><strong>Wide Range of Routes:</strong> We offer bus tickets for multiple routes across the region.</li>
                <li><strong>Secure Payments:</strong> Our payment system is safe and easy to use.</li>
            </ul>

            <h2>Our Vision</h2>
            <p>We aim to become the leading platform for bus ticket bookings, offering services that cater to the diverse needs of passengers. Our goal is to revolutionize the travel industry by making it easier, more affordable, and accessible for everyone.</p>

            <h2>Our Team</h2>
            <p>We have a dedicated team of professionals who work relentlessly to ensure that your travel experience is as smooth as possible. From developers to customer service agents, everyone plays a crucial role in the success of our platform.</p>
        </div>
    </div>

</body>
</html>
