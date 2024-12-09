<?php
require_once "config.php";

// Set the correct content-type header
header('Content-Type: text/html; charset=UTF-8');

// Ensure the database connection uses UTF-8
$conn->set_charset("utf8mb4");

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

// Fetch attraction details including longitude and latitude
$stmt = $conn->prepare("
    SELECT a.*, a.longitude, a.latitude 
    FROM attractions a 
    JOIN destinations d ON a.attraction_name = d.attraction_name 
    WHERE a.attr_id = ?
");
$stmt->bind_param("i", $attr_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $attraction = $result->fetch_assoc();
    $longitude = $attraction['longitude'];
    $latitude = $attraction['latitude'];   // From destinations
} else {
    echo "Attraction not found.";
    exit;
}

// Fetch weather data using WeatherAPI
$weatherApiKey = 'a7169d65775d4f55b6f104707242411'; 
$weatherUrl = "http://api.weatherapi.com/v1/current.json?key=$weatherApiKey&q=$longitude,$latitude";
$weatherResponse = file_get_contents($weatherUrl);
$weatherData = json_decode($weatherResponse, true);

$weatherDescription = $weatherData['current']['condition']['text'] ?? 'No weather data available';
$temperature = $weatherData['current']['temp_c'] ?? 'N/A';
$humidity = $weatherData['current']['humidity'] ?? 'N/A';
$weatherIcon = $weatherData['current']['condition']['icon'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($attraction['attraction_name']); ?> - Attraction Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>

.popup-overlay {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%; /* Full viewport height */
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
}

.popup-content {
    position: absolute;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 800px;
    max-height: 80vh; /* Ensure the content stays within the viewport height */
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    z-index: 1001;
    overflow-y: auto; /* Allows scrolling within the modal if content overflows */
}

.popup-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #333;
}

/* Ensure the reviews container is scrollable */
#reviews-container {
    max-height: 60vh; /* Limit the height of the reviews container */
    overflow-y: auto; /* Enable scrolling if the content exceeds max height */
}


        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .attraction-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative; /* Added to allow positioning of child elements inside it */
        }

        .weather-info {
            position: absolute;
            width: 230px;
            top: 40px;
            right: 2px;
            background-color: white;
            opacity: 90%;
            padding: 5px 10px 3px 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.9);
            text-align: center;
            z-index: 10;
            transition: opacity 0.3s ease-in-out;
        }

        .weather-info img {
            width: 70px;
            height: 70px;
            margin-bottom: 10px; /* Optional, add some space below the icon */
        }

        #weather-content {
            display: block;
        }

        #weather-content.hidden {
            display: none;
        }

        /* Toggle button positioned at the top-right corner */
        .toggle-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #333;
            position: absolute;
            top: 1px;
            right: 1px; /* Position at the top-right corner */
            padding: 5px;
        }

        .toggle-btn:focus {
            outline: none;
        }

        .attraction-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative; /* This ensures the .weather-info is placed relative to this element */
        }
        .attraction-description {
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        .attraction-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }
        .adbutton {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-top: 10px;
            right: 500px;
        }
        .adbutton:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <br>
    <?php require_once "back.php"; ?>

    <div class="main-container">
        <div class="attraction-header">
            <h1><?php echo htmlspecialchars($attraction['attraction_name']); ?></h1>
            
            <img class="attraction-image" src="images/<?php echo htmlspecialchars($attraction['image']); ?>" alt="<?php echo htmlspecialchars($attraction['attraction_name']); ?>">
            
            <!-- Directions Link -->
            <?php if (!empty($longitude) && !empty($latitude)): ?>
                <a id="get-directions" class="adbutton" href="amap.php?longitude=<?php echo urlencode($longitude); ?>&latitude=<?php echo urlencode($latitude); ?>">Get Directions</a>
            <?php else: ?>
                <p>Coordinates not available for this attraction.</p>
            <?php endif; ?>

             <div class="weather-info">
                <h3><?php echo htmlspecialchars($attraction['attraction_name']); ?> Weather</h3>
                <button id="toggle-weather" class="toggle-btn">&#9650;</button>

                <div id="weather-content" class="hidden"> <!-- Add 'hidden' class here to start hidden -->
            <?php
                if (!empty($weatherIcon)) {
                    echo '<img src="https:' . $weatherIcon . '" alt="Weather Icon">';
                } else {
                    echo '<p>No weather icon available.</p>';
                }
            ?>

                <p><h3><?php echo $weatherDescription; ?></h3></p>
                <p><strong>Temperature:</strong> <?php echo $temperature; ?>°C</p>
                <p><strong>Humidity:</strong> <?php echo $humidity; ?>%</p>
            </div>
        </div>
            </div>

        <div class="attraction-details">
            <div class="attraction-description">
                <h2>Description</h2>
                <p><?php echo nl2br(htmlspecialchars($attraction['description'])); ?></p>
            </div>
            <div class="attraction-description">
                <h2>Details</h2>
                <p><strong>Entrance Fee:</strong> <?php echo htmlspecialchars($attraction['entrance_fee']); ?></p>
                <p><strong>Parking:</strong> <?php echo htmlspecialchars($attraction['parking']); ?></p>
                <!-- <p><strong>Dining:</strong> <?//php echo htmlspecialchars($attraction['dining']); ?></p> -->
                <p><strong>Operating Hours:</strong> <?php echo htmlspecialchars($attraction['operating_hours']); ?></p>
                <p><strong>History:</strong> <?php echo nl2br(htmlspecialchars($attraction['history'])); ?></p>
                </div>
        </div>
        
