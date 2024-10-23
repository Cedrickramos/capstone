<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-page {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            font-size: 18px;
            margin: 0 0 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 4px;
        }

        .sidebar ul li a:hover {
            background-color: #555;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f9f9f9;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="manage_users.php">Users</a></li>
                <li><a href="manage_attractions.php">Attractions</a></li>
                <li><a href="manage_reviews.php">Reviews</a></li>
                <li><a href="manage_destinations.php">Destinations</a></li>
                <li><a href="manage_admin.php">Admin</a></li>
            </ul>
        </div>
        
    </div>
</body>
</html>
