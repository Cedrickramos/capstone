<?php
session_start();
// Database connection
require_once "config.php";

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize error message variable
$error_message = "";

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uemail = $_POST['email'];
    $uname = $_POST['username'];
    $upassword = $_POST['password'];

    // Validate password
    if (strlen($upassword) < 8 || !preg_match('/[a-z]/', $upassword) || !preg_match('/[0-9]/', $upassword)) {
        $error_message = 'Password must be at least 8 characters long and numbers.';
    } else {
        // Hash the password
        $hashedPassword = password_hash($upassword, PASSWORD_BCRYPT);

        // Validate email format
        if (!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format!';
        } else {
            // Check if username or email already exists
            $stmt = $conn->prepare('SELECT uid FROM users WHERE uemail = ? OR uname = ?');
            $stmt->bind_param('ss', $uemail, $uname);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = 'Email or Username already exists!';
            } else {
                // Generate a verification token
                $token = bin2hex(random_bytes(16));  // Generates a 32-character token
                $isVerified = 0;

                // Insert new user
                $stmt = $conn->prepare('INSERT INTO users (uemail, uname, upassword, verification_token, is_verified) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('ssssi', $uemail, $uname, $hashedPassword, $token, $isVerified);

                if ($stmt->execute()) {
                    // Send verification email using PHPMailer
                    $verifyLink = "http://accompanyme.kesug.com/verify_email.php?token=$token";
                    $subject = "Verify Your Email";
                    $message = "
                    <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f9f9f9;
                                margin: 0;
                                padding: 0;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #ffffff;
                                padding: 20px;
                                border-radius: 8px;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                            }
                            h1 {
                                color: #4CAF50;
                                text-align: center;
                            }
                            p {
                                font-size: 16px;
                                color: #333333;
                                line-height: 1.6;
                            }
                            .btn {
                                display: block;
                                width: 100%;
                                padding: 15px;
                                background-color: #4CAF50;
                                color: #ffffff;
                                text-align: center;
                                text-decoration: none;
                                font-size: 18px;
                                border-radius: 5px;
                                margin-top: 20px;
                            }
                            .footer {
                                font-size: 12px;
                                color: #aaaaaa;
                                text-align: center;
                                margin-top: 30px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <h1>Welcome to AccompanyMe</h1>
                            <p>Hello,</p>
                            <p>Thank you for registering with AccompanyMe App! To complete your registration, please verify your email address by clicking the link below:</p>
                            <a href='$verifyLink' class='btn'>Verify Email Address</a>
                            <p>If you did not create an account with us, please ignore this email.</p>
                            <div class='footer'>
                                <p>&copy; 2024 AccompanyMe App. All rights reserved.</p>
                                <p>For any support, contact us at <a href='mailto:accompanymelaguna@gmail.com'>accompanymelaguna@gmail.com</a></p>
                            </div>
                        </div>
                    </body>
                    </html>";
                
                    $mail = new PHPMailer(true);
                    try {
                        // Set up PHPMailer
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';  // Use your SMTP server
                        $mail->SMTPAuth = true;
                        $mail->Username = 'accompanymelaguna@gmail.com';  // Your email
                        $mail->Password = 'hrfk vrok ixpn sewx';  // Your email password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                
                        // Recipients
                        $mail->setFrom('no-reply@yourwebsite.com', 'AccompanyMe App');
                        $mail->addAddress($uemail);
                
                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body    = $message;
                
                        // Send email
                        $mail->send();
                        $success_message = "A verification email has been sent to your email address. Please check your inbox (it may take a few minutes).";
                    } catch (Exception $e) {
                        $error_message = "Failed to send verification email. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
                 else {
                    $error_message = 'Sign-up failed!';
                }
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .success-message {
            background-color: #28a745;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* .input-group {
    position: relative;
} */

/* .input-group input {
    width: 100%;
    padding-right: 40px; /* Adjust to make space for the icon */
} */

.input-group i {
    position: absolute;
    right: 10px;
    top: 40%;
    transform: translateY(-50%);
    cursor: pointer;
}

    </style>
</head>
<body>
    <div class="background"></div>
    <div class="login-container">
        <div class="login-box">
            <h2>Sign Up</h2>
            
            <!-- Display success message if set -->
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Display error message if any -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="signup.php" method="POST">
    <div class="input-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <small id="emailHelp" class="help-text">Please enter a valid email address.</small>
    </div>

    <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
    </div>

    <div class="input-group" style="position: relative;">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    <small id="passwordHelp" class="help-text">Password must be at least 8 characters long and contain numbers.</small>
    <i id="togglePassword" class="fas fa-eye-slash" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
</div>
<!-- </form> -->

<script>
const passwordField = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

togglePassword.addEventListener('click', function () {
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;

    // Toggle between eye and eye-slash icons
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});
</script>


<style>
    .monkey-icon {
        cursor: pointer;
        font-size: 20px;
        position: absolute;
        right: 10px;
        top: 40%;
        transform: translateY(-50%);
    }
    
    .input-group {
        position: relative;
    }
</style>

                <button type="submit" class="btn">Sign Up</button>
            </form>
            <p class="signup-link">Already have an account? <a href="signin.php">Sign In</a></p>
        </div>
    </div>
    <script>
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordHelp = document.getElementById('passwordHelp');
        const emailHelp = document.getElementById('emailHelp');

        emailInput.addEventListener('input', () => {
            const value = emailInput.value;
            const isValidEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value);
            emailHelp.style.color = isValidEmail ? 'green' : 'red';
        });

        // Password validation remains unchanged
        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            const isValid = value.length >= 8 &&
                            /[a-z]/.test(value) &&
                            /[0-9]/.test(value); // Checks for numbers
            passwordHelp.style.color = isValid ? 'green' : 'red';
        });
    </script>
</body>
</html>
