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

// Get the specialty from the query parameter
$specialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';

// Fetch doctors data from the database based on the specialty
$sql = "SELECT name, specialties, location, description, hospital, image_path image_path FROM doctors WHERE specialties = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $specialty);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $stmt->error);
}

// Prepare the output as JSON
$doctors = array();
$default_image = 'uploads/logoo.png'; // Define a default image path

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Check if the image path is valid and the file exists
        $image = !empty($row['image_path']) ? str_replace('\\', '/', $row['image_path']) : $default_image; // Convert backslashes to forward slashes
        $hospital = isset($row['hospital']) ? $row['hospital'] : 'Not specified'; // Handle missing hospital data

        // $doctor = array(
        //     'name' => $row['name'],
        //     'specialties' => $row['specialties'],
        //     'location' => $row['location'],
        //     'description' => $row['description'],
        //     'hospital' => $hospital,
        //     'image' => $image
        // );
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
