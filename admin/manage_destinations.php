<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Initialize destinations array
$destinations = [];

// Handle form submission to add an attraction
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city_id = $_POST['city_id'] ?? null;
    $attraction_name = $_POST['attraction_name'] ?? null;

    // Validate city_id
    if (empty($city_id)) {
        $_SESSION['error'] = "City is required.";
        header("Location: manage_destinations.php");
        exit();
    }

    // Validate if city exists
    $sql_check_city = "SELECT * FROM cities WHERE city_id = ?";
    $stmt_check_city = $conn->prepare($sql_check_city);
    $stmt_check_city->bind_param("i", $city_id);
    $stmt_check_city->execute();
    $result_check_city = $stmt_check_city->get_result();

    if ($result_check_city->num_rows == 0) {
        die("Invalid city selected.");
    }

    // Insert the attraction
    $sql = "INSERT INTO attractions (city_id, attraction_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $city_id, $attraction_name);

    if ($stmt->execute()) {
        echo "Attraction added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch destinations
$sql_destinations = "
    SELECT d.dest_id, d.attraction_name, d.image, d.map, d.weather, d.longitude, d.latitude, c.city 
    FROM destinations d 
    JOIN cities c ON d.city_id = c.city_id
    JOIN attractions a ON d.attraction_name = a.attraction_name";
$result_destinations = $conn->query($sql_destinations);

if ($result_destinations->num_rows > 0) {
    while ($row = $result_destinations->fetch_assoc()) {
        $destinations[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Destinations</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .btn { background-color: #333; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; text-align: center; /*display: inline-block; */text-decoration: none; }
        .btn:hover { background-color: #555; }
        .add-button:hover { background-color: #555; }
        .table-container table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-container th, .table-container td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .table-container th { background-color: #333; color: #fff; }
        .table-container tr:nth-child(even) { background-color: #f2f2f2; }
        .table-container td img { max-width: 100px; height: auto; }
        .update-button { background-color: #333; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; text-align: center; display: inline-block; text-decoration: none; }
        .update-button:hover { background-color: #555; }
    </style>
</head>
<body>
    <div class="admin-page">
        <?php require_once "sideBar.php"; ?>
        <div class="main-content">
            <div class="table-container">
                <h2>Manage Destinations</h2>
                <table>
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Destination Name</th>
                            <th>Image</th>
                            <th>Map Link</th>
                            <th>Weather Info</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($destinations)): ?>
                            <tr>
                                <td colspan="6">No destinations found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($destinations as $destination): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($destination['city']); ?></td>
                                    <td><?php echo htmlspecialchars($destination['attraction_name']); ?></td>
                                    <td><img src="<?php echo htmlspecialchars($destination['image']); ?>" alt="<?php echo htmlspecialchars($destination['image']); ?>"></td>
                                    
                                    <!-- Construct the map URL dynamically -->
                                    <td>
                                        <?php if (!empty($destination['longitude']) && !empty($destination['latitude'])): ?>
                                            <p><b>Longitude:</b> <?php echo htmlspecialchars($destination['longitude']); ?></p>
                                            <hr>
                                            <p><b>Latitude:</b> <?php echo htmlspecialchars($destination['latitude']); ?></p>
                                            <a href="https://www.google.com/maps?q=<?php echo htmlspecialchars($destination['longitude']) . ',' . htmlspecialchars($destination['latitude']); ?>" target="_blank" class="btn">View on Map</a>
                                            <br>
                                            <br>

                                            <?php else: ?>
                                            No Map Available
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo htmlspecialchars($destination['weather']); ?></td>
                                    <td>
                                        <a href="edit_attraction.php?id=<?php echo $destination['dest_id']; ?>" class="update-button">Edit</a>
                                        <a href="delete_attraction.php?id=<?php echo $destination['dest_id']; ?>" class="update-button">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</body>
</html>
