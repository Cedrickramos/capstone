<?php
require_once "navbar.php";
require_once "config.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

//cities boxes
$cities = [
    "alaminos" => ["name" => "Alaminos", "image" => "attractions/alaminos.jpg"],
    "banilan" => ["name" => "Banilan", "image" => "attractions/banilan.jpg"],
    "bay" => ["name" => "Bay", "image" => "attractions/bay.jpg"],
    "biñan" => ["name" => "Biñan", "image" => "attractions/binan.jpg"],
    "cabuyao" => ["name" => "Cabuyao", "image" => "attractions/cabuyao.jpg"],
    "calamba" => ["name" => "Calamba", "image" => "attractions/calamba.jpg"],
    "calauan" => ["name" => "Calauan", "image" => "attractions/calauan.jpg"],
    "canlubang" => ["name" => "Canlubang", "image" => "attractions/canlubang.jpg"],
    "cavinti" => ["name" => "Cavinti", "image" => "attractions/cavinti.jpg"],
    "del_remedio" => ["name" => "Del Remedio", "image" => "attractions/del_remedio.jpg"],
    "famy" => ["name" => "Famy", "image" => "attractions/famy.jpg"],
    "general_mariano_alvarez" => ["name" => "General Mariano Alvarez", "image" => "attractions/general_mariano_alvarez.jpg"],
    "kalayaan" => ["name" => "Kalayaan", "image" => "attractions/kalayaan.jpg"],
    "kay-anlog_calamba" => ["name" => "Kay-Anlog, Calamba", "image" => "attractions/kay-anlog_calamba.jpg"],
    "liliw" => ["name" => "Liliw", "image" => "attractions/liliw.jpg"],
    "los_baños" => ["name" => "Los Baños", "image" => "attractions/los_baños.jpg"],
    "lucban" => ["name" => "Lucban", "image" => "attractions/lucban.jpg"],
    "lumban" => ["name" => "Lumban", "image" => "attractions/lumban.jpg"],
    "luisiana" => ["name" => "Luisiana", "image" => "attractions/luisiana.jpg"],
    "mabitac" => ["name" => "Mabitac", "image" => "attractions/mabitac.jpg"],
    "magdalena" => ["name" => "Magdalena", "image" => "attractions/magdalena.jpg"],
    "makiling" => ["name" => "Makiling", "image" => "attractions/makiling.jpg"],
    "malitlit" => ["name" => "Malitlit", "image" => "attractions/malitlit.jpg"],
    "mamatid" => ["name" => "Mamatid", "image" => "attractions/mamatid.jpg"],
    "nagcarlan" => ["name" => "Nagcarlan", "image" => "attractions/nagcarlan.jpg"],
    "paete" => ["name" => "Paete", "image" => "attractions/paete.jpg"],
    "pagsanjan" => ["name" => "Pagsanjan", "image" => "attractions/pagsanjan.jpg"],
    "pakil" => ["name" => "Pakil", "image" => "attractions/pakil.jpg"],
    "parian" => ["name" => "Parian", "image" => "attractions/parian.jpg"],
    "pila" => ["name" => "Pila", "image" => "attractions/pila.jpg"],
    "pililla" => ["name" => "Pililla", "image" => "attractions/pililla.jpg"],
    "punta" => ["name" => "Punta", "image" => "attractions/punta.jpg"],
    "rizal" => ["name" => "Rizal", "image" => "attractions/rizal.jpg"],
    "san_pablo" => ["name" => "San Pablo City", "image" => "attractions/san_pablo.jpg"],
    "san_pedro_city" => ["name" => "San Pedro City", "image" => "attractions/san_pedro_city.jpg"],
    "santa_cruz" => ["name" => "Santa Cruz", "image" => "attractions/santa_cruz.jpg"],
    "santa_maria" => ["name" => "Santa Maria", "image" => "attractions/santa_maria.jpg"],
    "santa_rosa" => ["name" => "Santa Rosa", "image" => "attractions/santa_rosa.jpg"],
    "siniloan" => ["name" => "Siniloan", "image" => "attractions/siniloan.jpg"],
    "tanay" => ["name" => "Tanay", "image" => "attractions/tanay.jpg"],
    "turbina" => ["name" => "Turbina", "image" => "attractions/turbina.jpg"],
    "victoria" => ["name" => "Victoria", "image" => "attractions/victoria.jpg"]
];

$selected_city = $_GET['city'] ?? '';
$search_query = $_GET['search'] ?? '';

