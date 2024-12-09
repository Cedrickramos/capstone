<?php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token']);
    $newPassword = trim($_POST['password']);

    // Validate token
    $stmt = $conn->prepare('SELECT uid, reset_expiry FROM users WHERE reset_token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && strtotime($user['reset_expiry']) > time()) {
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('UPDATE users SET upassword = ?, reset_token = NULL, reset_expiry = NULL WHERE uid = ?');
        $stmt->bind_param('si', $hashedPassword, $user['uid']);
        $stmt->execute();

        echo "Password successfully reset. <a href='signin.php'>Log in</a>";
    } else {
        echo "Invalid or expired token.";
    }
} elseif (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