<?php if ($uid): ?>
    <a href="reviews.php?attr_id=<?php echo $attr_id; ?>" class="adbutton">Review <?php echo htmlspecialchars($attraction['attraction_name']); ?></a>
<?php else: ?>
    <!-- <p><em><a href="signin.php" style="color: black">Login</a> to leave a review.</em></p> -->
    <p><em><a href="signin.php?redirect_to=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" style="color: black">Login</a> to leave a review.</em></p>

<?php endif; ?>

<!------------------------------------------------------------------------------------ users reviews --------------------------------------------------------------------------------------------->
<button id="show-reviews" class="adbutton">Show Reviews</button>
    <!-- </div> -->

    <!-- Popup structure -->
    <div class="popup-overlay" id="reviews-popup">
        <div class="popup-content">
            <button class="popup-close" onclick="togglePopup()">×</button>
            <div id="reviews-container">
                <p>Loading reviews...</p>
            </div>
        </div>
    </div>

    <script>
        const showReviewsButton = document.getElementById('show-reviews');
        const reviewsPopup = document.getElementById('reviews-popup');
        const reviewsContainer = document.getElementById('reviews-container');

        // Function to toggle popup visibility
        function togglePopup() {
            const isVisible = reviewsPopup.style.display === 'block';
            reviewsPopup.style.display = isVisible ? 'none' : 'block';
        }

        // Function to fetch and display reviews
        function fetchReviews() {
    const attrId = "<?php echo $attr_id; ?>";
    fetch(`users_reviews.php?attr_id=${attrId}`)
        .then(response => response.text())
        .then(data => {
            reviewsContainer.innerHTML = data;
            // After loading reviews, ensure the modal adjusts its height
            const popupContent = document.querySelector('.popup-content');
            popupContent.style.height = 'auto'; // Adjust to fit content
        })
        .catch(error => {
            reviewsContainer.innerHTML = '<p>Error loading reviews. Please try again later.</p>';
            console.error('Error:', error);
        });
}


        // Show popup and load reviews on button click
        showReviewsButton.addEventListener('click', () => {
            togglePopup();
            fetchReviews();
        });
    </script>

    <!-- image popups -->
    <script>
        let currentImageIndex = 0;
        let imagesArray = [];
        let fullMessageText = '';

        function toggleText(element) {
        const shortText = element.closest('.review-item').querySelector('.short-text');
        const fullText = element.closest('.review-item').querySelector('.full-text');

        if (shortText.style.display === 'none') {
            shortText.style.display = 'inline';
            fullText.style.display = 'none';
        } else {
            shortText.style.display = 'none';
            fullText.style.display = 'inline';
        }
    }

        function handlePopup(element) {
            const images = JSON.parse(element.getAttribute('data-images'));
            const index = parseInt(element.getAttribute('data-index'), 10);
            const message = element.getAttribute('data-message');
            showPopup(images, index, message);
        }

        function showPopup(images, index, message) {
            imagesArray = images;
            currentImageIndex = index;
            fullMessageText = message;
            updatePopup();
            document.getElementById('imagePopup').style.display = 'block';
            document.body.classList.add('popup-active'); // Add blur
        }
        function updatePopup() {
            document.getElementById('popupImage').src = imagesArray[currentImageIndex];
            // document.getElementById('popupMessage').innerText = fullMessageText;
        }

        function hidePopup() {
            document.getElementById('imagePopup').style.display = 'none';
            document.body.classList.remove('popup-active'); // Remove blur
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % imagesArray.length;
            updatePopup();
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + imagesArray.length) % imagesArray.length;
            updatePopup();
        }
    </script>

    <!------------------------------------------------------------------------------------ users reviews --------------------------------------------------------------------------------------------->

    <script>
        // JavaScript to toggle weather visibility
document.getElementById('toggle-weather').addEventListener('click', function() {
    var weatherContent = document.getElementById('weather-content');
    var button = document.getElementById('toggle-weather');
    
    // Toggle the visibility of the weather content
    if (weatherContent.classList.contains('hidden')) {
        weatherContent.classList.remove('hidden');
        button.innerHTML = '&#9660;'; // Down arrow
    } else {
        weatherContent.classList.add('hidden');
        button.innerHTML = '&#9650;'; // Up arrow
    }
});

    </script>
</body>
</html>
