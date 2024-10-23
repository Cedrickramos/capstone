<?php
require_once "navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mabuhay, Laguna</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background-image"></div>
        <div class="content">
            <h1 style="font-size: 80px"> Mabuhay, Laguna</h1>
            <h2>Laguna is a province in the Calabarzon region of the island of Luzon in the Philippines, wrapped around the large lake Laguna de Bay. It is one of the most progressive, historical and cultural provinces in the Philippines.</h2>
            <!-- <div class="btn"> -->
                <br><br><br>
                <a href="attractions.php" class="btn"><h1>Explore Destinations</h1></a>
           <!-- </div> -->
        </div>
    <!-- </div> -->

    <div class="content-sections">
            <h1>Welcome to AccompanyMe!</h1>
            <p>Discover the essence of Laguna with AccompanyMe, where we are dedicated to enhancing your travel experiences through personalized tour recommendation and unforgettable adventures. 
            Whether you crave cultural immersion, or a natural wonders, AccompanyMe is your gateway to making every journey in Laguna truly memorable.</p>
            <div class="buttons">
                <!-- <a href="attractions.php" class="btn">Explore Destinations</a> -->
            </div>
        <br>

        <br>
        </br>
    </div>

    <script>
    const images = [
        'attractions/background1.jpg', 
        'attractions/background2.jpg', 
        'attractions/background.jpg'  
    ];

    let currentIndex = 0;
    const backgroundElement = document.querySelector('.background-image');

    // Immediately set the first background image on page load
    backgroundElement.style.backgroundImage = `url(${images[currentIndex]})`;

    function changeBackground() {
        currentIndex = (currentIndex + 1) % images.length;
        backgroundElement.style.opacity = 0; // Fade out
        setTimeout(() => {
            backgroundElement.style.backgroundImage = `url(${images[currentIndex]})`;
            backgroundElement.style.opacity = 1; // Fade in
        }, 1000); 
    }

    // Start the interval to change the background
    setInterval(changeBackground, 12000); // 12 seconds
</script>

   <?php
   require_once "footer.php";
   ?>
</body>
</html>
