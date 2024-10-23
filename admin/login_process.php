<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'accompanyme'); 

// kesug
// $conn = new mysqli('sql307.infinityfree.com', 'if0_36896748', 'rzQg0dnCh2BT', 'if0_36896748_accompanyme');

// infinity
// $conn = new mysqli('sql202.infinityfree.com', 'if0_37495817', 'TQY8mKoPDq ', 'if0_37495817_accompanyme');



if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (empty($username) || empty($password)) {
    // echo "Please fill in all fields.";
    exit();
}

// Query to check credentials
$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    if (password_verify($password, $admin['password'])) {
        // Passwords match, proceed with login.
        $_SESSION['username'] = $username;  // Save username in session
        header("Location: manage_index.php");  // Redirect after successful login
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "Username not found.";
}

$stmt->close();
$conn->close();
?>