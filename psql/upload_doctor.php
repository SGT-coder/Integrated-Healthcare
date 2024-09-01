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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into database
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);
        $location = $conn->real_escape_string($_POST['location']);
        $specialties = $conn->real_escape_string($_POST['specialties']);
        $description = $conn->real_escape_string($_POST['description']);
        $image_path = $conn->real_escape_string($target_file);
        $doctor_id = $conn->real_escape_string($_POST['doctor_id']);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO doctors (name, email, password, location, specialties, description, image_path, doctor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind the parameters
        $stmt->bind_param("sssssssi", $name, $email, $password, $location, $specialties, $description, $image_path, $doctor_id);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>