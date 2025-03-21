<?php
// PHP code to handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate fields
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "All fields are required.";
    } else {
        // Example of sending an email
        $to = "support@busticketing.com";  // Replace with your support email
        $subject = "Contact Form Submission from $name";
        $body = "Message from $name ($email):\n\n$message";
        $headers = "From: $email";

        // Check if the email was sent successfully
        if (mail($to, $subject, $body, $headers)) {
            $success_message = "Thank you for contacting us! We will get back to you shortly.";
        } else {
            $error_message = "There was an error sending your message. Please try again later.";
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
    <title>Contact Us</title>
    <style>
        /* General Styles */
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

        /* Back Button Styles */
        .back-btn {
            margin: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        /* Main Content Styles */
        .main-content {
            padding: 20px;
        }

        .contact-section {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .contact-section h1 {
            text-align: center;
            color: #007bff;
        }

        .contact-section p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
        }

        .contact-info {
            margin-top: 30px;
        }

        .contact-info h2 {
            color: #333;
        }

        .contact-info ul {
            list-style-type: none;
            padding: 0;
        }

        .contact-info ul li {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }

        .contact-info ul li strong {
            color: #007bff;
        }

        /* Contact Form Styles */
        form {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
            display: block;
        }

        form input, form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Error & Success Message Styles */
        .error-msg {
            color: red;
            font-size: 14px;
            display: block;
            margin-top: 10px;
        }

        .success-msg {
            color: green;
            font-size: 14px;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <a href="javascript:history.back()" class="back-btn">Back</a>

    <!-- Main Content -->
    <div class="main-content">
        <div class="contact-section">
            <h1>Contact Us</h1>
            <p>If you have any questions or issues, please don't hesitate to reach out to us. Our customer support team is available to assist you!</p>

            <!-- Customer Support Details -->
            <div class="contact-info">
                <h2>Customer Support</h2>
                <ul>
                    <li><strong>Email:</strong> support@busticketing.com</li>
                    <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                    <li><strong>Address:</strong> 123 Main Street, Cityville, Country</li>
                </ul>
            </div>

            <!-- Display error or success message -->
            <?php
            if (isset($error_message)) {
                echo '<div class="error-msg">' . $error_message . '</div>';
            }
            if (isset($success_message)) {
                echo '<div class="success-msg">' . $success_message . '</div>';
            }
            ?>

            <!-- Contact Form -->
            <h2>Send us a Message</h2>
            <form action="" method="post">
                <label for="name">Your Name</label>
                <input type="text" name="name" id="name" required placeholder="Enter your name">

                <label for="email">Your Email</label>
                <input type="email" name="email" id="email" required placeholder="Enter your email address">

                <label for="message">Your Message</label>
                <textarea name="message" id="message" rows="5" required placeholder="Write your message here"></textarea>

                <input type="submit" value="Send Message">
            </form>
        </div>
    </div>

</body>
</html>