if (!empty($selected_city) && array_key_exists($selected_city, $cities)) {
    $stmt = $conn->prepare("SELECT city_id FROM cities WHERE city = ?");
    $stmt->bind_param("s", $cities[$selected_city]['name']);
    $stmt->execute();
    $city_result = $stmt->get_result();

    if ($city_result->num_rows > 0) {
        $city_row = $city_result->fetch_assoc();
        $city_id = $city_row['city_id'];

        $sql = "SELECT a.attr_id, a.attraction_name, a.description, a.image, 
                       IFNULL(AVG(r.rating), 0) AS avg_rating
                FROM attractions a
                LEFT JOIN reviews r ON a.attr_id = r.attr_id
                WHERE a.city_id = ?";

        if (!empty($search_query)) {
            $sql .= " AND LOWER(a.attraction_name) LIKE LOWER(?)";
        }

        $sql .= " GROUP BY a.attr_id";

        // Prepare and bind
        $stmt = $conn->prepare($sql);
        if (!empty($search_query)) {
            $search_query = '%' . $search_query . '%'; // Prepare for LIKE query
            $stmt->bind_param("is", $city_id, $search_query);
        } else {
            $stmt->bind_param("i", $city_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo '<p>Invalid city selected.</p>';
        exit;
    }
} else {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attractions - AccompanyMe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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
            transition: transform 0.3s ease; /*zoom*/
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
            font-size: 18px;
            z-index: 1; /* text above the image */
            text-shadow: 4px 4px 6px #000; /* Outer shadow */
        }
        .search-bar {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="city-list">
        <h1>Select a City</h1>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="hidden" name="city" value="<?php echo htmlspecialchars($selected_city); ?>">
                <!-- <label for="search">Search attractions:</label> -->
                <input type="text" id="search" name="search" placeholder="Search attractions..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <ul>
            <?php
            foreach ($cities as $city_slug => $city_info) {
                echo '<li>';
                echo '<a href="attractions.php?city=' . htmlspecialchars($city_slug) . '">';
                echo '<img class="city-image" src="' . htmlspecialchars($city_info['image']) . '" alt="' . htmlspecialchars($city_info['name']) . '">';
                echo '<div class="city-name"><h2>' . htmlspecialchars($city_info['name']) . '</h2></div>';
                echo '</a></li>';
            }
            ?>
        </ul>
    </div>
    <?php require_once "footer.php"; ?>
</body>
</html>


    <?php
    exit;
}
// 2nd DOCtype will redirect to attractions.php?city=chosen city
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cities[$selected_city]['name']); ?> - AccompanyMe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .city-image {
            background-size: cover;
            background-position: center;
            height: 300px;
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

        .attraction-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
        }

        .attraction-item:hover {
            transform: scale(1.05);
        }
        
        .attraction-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .attraction-grid span {
            font-size: 30;
        }

        .footer img {
            width: 150px; 
            height: auto; 
        }
    </style>
</head>
<body>
<div class="city-image" 
     style="background-image: url('<?php echo htmlspecialchars($cities[$selected_city]['image']); ?>');">
    <br>
    <?php require_once "back.php"; ?>
    <div class="content">
        <h1 style="font-size: 80px;"><?php echo htmlspecialchars($cities[$selected_city]['name']); ?></h1>
    </div>
</div>


    <div class="attraction-grid">
    <?php
    // Query to fetch attractions and their average ratings
    $sql = "SELECT a.attr_id, a.attraction_name, a.description, a.image, 
                   IFNULL(AVG(r.rating), 0) AS avg_rating
            FROM attractions a
            LEFT JOIN reviews r ON a.attr_id = r.attr_id
            WHERE a.city_id = ?  
            GROUP BY a.attr_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $city_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    // Function to display star rating
    function displayStarRating($avg_rating) {
        $output = '';
        $fullStars = floor($avg_rating);  // Number of full stars
        $halfStar = ($avg_rating - $fullStars >= 0.5) ? 1 : 0;  // Check if there's a half star
        $emptyStars = 5 - ($fullStars + $halfStar);  // Remaining stars are empty

        // Output full stars
        for ($i = 0; $i < $fullStars; $i++) {
            $output .= '<span style="color: orange;">&#9733;</span>';  // Full star
        }

        // Output empty stars
        for ($i = 0; $i < $emptyStars; $i++) {
            $output .= '<span style="color: black; font-size: 50;">&#9733;</span>';  // Empty star
        }

        return $output;
    }

    if ($result->num_rows > 0) {
        while ($attraction = $result->fetch_assoc()) {
            echo '<a style="text-decoration: none" href="attraction_details.php?attr_id=' . htmlspecialchars($attraction['attr_id']) . '" class="attraction-item">';
            echo '<img src="images/' . htmlspecialchars($attraction['image']) . '" alt="' . htmlspecialchars($attraction['attraction_name']) . '">';
            echo '<h3 style="color: black">' . htmlspecialchars($attraction['attraction_name']) . '</h3>';
            echo '<p style="color: gray">' . htmlspecialchars($attraction['description']) . '</p>';
            echo '<div>' . displayStarRating($attraction['avg_rating']) . '</div>';
            echo '</a>';
        }
    } else {
        echo '<p>No attractions found for this city.</p>';
    }

    $stmt->close();
    ?>
    </div>

    <?php require_once "footer.php"; ?>
</body>
</html>
