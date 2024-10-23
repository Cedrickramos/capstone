<?php
session_start();

// require_once "../config.php";
require_once "sideBar.php";

// Ensure admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// session_start();
$conn = new mysqli('localhost', 'root', '', 'accompanyme'); 

// kesug
// $conn = new mysqli('sql307.infinityfree.com', 'if0_36896748', 'rzQg0dnCh2BT', 'if0_36896748_accompanyme');

// infinity
// $conn = new mysqli('sql202.infinityfree.com', 'if0_37495817', 'TQY8mKoPDq ', 'if0_37495817_accompanyme');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reviews with user and attraction details
$sql = "SELECT r.rating, u.uname, c.city, a.attraction_name, r.message, r.images, r.created_at 
        FROM reviews r 
        JOIN users u ON r.uid = u.uid 
        JOIN attractions a ON r.attr_id = a.attr_id 
        JOIN cities c ON a.city_id = c.city_id 
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);
?>

<style>
    .add-button:hover { background-color: #555; }
    .table-container { margin: 20px; }
    .table-container table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .table-container th, .table-container td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    .table-container th { background-color: #333; color: #fff; }
    .table-container tr:nth-child(even) { background-color: #f2f2f2; }
    .update-button { background-color: #333; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; text-align: center; display: inline-block; text-decoration: none; }
    .update-button:hover { background-color: #555; }
</style>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Rating</th>
                <th>Username</th>
                <th>City</th>
                <th>Attraction Name</th>
                <th>Message</th>
                <th>Images</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['rating']); ?></td>
                <td><?php echo htmlspecialchars($row['uname']); ?></td>
                <td><?php echo htmlspecialchars($row['city']); ?></td>
                <td><?php echo htmlspecialchars($row['attraction_name']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td>
                    <?php 
                    $images = json_decode($row['images']);
                    if (!empty($images)) {
                        foreach ($images as $image) {
                            echo '<img src="../' . htmlspecialchars($image) . '" alt="Review Image" style="max-width: 100px; height: auto;">';
                        }
                    }
                    ?>
                </td>
                <td><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></td> <!-- for better date time reading -->
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
