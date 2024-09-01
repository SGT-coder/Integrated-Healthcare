<?php
header("Content-Type: application/json");

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

// Define the default image path
$default_image = 'uploads/default_hospital.webp';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve search query from POST request
$searchTerm = $_POST['search_term'] ?? '';

// Prepare and execute SQL query
$sql = "SELECT name, location, specialties, description, emergency_services, image_path FROM hospital WHERE name LIKE ? OR specialties LIKE ? OR location LIKE ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Failed to prepare the SQL statement: " . $conn->error);
}

$searchTermWildcard = "%" . $searchTerm . "%";
$stmt->bind_param("sss", $searchTermWildcard, $searchTermWildcard, $searchTermWildcard);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results and display them
$hospitals = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $image = $row['image_path'] ? $row['image_path'] : $default_image;

        $hospitals[] = [
            'name' => htmlspecialchars($row['name']),
            'location' => htmlspecialchars($row['location']),
            'specialties' => htmlspecialchars($row['specialties']),
            'description' => htmlspecialchars($row['description']),
            'emergency_services' => htmlspecialchars($row['emergency_services']),
            'image' => htmlspecialchars($image)
        ];
    }
}

$stmt->close();
$conn->close();

echo json_encode($hospitals);
?>
