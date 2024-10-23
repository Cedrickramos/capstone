<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once "../config.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $city_id = $_POST['city_id'];  // Ensure 'city_id' is used in the form
    $attraction_name = $_POST['attraction_name'] ?? '';
    $entry_fee = $_POST['entry_fee'] ?? '';
    $parking = $_POST['parking'] ?? 'Unspecified';
    $dining = $_POST['dining'] ?? 'Unspecified';
    $operating_hours_from = $_POST['operating_hours_from'] ?? '';
    $operating_hours_to = $_POST['operating_hours_to'] ?? '';
    $contact_details = $_POST['contact_info'] ?? '';
    $historical_significance = $_POST['historical_significance'] ?? '';
    $description = $_POST['description'] ?? '';
    $map = $_POST['map'] ?? '';
    $weather = $_POST['weather'] ?? '';
    $longitude = $_POST['longitude'] ?? '';  // Add longitude
    $latitude = $_POST['latitude'] ?? '';    // Add latitude

    // Handling the image upload
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["attraction_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an image
    $check = getimagesize($_FILES["attraction_image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["attraction_image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Acceptable picture formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["attraction_image"]["tmp_name"], $target_file)) {
        
// Prepare INSERT INTO attractions and destinations table by referring to cities table datas
//attractions
$stmt_a = $conn->prepare("INSERT INTO attractions (attraction_name, entrance_fee, parking, dining, operating_hours_from, operating_hours_to, contact_details, history, description, image, city_id, longitude, latitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_a->bind_param("ssssssssssidd", $attraction_name, $entry_fee, $parking, $dining, $operating_hours_from, $operating_hours_to, $contact_details, $historical_significance, $description, $target_file, $city_id, $longitude, $latitude);

//destinations
$stmt_d = $conn->prepare("INSERT INTO destinations (attraction_name, image, map, weather, city_id, longitude, latitude) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt_d->bind_param("ssssidd", $attraction_name, $target_file, $map, $weather, $city_id, $longitude, $latitude);

            if ($stmt_a->execute()) {
                if ($stmt_d->execute()) {
                    echo "New attraction added successfully.";
                    header("Location: manage_attractions.php");
                    exit(); // It's good practice to exit after a redirect
                } else {
                    echo "Error inserting into destinations: " . $stmt_d->error;
                }
            } else {
                echo "Error inserting into attractions: " . $stmt_a->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Attraction</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="sideBar.css">

    <style>
        .form-container { 
            max-width: 600px; 
            margin: 0 auto; 
            padding: 20px; 
            background-color: #f9f9f9; 
            border-radius: 8px; 
        }

        .form-container label { 
            display: block; 
            margin-bottom: 10px; 
            font-weight: bold; 
        }

        .form-container input[type="text"], 
        .form-container textarea, 
        .form-container select { 
            width: 100%; 
            padding: 10px; 
            margin-bottom: 20px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
        
        .form-container input[type="file"] { 
            margin-bottom: 20px; 
        }
       
        .form-container input[type="submit"] { 
            background-color: #333; 
            color: #fff; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }

        .form-container input[type="submit"]:hover { 
            background-color: #555; 
        }
    </style>
</head>

<body>
    <div class="admin-page">
        <?php require_once "sideBar.php"; ?>
        <div class="main-content">
            <h2>Add New Attraction</h2>
            <div class="form-container">
            <form action="add_attractions.php" method="POST" enctype="multipart/form-data">
            <select name="city_id" required>
                        <option value="">Select a city</option>
                        <?php
                        // Fetch cities from database to populate the dropdown
                        $sql_cities = "SELECT * FROM cities";
                        $result_cities = $conn->query($sql_cities);
                        while ($row = $result_cities->fetch_assoc()) {
                            echo '<option value="' . $row['city_id'] . '">' . htmlspecialchars($row['city']) . '</option>';
                        }
                        ?>
                    </select>
                    <input type="text" name="attraction_name" placeholder="Attraction Name" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="5"></textarea>

                    <label for="entry_fee">Entry Fee:</label>
                    <input type="text" id="entry_fee" name="entry_fee">

                    <label for="parking">Parking:</label>
                    <select id="parking" name="parking">
                        <option value="Available">Available</option>
                        <option value="Not Available">Not Available</option>
                        <option value="Unspecified">Unspecified</option>
                    </select>

                    <label for="dining">Dining:</label>
                    <select id="dining" name="dining">
                        <option value="Available">Available</option>
                        <option value="Not Available">Not Available</option>
                        <option value="Unspecified">Unspecified</option>
                    </select>

                    <label for="operating_hours_from">Operating Hours:</label>
                    <input type="text" id="operating_hours_from" placeholder="am" name="operating_hours_from">
                    <input type="text" id="operating_hours_to" placeholder="pm" name="operating_hours_to">

                    <label for="contact_info">Contact Info:</label>
                    <input type="text" id="contact_info" name="contact_info">

                    <label for="historical_significance">Historical Significance:</label>
                    <textarea id="historical_significance" name="historical_significance" rows="5"></textarea>

                    <label for="longitude">Longitude:</label>
                    <input type="text" id="longitude" name="longitude" required>

                    <label for="latitude">Latitude:</label>
                    <input type="text" id="latitude" name="latitude" required>

                    <label for="attraction_image">Image:</label>
                    <input type="file" id="attraction_image" name="attraction_image" required>

                    <input type="submit" name="add_attraction" value="Add Attraction">
                </form>
            </div>
        </div>
    </div>
</body>
</html>