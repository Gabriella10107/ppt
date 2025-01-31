<?php
// Include the database connection
include 'connection.php';

// Create vendors table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS vendors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_no VARCHAR(15) NOT NULL,
    address VARCHAR(255) NOT NULL,
    business_name VARCHAR(100) NOT NULL,
    business_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    // Table created successfully or already exists
} else {
    die("Error creating table: " . $conn->error);
}

// Handle form submission
$alert_message = "";
$alert_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $phone_no = htmlspecialchars($_POST['phone_no']);
    $address = htmlspecialchars($_POST['address']);
    $business_name = htmlspecialchars($_POST['business_name']);
    $business_type = htmlspecialchars($_POST['business_type']);

    // Insert data into the vendors table
    $stmt = $conn->prepare("INSERT INTO vendors (name, email, password, phone_no, address, business_name, business_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $password, $phone_no, $address, $business_name, $business_type);

    if ($stmt->execute()) {
        $alert_message = "Vendor registration successful!";
        $alert_type = "success";
    } else {
        $alert_message = "Error: " . $stmt->error;
        $alert_type = "error";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Signup</title>
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

        .form-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            padding: 30px;
            text-align: center;
            animation: fadeIn 1.2s ease-out;
        }

        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-shadow: 1px 1px 2px #ccc;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-container input[type="text"]:focus,
        .form-container input[type="email"]:focus,
        .form-container input[type="password"]:focus {
            border-color: #6E85FF;
            box-shadow: 0 0 8px #6E85FF;
            outline: none;
        }

        .form-container button[type="submit"] {
            background: linear-gradient(135deg, #6E85FF, #A5D8FF);
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-container button[type="submit"]:hover {
            background: linear-gradient(135deg, #A5D8FF, #6E85FF);
        }

        .alert {
            font-size: 14px;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .alert.success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Vendor Signup</h2>

        <!-- Show alert message if available -->
        <?php if ($alert_message): ?>
            <div class="alert <?php echo $alert_type; ?>"><?php echo $alert_message; ?></div>
        <?php endif; ?>

        <form action="../vendor/signup.php" method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone_no" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="business_name" placeholder="Business Name" required>
            <input type="text" name="business_type" placeholder="Business Type" required>
            <button type="submit">Sign Up</button>
        </form>


        <div class="vendor-section">
            <p style="color: black;">Already have an account? <a href="../vendor/login.php" class="vendor-button">Login</a></p>
        </div>
    </div>
</body>
</html>