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

// Fetch popular doctors data from the database
$sql = "SELECT name, specialties, location, description, hospital, image_path FROM doctors WHERE popular = 1";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare the output as JSON
$doctors = array();
$default_image = 'uploads/OIP.jfif'; // Define a default image path

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $image = $row['image_path'] ? $row['image_path'] : $default_image; // Use default image if none exists
        $hospital = isset($row['hospital']) ? $row['hospital'] : 'Not specified'; // Handle missing hospital data

        $doctor = array(
            'name' => $row['name'],
            'specialties' => $row['specialties'],
            'location' => $row['location'],
            'description' => $row['description'],
            'hospital' => $hospital,
            'image' => $image
        );
        $doctors[] = $doctor;
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($doctors);

$conn->close();
?>
