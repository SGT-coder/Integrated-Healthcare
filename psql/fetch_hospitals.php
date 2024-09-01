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
$sql = "SELECT name, specialties, location, description, emergency_services, image_path FROM hospital WHERE popular = 1";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare the output as JSON
$hospitals = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Use a default image path if the image_path is empty
        $image = !empty($row['image_path']) ? $row['image_path'] : 'uploads/default_hospital.webp';

        // Prepare hospital data
        $hospital = array(
            'name' => $row['name'],
            'specialties' => $row['specialties'],
            'location' => $row['location'],
            'description' => $row['description'],
            'emergency_services' => $row['emergency_services'],
            'image' => $image
        );

        // Add hospital data to the array
        $hospitals[] = $hospital;
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($hospitals);

// Close the database connection
$conn->close();
?>
