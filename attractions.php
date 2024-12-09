<?php
require_once "navbar.php";
require_once "config.php";

// Set the correct content-type header
// header('Content-Type: text/html; charset=UTF-8');

// Ensure the database connection uses UTF-8
$conn->set_charset("utf8mb4");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

//cities boxes
$cities = [
    "alaminos" => ["name" => "Alaminos", "image" => "attractions/alaminos.jpg"],
    "bay" => ["name" => "Bay", "image" => "attractions/bay.jpg"],
    "biñan" => ["name" => "Biñan", "image" => "attractions/binan.jpg"],
    "cabuyao" => ["name" => "Cabuyao", "image" => "attractions/cabuyao.jpg"],
    "calamba" => ["name" => "Calamba", "image" => "attractions/calamba.jpg"],
    "calauan" => ["name" => "Calauan", "image" => "attractions/calauan.jpg"],
    "cavinti" => ["name" => "Cavinti", "image" => "attractions/cavinti.jpg"],
    "kalayaan" => ["name" => "Kalayaan", "image" => "attractions/kalayaan.jpg"],
    "liliw" => ["name" => "Liliw", "image" => "attractions/liliw.jpg"],
    "los_baños" => ["name" => "Los Baños", "image" => "attractions/los_baños.jpg"],
    "lumban" => ["name" => "Lumban", "image" => "attractions/lumban.jpg"],
    "luisiana" => ["name" => "Luisiana", "image" => "attractions/luisiana.JPG"],
    "mabitac" => ["name" => "Mabitac", "image" => "attractions/mabitac.jpg"],
    "magdalena" => ["name" => "Magdalena", "image" => "attractions/magdalena.jpg"],
    "nagcarlan" => ["name" => "Nagcarlan", "image" => "attractions/nagcarlan.jpg"],
    "paete" => ["name" => "Paete", "image" => "attractions/paete.JPG"],
    "pagsanjan" => ["name" => "Pagsanjan", "image" => "attractions/pagsanjan.jpg"],
    "pakil" => ["name" => "Pakil", "image" => "attractions/pakil.jpg"],
    "pila" => ["name" => "Pila", "image" => "attractions/pila.jpg"],
    "rizal" => ["name" => "Rizal", "image" => "attractions/rizal.jpg"],
    "san_pablo" => ["name" => "San Pablo", "image" => "attractions/san_pablo.JPG"],
    "san_pedro" => ["name" => "San Pedro", "image" => "attractions/san_pedro.jpg"],
    "santa_cruz" => ["name" => "Santa Cruz", "image" => "attractions/santa_cruz.JPG"],
    "santa_maria" => ["name" => "Santa Maria", "image" => "attractions/santa_maria.JPG"],
    "santa_rosa" => ["name" => "Santa Rosa", "image" => "attractions/santa_rosa.JPG"],
    "siniloan" => ["name" => "Siniloan", "image" => "attractions/siniloan.JPG"],
    "victoria" => ["name" => "Victoria", "image" => "attractions/victoria.jpg"]
];

$selected_city = $_GET['city'] ?? '';
$search_query = strtolower(trim($_GET['search'] ?? ''));

// Star rating function
function displayStarRating($avg_rating) {
    $fullStars = floor($avg_rating);
    $emptyStars = 5 - $fullStars;
    return str_repeat('<span style="color: orange;">&#9733;</span>', $fullStars) . 
           str_repeat('<span style="color: black;">&#9733;</span>', $emptyStars);
}

