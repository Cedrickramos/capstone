<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once "../config.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_destination'])) {
    // Get form data
    $city = $_POST['city'];
    $attraction_name = $_POST['attraction_name'];
    $image_url = $_POST['image_url'];
    $description = $_POST['description'];

    // Insert query
    $sql = "INSERT INTO destinations (city, attraction_name, image_url, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $city, $attraction_name, $image_url, $description);

    if ($stmt->execute()) {
        // Redirect to manage_destinations.php after successful insertion
        header("Location: manage_destinations.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Add New Destination</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="sideBar.css">
    <style>
        .form-container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; }
        .form-container label { display: block; margin-bottom: 10px; font-weight: bold; }
        .form-container input[type="text"], .form-container textarea { width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; }
        .form-container input[type="submit"] { background-color: #333; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .form-container input[type="submit"]:hover { background-color: #555; }
    </style>
</head>
<body>
    <div class="admin-page">
        <?php require_once "sideBar.php"; ?>
        <div class="main-content">
            <h2>Add New Destination</h2>
            <div class="form-container">
                <form action="add_destinations.php" method="POST">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>

                    <label for="attraction_name">Attraction Name:</label>
                    <input type="text" id="attraction_name" name="attraction_name" required>

                    <label for="image_url">Image URL:</label>
                    <input type="file" id="image_url" name="image_url">

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="5"></textarea>

                    <input type="submit" name="add_destination" value="Add Destination">
                </form>
            </div>
        </div>
    </div>
</body>
</html>

