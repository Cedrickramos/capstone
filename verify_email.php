<?php
require_once "config.php";

// Get token from URL
$token = $_GET['token'];

// Check if the token exists in the database
$stmt = $conn->prepare('SELECT uid FROM users WHERE verification_token = ?');
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Token is valid, verify the user
    $updateStmt = $conn->prepare('UPDATE users SET is_verified = 1 WHERE verification_token = ?');
    $updateStmt->bind_param('s', $token);
    $updateStmt->execute();

    // Redirect to signin.php with a success message
    header("Location: signin.php?message=verified");
} else {
    // Invalid token, redirect with error message
    header("Location: signin.php?message=invalid_token");
}
?>
