<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if admin has not yet logged in
    exit();
}

require_once '../config.php'; 
// Fetch admin data
$sql = "SELECT * FROM admins"; 
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$admins = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="sideBar.css">

    <!-- for tables lang yung style na to -->
    <style>
        .table-container {
            margin-bottom: 20px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-container th, .table-container td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .table-container th {
            background-color: #333;
            color: #fff;
        }

        .table-container tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 4px;
            border: none;
            border-radius: 4px;
            color: #fff;
            background-color: #007bff;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<div class="admin-page">
    <?php
    require_once "sideBar.php"
    ?>
    <div class="main-content">
        <div class="table-container">
            <h2>Manage Admin</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['id']); ?></td>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td><?php echo htmlspecialchars($admin['password']); ?></td>
                            <td>
                                <!-- Edit button -->
                                <a href="edit_admin.php?id=<?php echo htmlspecialchars($admin['id']); ?>" class="btn">Edit</a>
                                
                                <!-- Delete button -->
                                <form action="delete_admin.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($admin['id']); ?>">
                                    <button type="submit" class="btn btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
