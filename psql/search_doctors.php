<?php
header("Content-Type: application/json");

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

// Define the default image path
$default_image = 'uploads/OIP.jfif';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve search query from POST request
$searchTerm = $_POST['search_term'] ?? '';

// Prepare and execute SQL query
$sql = "SELECT name, location, specialties, description, hospital, image_path FROM doctors WHERE name LIKE ? OR specialties LIKE ? OR location LIKE ? OR hospital LIKE ?";
$stmt = $conn->prepare($sql);
$searchTermWildcard = "%" . $searchTerm . "%";
$stmt->bind_param("ssss", $searchTermWildcard, $searchTermWildcard, $searchTermWildcard, $searchTermWildcard);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results and display them
$doctors = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $image = $row['image_path'] ? $row['image_path'] : $default_image;
        $hospital = trim($row['hospital']) !== '' ? $row['hospital'] : 'Not specified';

        $doctors[] = [
            'name' => htmlspecialchars($row['name']),
            'specialties' => htmlspecialchars($row['specialties']),
            'location' => htmlspecialchars($row['location']),
            'description' => htmlspecialchars($row['description']),
            'hospital' => htmlspecialchars($hospital),
            'image' => htmlspecialchars($image)
        ];
    }
}

$stmt->close();
$conn->close();

echo json_encode($doctors);
?>