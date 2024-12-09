<?php
session_start();

require_once "config.php";

// Check if a message is passed in the URL
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_POST['username'];
    $upassword = $_POST['password'];

    // Find the user
    $stmt = $conn->prepare('SELECT uid, upassword FROM users WHERE uname = ?');
    $stmt->bind_param('s', $uname);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and the password is correct
    if ($user) {
        if (password_verify($upassword, $user['upassword'])) {
            $_SESSION['uid'] = $user['uid'];
    
            // Check if the redirect_to parameter is set
            if (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) {
                header('Location: ' . $_GET['redirect_to']);
            } else {
                // Default redirect to index if no redirect parameter is passed
                header('Location: index.php');
            }
            exit();
        } else {
            // Handle password verification failure (optional)
            echo "Invalid password.";
        }
    } else {
        // Handle user not found (optional)
        echo "User not found.";
    }
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }
        .modal .modal-header {
            font-size: 18px;
            font-weight: bold;
        }
        .modal .modal-body {
            margin-top: 10px;
        }
        .modal .btn {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .modal .btn:hover {
            background-color: #45a049;
        }

        /* Display message styles inside the login form */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .monkey-icon {
            cursor: pointer;
            font-size: 20px;
            position: absolute;
            right: 10px;
            top: 60%;
            transform: translateY(-50%);
        }
        
        .input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="background"></div>

    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>

            <form action="signin.php" method="POST">
    <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <!-- Eye Icon for show/hide password -->
        <span id="togglePassword" class="eye-icon">👁️</span>
    </div>
    <!-- Display error message here if wrong username and/or password -->
    <?php if ($message == 'password_failed'): ?>
        <div class="error-message">Incorrect Password.</div>
    <?php elseif ($message == 'user_not_found'): ?>
        <div class="error-message">Incorrect Username.</div>
    <?php endif; ?>

    <style>
        .eye-icon {
            cursor: pointer;
            font-size: 20px;
            position: absolute;
            right: 10px;
            top: 60%;
            transform: translateY(-50%);
        }

        .input-group {
            position: relative;
        }
    </style>

    <script>
        // Get references to the password input field and the eye icon
        const passwordField = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        // Add event listener to toggle the password visibility
        togglePassword.addEventListener('click', function () {
            // Toggle the input type between "password" and "text"
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the eye icon between closed-eye and open-eye
            this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';  // 👁️ for open eye, 👁️‍🗨️ for closed eye
        });
    </script>


                <button type="submit" class="btn">Login</button>
            </form>
            <p class="signup-link">Don't have an account? <a href="signup.php">Sign up</a></p>
            <p><a href="forgot_password.php" style="color: grey;">Forgot your password?</a></p>
        </div>
    </div>

    <script>
        const message = "<?php echo $message; ?>";
        const modal = document.getElementById("messageModal");
        const modalHeader = document.getElementById("modalHeader");
        const modalBody = document.getElementById("modalBody");

        if (message === "password_mismatch") {
            modalHeader.textContent = "Error!";
            modalBody.textContent = "Passwords do not match. Please try again.";
            modal.style.display = "flex";
        } else if (message === "update_failed") {
            modalHeader.textContent = "Error!";
            modalBody.textContent = "Failed to update password. Please try again.";
            modal.style.display = "flex";
        } else if (message === "email_not_found") {
            modalHeader.textContent = "Error!";
            modalBody.textContent = "Email not found. Please try again.";
            modal.style.display = "flex";
        } else if (message === "invalid_password") {
            modalHeader.textContent = "Error!";
            modalBody.textContent = "Invalid password. Please try again.";
            modal.style.display = "flex";
        } else if (message === "user_not_found") {
            modalHeader.textContent = "Error!";
            modalBody.textContent = "User not found. Please register.";
            modal.style.display = "flex";
        } else if (message === "password_changed") {
            modalHeader.textContent = "Success!";
            modalBody.textContent = "Password changed successfully. Please login.";
            modal.style.display = "flex";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>
</body>
</html>
