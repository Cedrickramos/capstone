<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Fetch attractions data with city names
$sql = "SELECT a.*, c.city FROM attractions a
JOIN cities c ON a.city_id = c.city_id";
$result = $conn->query($sql);

if (!$result) {
die("Query failed: " . $conn->error);
}


$attractions = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attractions</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .add-button {
            display: inline-block;
            padding: 10px 15px;
            margin-bottom: 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            font-size: 16px;
            transition: background-color 0.3s;
            float: right;
        }

        .add-button:hover {
            background-color: #555;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-container th, .table-container td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .table-container th {
            background-color: #333;
            color: #fff;
        }

        .table-container tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-container th.city, .table-container td.city {
            width: 10%;
        }

        .table-container th.attraction-name, .table-container td.attraction-name {
            width: 15%;
        }

        .table-container th.description, .table-container td.description,
        .table-container th.history, .table-container td.history,
        .table-container th.amenities, .table-container td.amenities,
        .table-container th.other-info, .table-container td.other-info,
        .table-container th.image, .table-container td.image,
        .table-container th.actions, .table-container td.actions {
            width: 20%;
        }

        .table-container th {
            font-weight: bold;
        }

        .table-container td img {
            max-width: 100px;
            height: auto;
        }

        .update-button {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .update-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="admin-page">
        <?php require_once "sideBar.php"; ?>
        <div class="main-content">
            <a href="add_attractions.php" class="add-button">Add New Attraction</a>
            <div class="table-container">
                <h2>Manage Attractions</h2>
                <table>
                    <thead>
                        <tr>
                            <th class="city">City</th>
                            <th class="attraction-name">Attraction Name</th>
                            <th class="description">Description</th>
                            <th class="history">Historical Significance</th>
                            <th class="amenities">Amenities</th>
                            <th class="other-info">Other Information</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($attractions)): ?>
                            <tr>
                                <td colspan="7">No attractions found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($attractions as $attraction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($attraction['city']); ?></td>
                                    <td><?php echo htmlspecialchars($attraction['attraction_name']); ?></td>
                                    <td><?php echo htmlspecialchars($attraction['description']); ?></td>
                                    <td><?php echo htmlspecialchars($attraction['history']); ?></td>
                                    <td>
                                        <p><b>Parking:</b> <?php echo htmlspecialchars($attraction['parking']); ?></p>
                                        <hr>
                                        <p><b>Dining:</b> <?php echo htmlspecialchars($attraction['dining']); ?></p>
                                    </td>
                                    <td>
                                        <p><b>Entry Fee:</b> <?php echo htmlspecialchars($attraction['entrance_fee']); ?></p>
                                        <hr>
                                        <p><b>Operating Hours:</b> <?php echo htmlspecialchars($attraction['operating_hours_from']) . 'am - ' . htmlspecialchars($attraction['operating_hours_to']); ?>pm</p>
                                    </td>
                                    <td>
                                        <a href="edit_attraction.php?id=<?php echo $attraction['attr_id']; ?>" style="text-decoration: none; padding: 10px; background-color: #333; color: white; border-radius: 5px;">Edit</a>
                                       <br>
                                       <hr>
                                        <a href="delete_attraction.php?id=<?php echo $attraction['attr_id']; ?>" style="text-decoration: none; padding: 10px; background-color: #333; color: white; border-radius: 5px;">Delete</a>
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