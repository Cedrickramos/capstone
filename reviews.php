<?php
require_once "config.php";
session_start();

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// Get the attr_id from the URL
$attr_id = $_GET['attr_id'] ?? '';
$uid = $_SESSION['uid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $message = $_POST['message'];
    $images = [];
    $video = null;

    // Handle file uploads for images
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['images']['name'][$key];
            $file_tmp = $_FILES['images']['tmp_name'][$key];
            $file_path = "images/" . basename($file_name);

            // Move uploaded file to images/
            if (move_uploaded_file($file_tmp, $file_path)) {
                $images[] = $file_path;
            }
        }
    }
    
    // Handle video upload
    if (!empty($_FILES['video']['name'])) {
        $video_name = $_FILES['video']['name'];
        $video_tmp = $_FILES['video']['tmp_name'];
        $video_path = "videos/" . basename($video_name);
        
        // Move uploaded video to videos/ directory
        if (move_uploaded_file($video_tmp, $video_path)) {
            $video = $video_path;
        }
    }

    // Convert images array to JSON para mabisa
    $images_json = json_encode($images);
    
$stmt = $conn->prepare("INSERT INTO reviews (attr_id, uid, rating, message, images, video) VALUES (?, ?, ?, ?, ?, ?)");

$stmt->bind_param("iiisss", $attr_id, $uid, $rating, $message, $images_json, $video);

// Execute the statement
if ($stmt->execute()) {
    echo "Review submitted successfully!";
    // header("location: attraction_details.php?attr_id='attr_id");
    header("Location: attraction_details.php?attr_id=" . $attr_id);

} else {
    echo "Error submitting review: " . $stmt->error;
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Review</title>
    <link rel="stylesheet" href="styles.css">
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    color: #333;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Main container for the review form */
.main-container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    font-size: 28px;
    color: #333;
    margin-bottom: 40px;
}

label {
    font-size: 18px;
    font-weight: bold;
    display: block;
    margin-bottom: 10px;
    color: #444;
}

input[type="file"],
select,
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: #f9f9f9;
    box-sizing: border-box;
    transition: all 0.3s ease-in-out;
}

input[type="file"]:focus,
select:focus,
textarea:focus {
    border-color: #4CAF50;
    background-color: #fff;
}

textarea {
    resize: vertical;
    height: 150px;
}

button[type="submit"] {
    width: 100%;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    background-color: #333;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #555;
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    .main-container {
        padding: 20px;
        margin: 20px;
    }

    h1 {
        font-size: 24px;
    }
    
    button[type="submit"] {
        font-size: 16px;
        padding: 12px;
    }
}
    </style>
</head>
<body>
<div class="main-container">
    <h1>Leave a Review</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="rating">Rating:</label>
        <select name="rating" id="rating" required>
            <option value="">Select a rating</option>
            <option value="1" style="font-size: 20px;">&#11088;</option>
            <option value="2" style="font-size: 20px;">&#11088;&#11088;</option>
            <option value="3" style="font-size: 20px;">&#11088;&#11088;&#11088;</option>
            <option value="4" style="font-size: 20px;">&#11088;&#11088;&#11088;&#11088;</option>
            <option value="5" style="font-size: 20px;">&#11088;&#11088;&#11088;&#11088;&#11088;</option>
        </select>

        <label for="message">Message:</label>
        <textarea name="message" id="message" rows="5" required></textarea>

        <label for="images">Upload Images (max 5):</label>
        <input type="file" name="images[]" id="images" multiple accept="image/*">

        <label for="video">Upload Video (max 1):</label>
        <input type="file" name="video" id="video" accept="video/*">

        <button type="submit">Submit Review</button>
    </form>
</div>
</body>
</html>
