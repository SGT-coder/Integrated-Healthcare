<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch hospitals with 24/7 emergency services from the database
$sql = "SELECT name, specialties, location, description, emergency_services, image_path FROM hospital WHERE 24_7 = 1";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare the output as JSON
$hospitals = array();
$default_image = 'uploads/default_hospital.webp'; // Define a default image path

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $image = $row['image_path'] ? $row['image_path'] : $default_image; // Use default image if none exists
        $hospital = array(
            'name' => $row['name'],
            'specialties' => $row['specialties'],
            'location' => $row['location'],
            'description' => $row['description'],
            'emergency_services' => $row['emergency_services'],
            'image' => $image
        );
        $hospitals[] = $hospital;
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($hospitals);

$conn->close();
?>
