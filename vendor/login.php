<?php
// Start the session
session_start();

// Include the database connection
include 'connection.php';

// Handle form submission
$alert_message = "";
$alert_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Fetch vendor data from the database
    $stmt = $conn->prepare("SELECT id, name, password FROM vendors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start a session
            $_SESSION['vendor_id'] = $id;
            $_SESSION['vendor_name'] = $name;

            // Redirect to the vendor dashboard with the vendor ID in the URL
            header("Location: ../vendor/index.php?id=" . urlencode($id));
            exit();
        } else {
            $alert_message = "Invalid email or password.";
            $alert_type = "error";
        }
    } else {
        $alert_message = "Invalid email or password.";
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
    <title>Vendor Login</title>
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
        <h2>Vendor Login</h2>

        <!-- Show alert message if available -->
        <?php if ($alert_message): ?>
            <div class="alert <?php echo $alert_type; ?>"><?php echo $alert_message; ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p style="color: black;">Don't have an account? <a href="../vendor/signup.php">Sign Up</a></p>
    </div>
</body>
</html>