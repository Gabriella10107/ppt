
<?php
// Include the connection file
include 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to fetch the user
    $stmt = $conn->prepare("SELECT id, password FROM customer_signup WHERE email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Start session and store user id
            session_start();
            $_SESSION['id'] = $id;

            // Redirect to the user's index page with the id parameter
            header("Location: ../user/index.php?id=" . urlencode($id));
            exit(); // Ensure no further code is executed after redirection
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please register first.');</script>";
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="templatemo">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
        <title>Liberty NFT Marketplace - HTML CSS Template</title>
        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Additional CSS Files -->
        <link rel="stylesheet" href="assets/css/fontawesome.css">
        <link rel="stylesheet" href="assets/css/templatemo-liberty-market.css">
        <link rel="stylesheet" href="assets/css/owl.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    </head>
    <style>
/* Body and General Layout */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    flex-direction: column;
    box-sizing: border-box;
}

/* Form Container Styling */
.form-container {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    width: 400px;
    padding: 30px;
    text-align: center;
    animation: fadeIn 1.2s ease-out;
    margin-top: 160px;
    margin-bottom: 60px;
}

/* Fade-in Animation for Form */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form Title Styling */
.form-container h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

/* Input Fields Styling */
.form-container input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-container input:focus {
    border-color: #6E85FF;
    box-shadow: 0 0 8px #6E85FF;
    outline: none;
}

/* Submit Button Styling */
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

/* Link Styling */
.form-container a {
    color: #6E85FF;
    text-decoration: none;
    font-size: 14px;
    margin-top: 15px;
    display: inline-block;
}

.form-container a:hover {
    text-decoration: underline;
}

/* Responsive Adjustments */
@media (max-width: 600px) {
    .form-container {
        width: 90%;
        padding: 20px;
    }

    .form-container h2 {
        font-size: 20px;
    }

    .form-container input {
        padding: 8px;
        font-size: 14px;
    }

    .form-container button[type="submit"] {
        padding: 8px 16px;
        font-size: 14px;
    }
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

        .vendor-section {
            margin-top: 20px;
            text-align: center;
        }

        .vendor-section a {
            color: #6E85FF;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .vendor-section a:hover {
            color: #A5D8FF;
        }

        .floating-vendor-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #6E85FF;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        .floating-vendor-button a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .floating-vendor-button:hover {
            background-color: #A5D8FF;
        }

        footer {
            width: 100%;
            text-align: center;
            padding: 20px 0;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }
        </style>

    <body>

    <header class="header-area header-sticky" >
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.php" class="logo">
                            <img src="assets/images/Essential-Logo.png" alt="Company Logo">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="#" class="active">Get On App</a></li>
                            <li><a href="products.html">Products</a></li>
                            <li><a href="shop.html">Shop</a></li>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="signup.php">Register</a></li>
                        </ul>
                        <a class="menu-trigger">
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>

       
    </header>

    <div class="form-container">
            <img src="assets/images/Essential-Logo.png">
            <!-- <h2>Welcome Back!</h2> -->
            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
            </form>
            <p style="color: black;">Don't have an account? <a href="signup.php">Sign Up</a></p>
    <br>
             <div class="vendor-section">
                <p style="color: black;">Are you a vendor? <a href="../vendor/login.php" class="vendor-button">Login as Vendor</a></p>
            </div>
        </div>



    <footer>
        <div class="container">
            <div class="row">
                <!-- Footer Logo and Description -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="index.html" class="footer-logo">
                        <img src="assets/images/Essential-Logo.png" alt="Footer Logo" style="max-width: 150px;">
                    </a>
                    <p style="margin-top: 10px; color: #555;">
                        Liberty NFT Marketplace offers a wide selection of NFTs and supports a vibrant creative community.
                    </p>
                </div>

                <!-- Quick Links Section -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <h5 style="font-weight: bold; margin-bottom: 15px; color: #333;">Quick Links</h5>
                    <ul style="list-style: none; padding: 0; line-height: 1.8;">
                        <li><a href="index.php" style="color: #2e2e2e; text-decoration: none;">Get On App</a></li>
                        <li><a href="explore.html" style="color: #2e2e2e; text-decoration: none;">Products</a></li>
                        <li><a href="details.html" style="color: #2e2e2e; text-decoration: none;">Shop</a></li>
                        <li><a href="author.html" style="color: #2e2e2e; text-decoration: none;">Login</a></li>
                        <li><a href="create.html" style="color: #2e2e2e; text-decoration: none;">Register</a></li>
                        <li><a href="create.html" style="color: #2e2e2e; text-decoration: none;">Language</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <h5 style="font-weight: bold; margin-bottom: 15px; color: #333;">Contact Us</h5>
                    <p style="color: #2e2e2e;">Feel free to reach out to us via email or social media:</p>
                    <p style="color: #2e2e2e; font-size: 14px;">
                        <strong>Email:</strong> <a href="mailto:support@libertynft.com" style="color: #2e2e2e; text-decoration: none;">support@libertynft.com</a>
                    </p>
                    <div>
                        <a href="#" style="color: #2e2e2e; margin-right: 10px; text-decoration: none;">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <a href="#" style="color: #2e2e2e; margin-right: 10px; text-decoration: none;">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="#" style="color: #2e2e2e; text-decoration: none;">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <p style="color: #2e2e2e; font-size: 14px;">
                        Copyright © 2022 <a href="#" style="color: #2e2e2e; text-decoration: none;">Liberty</a> NFT Marketplace Co., Ltd. All rights reserved.
                        Designed by <a title="HTML CSS Templates" rel="sponsored" href="https://templatemo.com" target="_blank" style="color: #2e2e2e; text-decoration: none;">TemplateMo</a>.
                    </p>
                </div>
            </div>
        </div>
    </footer>

        <!-- Scripts -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/isotope.min.js"></script>
        <script src="assets/js/owl-carousel.js"></script>
        <script src="assets/js/tabs.js"></script>
        <script src="assets/js/popup.js"></script>
        <script src="assets/js/custom.js"></script>
    </body>
    </html>