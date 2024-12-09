<?php
session_start();
require_once "config.php";

$message = isset($_GET['message']) ? $_GET['message'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare('SELECT uid, uname FROM users WHERE uemail = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        header("Location: change_password.php?email=" . urlencode($email) . "&username=" . urlencode($user['uname']));
        exit();
    } else {
        // header("Location: forgot_password.php?message=email_not_found");
        // exit();
        header("Location: change_password.php?email=" . urlencode($email) . "&username=" . urlencode($user['uname']) . "&verified=1");
exit();

    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background"></div>

    <!-- Modal Popup for Success/Error Message -->
    <div class="modal" id="messageModal">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader"></div>
            <div class="modal-body" id="modalBody"></div>
            <!-- <button class="btn" onclick="closeModal()">OK</button> -->
        </div>
    </div>

    <div class="login-container">
        <div class="login-box">
            <h2>Forgot Password</h2>
            <form action="forgot_password.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn">Verify Email</button>
            </form>
            <p class="signup-link"><a href="signin.php">Back to Login</a></p>
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
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>
</body>
</html>
