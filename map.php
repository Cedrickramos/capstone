<?php
// Get latitude and longitude from URL parameters
$destination_longitude = $_GET['longitude'] ?? null;
$destination_latitude = $_GET['latitude'] ?? null;

if (!$destination_longitude || !$destination_latitude) {
    echo "Coordinates not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
<?php require_once "navbar.php"; ?>
<h2>Map Location</h2>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script>
// Initialize the map
var map = L.map('map').setView([0, 0], 2); // Set an initial view

// Add OpenStreetMap tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

// Add a marker for the destination
var destinationLatLng = [<?php echo htmlspecialchars($destination_latitude); ?>, <?php echo htmlspecialchars($destination_longitude); ?>];
var destinationMarker = L.marker(destinationLatLng).addTo(map);
destinationMarker.bindPopup('<b>Your Destination</b>').openPopup();

// Function to handle location found
function onLocationFound(e) {
    var userMarker = L.marker(e.latlng).addTo(map);
    userMarker.bindPopup("You are here").openPopup();

    // Route from the user's location to the destination
    L.Routing.control({
        waypoints: [
            e.latlng, // User's location
            destinationLatLng // Destination location
        ],
        routeWhileDragging: true,
        geocoder: L.Control.Geocoder.nominatim(),
        language: 'en',
        units: 'metric'
    }).addTo(map);

    // Set map view to the user's location
    map.setView(e.latlng, 15); // Adjust zoom level as needed
}

// Function to handle location errors
function onLocationError(e) {
    alert(e.message);
}

// Request the user's location
map.locate({ setView: true, maxZoom: 15 });
map.on('locationfound', onLocationFound);
map.on('locationerror', onLocationError);
</script>
<?php require_once "footer.php"; ?>
</body>
</html>
