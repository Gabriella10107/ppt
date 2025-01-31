<?php
// Start the session
session_start();

// Check if the vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    header("Location: ../vendor/login.php");
    exit();
}

// Get the vendor ID from the URL
if (isset($_GET['id'])) {
    $vendor_id = htmlspecialchars($_GET['id']);
} else {
    die("Vendor ID not found in the URL.");
}

// Include the database connection
include 'connection.php';

// Fetch vendor details
$stmt = $conn->prepare("SELECT name, email, business_name FROM vendors WHERE id = ?");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$stmt->bind_result($name, $email, $business_name);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFDEE9, #B5FFFC);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .dashboard-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            padding: 30px;
            text-align: center;
            animation: fadeIn 1.2s ease-out;
        }

        .dashboard-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-shadow: 1px 1px 2px #ccc;
        }

        .dashboard-container p {
            font-size: 16px;
            color: #555;
        }

        .dashboard-container a {
            color: #6E85FF;
            text-decoration: none;
            font-weight: bold;
        }

        .dashboard-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Business Name: <?php echo htmlspecialchars($business_name); ?></p>
        <p>Vendor ID: <?php echo htmlspecialchars($vendor_id); ?></p>
        <p><a href="../vendor/logout.php">Logout</a></p>
    </div>
</body>
</html>