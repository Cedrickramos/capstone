<?php
session_start();
require_once "config.php";

$message = isset($_GET['message']) ? $_GET['message'] : '';
$verified = isset($_GET['verified']) ? $_GET['verified'] : '';

// Ensure the email and username are passed as parameters
if (!isset($_GET['email']) || !isset($_GET['username'])) {
    header("Location: signin.php");
    exit();
}

$email = $_GET['email'];
$username = $_GET['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        header("Location: change_password.php?email=" . urlencode($email) . "&username=" . urlencode($username) . "&message=password_mismatch");
        exit();
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare('UPDATE users SET upassword = ? WHERE uemail = ?');
    $stmt->bind_param('ss', $hashedPassword, $email);
    if ($stmt->execute()) {
        header("Location: signin.php?message=password_changed");
    } else {
        header("Location: change_password.php?email=" . urlencode($email) . "&username=" . urlencode($username) . "&message=update_failed");
    }
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .modal-header {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .modal-body p {
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            margin: 10px;
            background-color: #e0bf01;
            color: #000;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #fff188;
            color: #000;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="background"></div>

    <!-- Modal -->
    <div class="modal" id="messageModal">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader"></div>
            <div class="modal-body" id="modalBody"></div>
            <button class="btn" onclick="closeModal()">OK</button>
        </div>
    </div>

    <!-- Login Container -->
    <div class="login-container hidden" id="loginContainer">
        <div class="login-box">
            <h2>Change Password</h2>
            <form id="passwordForm" action="change_password.php?email=<?php echo urlencode($email); ?>&username=<?php echo urlencode($username); ?>" method="POST">
                <div class="input-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="button" class="btn" onclick="showConfirmationModal()">Change Password</button>
            </form>
            <p class="signup-link"><a href="signin.php">Back to Login</a></p>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-header">Confirm Password Change</div>
            <div class="modal-body">
                <p>Are you sure you want to change your password?</p>
                <button class="btn" onclick="submitForm()">Yes</button>
                <button class="btn" onclick="closeConfirmationModal()">No</button>
            </div>
        </div>
    </div>

    <script>
        // Get modal and other necessary elements
        const messageModal = document.getElementById("messageModal");
        const modalHeader = document.getElementById("modalHeader");
        const modalBody = document.getElementById("modalBody");
        const loginContainer = document.getElementById("loginContainer");
        const verified = "<?php echo $verified; ?>";
        const message = "<?php echo $message; ?>";

        // Show "Email Verified" popup if verified=1
        if (verified === "1") {
            modalHeader.textContent = "Email Verified!";
            modalBody.innerHTML = "<p>Your email has been successfully verified. You may now change your password.</p>";
            messageModal.style.display = "flex";
        }

        // Show other messages if present
        if (message === "password_mismatch") {
            modalHeader.textContent = "Error!";
            modalBody.innerHTML = "<p>Passwords do not match. Please try again.</p>";
            messageModal.style.display = "flex";
        } else if (message === "update_failed") {
            modalHeader.textContent = "Error!";
            modalBody.innerHTML = "<p>Failed to update password. Please try again.</p>";
            messageModal.style.display = "flex";
        } else if (message === "password_changed") {
            modalHeader.textContent = "Success!";
            modalBody.innerHTML = "<p>Password changed successfully. Please login.</p>";
            messageModal.style.display = "flex";
        }

        // Hide modal and show login container when modal is closed
        function closeModal() {
            messageModal.style.display = "none";
            if (verified === "1") {
                loginContainer.classList.remove("hidden");
            }
        }

        // Show confirmation modal
        function showConfirmationModal() {
            document.getElementById("confirmationModal").style.display = "flex";
        }

        // Close confirmation modal
        function closeConfirmationModal() {
            document.getElementById("confirmationModal").style.display = "none";
        }

        // Submit form on confirmation
        function submitForm() {
            document.getElementById("passwordForm").submit();
        }

        // Automatically display login container if no popup is needed
        if (verified !== "1") {
            loginContainer.classList.remove("hidden");
        }
    </script>
</body>
</html>
