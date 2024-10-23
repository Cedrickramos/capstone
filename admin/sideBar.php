<style>

.admin-page {
    display: flex;
    height: 650vh;
    /* overflow: hidden; */
}

.sidebar {
    width: 200px;
    background-color: #333;
    color: #fff;
    display: flex;
    flex-direction: column;
    padding: 5px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 1000; /* Ensure it's above other content */
    pointer-events: auto; /* Ensure pointer events are enabled */
}

/* Sidebar visible on hover */
.sidebar.visible {
    transform: translateX(0);
}

/* Remove bullets from sidebar list */
.sidebar ul {
    list-style-type: none; /* Remove bullets */
    padding: 0; /* Remove padding */
    margin: 0; /* Remove margin */
}

/* Sidebar link styling */
.sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 15px;
    display: block;
    border-radius: 4px;
}

/* Sidebar link hover effect */
.sidebar a:hover {
    background-color: #555;
}

/* Main content styling */
.main-content {
    flex: 3;
    padding: 20px;
    background-color: #f9f9f9;
    margin-left: 0; /* Full width of the page when sidebar is hidden */
    z-index: 1; /* Ensure it's below the sidebar */
    transition: margin-left 0.10s ease;
}
</style>

<script>
document.addEventListener('mousemove', function(event) {
    const sidebar = document.querySelector('.sidebar');
    if (event.clientX < 50) {
        sidebar.classList.add('visible');
    } else {
        sidebar.classList.remove('visible');
    }
});

</script>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="manage_users.php">Users</a></li>
        <li><a href="manage_attractions.php">Attractions</a></li>
        <li><a href="manage_destinations.php">Destinations</a></li>
        <li><a href="manage_reviews.php">Reviews</a></li>
        <li><a href="manage_admin.php">Admin</a></li>
        <br>
        <hr></hr>
        </br>
        <li><a href="logout_admin.php">Logout</a></li>
    </ul>
</div>
