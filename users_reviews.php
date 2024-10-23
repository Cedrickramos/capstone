<?php
require_once "config.php";
require_once "navbar.php";

// Check if the user is logged in
$uid = $_SESSION['uid'] ?? null; 
$uname = $_SESSION['uname'] ?? null; 

// Get the attr_id from the URL
$attr_id = $_GET['attr_id'] ?? '';

if (empty($attr_id)) {
    echo "Invalid attraction ID.";
    exit;
}

// Fetch attraction details based on attr_id, including the city
$stmt = $conn->prepare("
    SELECT a.*, c.city 
    FROM attractions a 
    JOIN cities c ON a.city_id = c.city_id 
    WHERE a.attr_id = ?
");
$stmt->bind_param("i", $attr_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $attraction = $result->fetch_assoc();
} else {
    echo "Attraction not found.";
    exit;
}

// Fetch reviews for the specific attraction
$review_stmt = $conn->prepare("
    SELECT r.rating, u.uname, r.message, r.images, r.created_at 
    FROM reviews r 
    JOIN users u ON r.uid = u.uid 
    WHERE r.attr_id = ?
    ORDER BY r.created_at DESC
");
$review_stmt->bind_param("i", $attr_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($attraction['attraction_name']); ?> Reviews</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .review-item {
            background-color: white;
            padding: 30px 60px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* border-left: 5px solid #3498db; */
        }
        .review-rating {
            color: #f39c12;
        }
        .review-images {
            margin-top: 10px;
        }
        .review-images img {
            border-radius: 4px;
            margin-right: 15px;
            transition: transform 0.2s ease;
        }
        .review-images img:hover {
            transform: scale(1.1);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <br>
    <?php require_once "back.php"; ?>

    <div class="container">
        <h1>Reviews for <?php echo htmlspecialchars($attraction['attraction_name']); ?> in <?php echo htmlspecialchars($attraction['city']); ?></h1>
        
        <?php if ($reviews->num_rows === 0): ?>
            <p>No reviews found for this attraction.</p>
        <?php else: ?>
            <?php while ($row = $reviews->fetch_assoc()): ?>
                <div class="review-item">
                    <div class="review-rating">
                        <?php echo htmlspecialchars($row['rating']); ?> &#11088;
                    </div>
                    <p><strong>User:</strong> <?php echo htmlspecialchars($row['uname']); ?></p>
                    <p><strong>Review:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                    <div class="review-images">
                        <?php 
                            $images = json_decode($row['images']);
                            if (!empty($images)) {
                                foreach ($images as $image) {
                                    echo '<img src="' . htmlspecialchars($image) . '" alt="Review Image" style="max-width: 100px; height: auto;">';
                                }
                            }
                        ?>
                    </div>
                    <!-- <hr> -->
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>
