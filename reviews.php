<?php
require_once "config.php";
session_start();

// Set the correct content-type header
header('Content-Type: text/html; charset=UTF-8');

// Ensure user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['uid'];
$attr_id = $_GET['attr_id'] ?? null;
$resto_id = $_GET['resto_id'] ?? null;

// Redirect if both IDs are missing
if (!$attr_id && !$resto_id) {
    echo "Invalid review request. Please specify an attraction or restaurant.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $message = $_POST['message'];
    $existingImages = json_decode($_POST['existing_images'], true) ?? [];
    $images = $existingImages;

    // Directory for image uploads
    $uploadDir = __DIR__ . "/images/";

    // Handle image uploads (max 10 images total)
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if (count($images) < 10) { // Limit total to 10 images
                $fileName = basename($_FILES['images']['name'][$key]);
                $sanitizedFileName = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "", $fileName);
                $fileTmp = $_FILES['images']['tmp_name'][$key];
                $filePath = $uploadDir . $sanitizedFileName;

                // Move uploaded file
                if (move_uploaded_file($fileTmp, $filePath)) {
                    $images[] = "images/" . $sanitizedFileName; // Store relative path
                } else {
                    echo "Failed to upload {$fileName}. Please try again.<br>";
                }
            }
        }
    }

    // Convert images array to JSON
    $images_json = json_encode($images);

    // Insert review into the database
    $stmt = $conn->prepare("
        INSERT INTO reviews (attr_id, resto_id, uid, rating, message, images) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("iiisss", $attr_id, $resto_id, $uid, $rating, $message, $images_json);

    if ($stmt->execute()) {
        header("Location: review_sent.php?attr_id=" . ($attr_id ?? $resto_id));
        exit();
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
            margin: 0;
            padding: 0;
        }
        .container {
    max-width: 600px;
    margin: 100px auto 0px auto; /* Reduced bottom margin to 10px */
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}


        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .star-rating {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .star {
            font-size: 40px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.2s ease;
        }
        .star.selected {
            color: #FFD700;
            transform: scale(1.1);
        }
        textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        textarea:focus, input[type="file"]:focus {
            outline: none;
            border-color: #666;
        }
        button[type="submit"] {
            padding: 12px 20px;
            font-size: 18px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button[type="submit"]:hover {
            background-color: #555;
            transform: translateY(-2px);
        }
        button[type="submit"]:active {
            transform: translateY(0);
        }
        .image-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 10px 0;
    justify-content: center;
    position: relative;
    min-height: 120px; /* Set a minimum height to reserve space */
    border: 1px dashed #ccc; /* Optional: To give it a "dropzone" look */
    border-radius: 10px;
    align-items: center;
    background-color: #f9f9f9;
}

.image-preview img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-preview img:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

#placeholder-text {
    font-size: 14px;
    color: #888;
}

    </style>
</head>
<body>
<div class="container">
    <h1>Leave a Review</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="star-rating" id="star-rating">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <input type="hidden" name="rating" id="rating" required>
        <input type="hidden" name="existing_images" id="existing-images" value="[]">
        
        <label for="message">Message:</label>
        <textarea name="message" rows="5" placeholder="Write your review..." required></textarea>

        <label for="images">Upload Images (max 10):</label>
        <input type="file" id="images" name="images[]" accept="image/*" multiple>
        <div id="image-counter">Uploaded: 0/10</div>
        <div class="image-preview" id="image-preview">
            <p id="placeholder-text">No images uploaded yet.</p>
        </div>

        <button type="submit">Submit Review</button>
    </form>
</div>

<script>
const imagesInput = document.getElementById('images');
const imagePreview = document.getElementById('image-preview');
const imageCounter = document.getElementById('image-counter');
const placeholderText = document.getElementById('placeholder-text');
let selectedFiles = [];

imagesInput.addEventListener('click', (event) => {
    if (selectedFiles.length > 0) {
        const userConfirmed = confirm("You have selected images. Adding new images will clear the current ones. Do you want to continue?");
        if (!userConfirmed) {
            event.preventDefault(); // Cancel the action
        } else {
            clearImages();
        }
    }
});

imagesInput.addEventListener('change', (event) => {
    const files = Array.from(event.target.files);

    // Clear previous selections and update previews
    clearImages();

    files.forEach((file) => {
        if (selectedFiles.length < 10) {
            selectedFiles.push(file);

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            imagePreview.appendChild(img);
        }
    });

    updateCounter();
});

// Function to clear images and reset the preview
function clearImages() {
    selectedFiles = [];
    imagePreview.innerHTML = '';
    imagePreview.appendChild(placeholderText); // Re-add placeholder text
    updateCounter();
}

// Update counter display
function updateCounter() {
    imageCounter.textContent = `Uploaded: ${selectedFiles.length}/10`;
    placeholderText.style.display = selectedFiles.length === 0 ? 'block' : 'none';
}



// Star rating logic
const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('rating');

stars.forEach(star => {
    star.addEventListener('mouseover', () => {
        const value = star.getAttribute('data-value');
        highlightStars(value);
    });

    star.addEventListener('mouseout', () => {
        highlightStars(ratingInput.value);
    });

    star.addEventListener('click', () => {
        ratingInput.value = star.getAttribute('data-value');
    });
});

function highlightStars(value) {
    stars.forEach(star => {
        const starValue = star.getAttribute('data-value');
        if (starValue <= value) {
            star.classList.add('selected');
        } else {
            star.classList.remove('selected');
        }
    });
}
</script>
</body>
</html>
