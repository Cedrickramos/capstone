<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if admin has not yet logged in
    exit();
}

// db connection
require_once "../config.php"; 

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to manage_admin.php with success message
        header("Location: manage_admin.php?message=Admin deleted successfully");
    } else {
        // Redirect to manage_admin.php with error message
        header("Location: manage_admin.php?error=Failed to delete admin");
    }

    $stmt->close();
} else {
    // Redirect to manage_admin.php with error message if ID is not valid
    header("Location: manage_admin.php?error=Invalid admin ID");
}

$conn->close();
?>