// City selection logic
if (!empty($selected_city) && array_key_exists($selected_city, $cities)) {
    $city_name = $cities[$selected_city]['name'];
    $stmt = $conn->prepare("SELECT city_id FROM cities WHERE city = ?");
    $stmt->bind_param("s", $city_name);
    $stmt->execute();
    $city_result = $stmt->get_result();

    if ($city_result->num_rows > 0) {
        $city_id = $city_result->fetch_assoc()['city_id'];

        // Fetch attractions with optional search query
        $sql = "SELECT a.attr_id, a.attraction_name, a.description, a.image, IFNULL(AVG(r.rating), 0) AS avg_rating 
                FROM attractions a 
                LEFT JOIN reviews r ON a.attr_id = r.attr_id 
                WHERE a.city_id = ? AND a.is_deleted = 0";
                
        $params = [$city_id];
        if (!empty($search_query)) {
            $sql .= " AND LOWER(a.attraction_name) LIKE ?";
            $params[] = '%' . $search_query . '%';
        }
        $sql .= " GROUP BY a.attr_id";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        $stmt->execute();
        $attractions = $stmt->get_result();
    } else {
        $invalid_city = true;
    }
} else {
    // Filter cities based on search query
    $normalized_search_query = str_replace(['ñ', 'Ñ'], ['n', 'N'], strtolower(trim($_GET['search'] ?? '')));
    $filtered_cities = empty($normalized_search_query) 
        ? $cities 
        : array_filter($cities, function($city) use ($normalized_search_query) {
            $normalized_city_name = str_replace(['ñ', 'Ñ'], ['n', 'N'], strtolower($city['name']));
            return strpos($normalized_city_name, $normalized_search_query) !== false;
        });
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (!empty($selected_city) && empty($invalid_city)) ? htmlspecialchars($cities[$selected_city]['name']) . " - AccompanyMe" : "Attractions - AccompanyMe"; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin: 20px;
        }

        .search-bar input[type="text"] {
            width: 300px;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 8px;
            margin-right: 10px;
            transition: border-color 0.3s ease;
        }

        .search-bar input[type="text"]:focus {
            border-color: green;
            outline: none;
        }

        .search-bar button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #28a745;
        }

        .city-image-bg {
            background-size: cover;
            background-position: center;
            height: 350px;
            position: relative;
            z-index: 1;
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #FFF;
            text-shadow: 4px 4px 6px #000;
        }

        /* Events Section Styling */
        .events-section {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f8f8;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .events-section h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .event-item {
            margin-bottom: 20px;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .event-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .event-item h3 {
            font-size: 20px;
            margin-top: 10px;
        }

        .event-item p {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .attraction-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px;
        }

        .attraction-item {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 8px;
            transition: transform 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .attraction-item:hover {
            transform: scale(1.05);
        }

        .attraction-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 4px;
        }

                /* City List Styles */
                .city-list {
            text-align: center;
            margin: 40px 0;
        }
        .city-list h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }
        .city-list ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .city-list ul li {
            display: inline-block;
            width: 300px;
            overflow: hidden;
            border-radius: 8px;
            position: relative;
        }
        .city-list ul li a {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .city-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease; /* Zoom effect */
        }
        /* Zoom in on hover */
        .city-list ul li a:hover .city-image {
            transform: scale(1.1); 
        }
        .city-name {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            color: white;
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            z-index: 1; /* Text above the image */
            text-shadow: 5px 5px 8px #000; /* Outer shadow */
        }

        .page-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
            padding: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                grid-template-columns: 1fr;
            }

            .attraction-grid {
                grid-template-columns: 1fr;
            }

            .city-list ul {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php if (!empty($selected_city) && empty($invalid_city)): ?>
    <div class="city-image-bg" style="background-image: url('<?= htmlspecialchars($cities[$selected_city]['image']) ?>');">
        <?php require_once "back.php"; ?>

        <!-- City Name -->
        <div class="content">
            <h1 style="font-size: 60px"><?= htmlspecialchars($cities[$selected_city]['name']) ?></h1>
        </div>
    </div>

    <!-- Search Bar for Attractions -->
    <div class="search-bar">
        <form method="GET">
            <input type="hidden" name="city" value="<?= htmlspecialchars($selected_city) ?>">
            <input type="text" name="search" placeholder="Search attractions..." value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Page Content Section: Two Columns -->
    <div class="page-content">
        <!-- Events Column -->
        <div class="events-section">
            <h2>Upcoming Events</h2>
            <?php
            // Fetch events from the database
            $events_sql = "SELECT * FROM events WHERE city_id = ? ORDER BY event_date ASC LIMIT 5";  // Adjust based on your DB
            $stmt = $conn->prepare($events_sql);
            $stmt->bind_param("i", $city_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($event = $result->fetch_assoc()): ?>
                    <div class="event-item">
                        <img src="images/<?= htmlspecialchars($event['event_image']) ?>" alt="<?= htmlspecialchars($event['event_name']) ?>" />
                        <h3><?= htmlspecialchars($event['event_name']) ?></h3>
                        <p><?= htmlspecialchars($event['event_date']) ?></p>
                        <p><?= htmlspecialchars($event['details']) ?></p>
                    </div>
                <?php endwhile; 
            else: ?>
                <p>No upcoming events found.</p>
            <?php endif;
            $stmt->close();
            ?>
        </div>

        <!-- Attraction Grid Column -->
        <div>
            <div class="attraction-grid">
                <?php if (isset($attractions) && $attractions->num_rows > 0): ?>
                    <?php while ($attr = $attractions->fetch_assoc()): ?>
                        <a href="attraction_details.php?attr_id=<?= htmlspecialchars($attr['attr_id']) ?>" class="attraction-item">
                            <img src="images/<?= htmlspecialchars($attr['image']) ?>" alt="<?= htmlspecialchars($attr['attraction_name']) ?>">
                            <h3><?= htmlspecialchars($attr['attraction_name']) ?></h3>
                            <p><?= htmlspecialchars($attr['description']) ?></p>
                            <div><?= displayStarRating($attr['avg_rating']) ?></div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No attractions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php elseif (isset($invalid_city)): ?>
    <p style="text-align: center; color: red;">Invalid city selected.</p>
<?php else: ?>
    <!-- Display city list inside grid layout -->
    <div class="city-list">
        <h1>Select a City</h1>
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Search cities..." value="<?= htmlspecialchars($search_query) ?>" aria-label="Search cities">
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
        <ul>
            <?php if (!empty($filtered_cities)): ?>
                <?php foreach ($filtered_cities as $slug => $info): ?>
                    <li>
                        <a href="attractions.php?city=<?= htmlspecialchars($slug) ?>">
                            <img class="city-image" src="<?= htmlspecialchars($info['image']) ?>" alt="<?= htmlspecialchars($info['name']) ?>">
                            <div class="city-name"><?= htmlspecialchars($info['name']) ?></div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No cities match your search.</p>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>

</body>
</html>