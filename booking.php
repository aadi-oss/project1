<?php
@include '../config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    header('location:login.php');
    exit();
}

// Handle booking deletion
if (isset($_GET['delete_booking'])) {
    $booking_id = $_GET['delete_booking'];
    // Prevent SQL injection by using prepared statements
    $delete_query = "DELETE FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    mysqli_stmt_execute($stmt);
    header('Location: manageBookings.php');
    exit();
}

// Fetch bookings from the database
$select_bookings = "SELECT bookings.id, bookings.user_id, bookings.bus_id, users.name AS full_name, 
                           users.contact_method, users.phone_number, users.email AS email_address, 
                           buses.bus_type, bookings.selected_seats, bookings.booking_date
                    FROM bookings
                    INNER JOIN users ON bookings.user_id = users.id
                    INNER JOIN buses ON bookings.bus_id = buses.id
                    ORDER BY bookings.id ASC";  // Order by booking id in ascending order

$result = mysqli_query($conn, $select_bookings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible=IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Panel</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }
        .admin-container {
            display: flex;
        }
        .admin-sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }
        .admin-sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }
        .admin-sidebar ul li {
            margin: 15px 0;
        }
        .admin-sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }
        .admin-sidebar ul li a:hover, .admin-sidebar ul li a.active {
            background-color: #4CAF50;
        }
        .admin-content {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #333;
            color: white;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            padding: 8px 12px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }
        .delete-btn {
            background-color: #f44336;
        }
    </style>
</head>
<body>

<div class="admin-container">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="addbus.php">Add New Bus</a></li>
            <li><a href="customerList.php">View Customer List</a></li>
            <li><a href="manageBookings.php" class="active">Manage Bookings</a></li>
            <li><a href="editUserList.php">Edit User List</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <h1>Manage Bookings</h1>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Contact Method</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Bus Type</th>
                <th>Selected Seats</th>
                <th>Booking Date</th>
                <th>Actions</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['user_id']}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['contact_method']}</td>
                            <td>{$row['phone_number']}</td>
                            <td>{$row['email_address']}</td>
                            <td>{$row['bus_type']}</td>
                            <td>{$row['selected_seats']}</td>
                            <td>{$row['booking_date']}</td>
                            <td>
                                <a href='manageBookings.php?delete_booking={$row['id']}' class='action-btn delete-btn' onclick='return confirm(\"Are you sure you want to delete this booking?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No bookings available</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
