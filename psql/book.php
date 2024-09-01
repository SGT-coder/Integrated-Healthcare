<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "healthcare"; // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    die("Connection failed: " . $conn->connect_error);
}

// Function to convert 12-hour format to 24-hour format
function convertTo24HourFormat($time) {
    return date("H:i:s", strtotime($time));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve appointment data from the POST request
    $doctor = $_POST['doctor'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = convertTo24HourFormat($_POST['time']); // Convert time to 24-hour format
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Your validation and booking logic here...
    
    // Example validation and booking logic:
    // Assume $validationPassed is true if validation succeeds
    $validationPassed = true;

    if ($validationPassed) {
        // Create SQL statement to insert the appointment
        $sql = "INSERT INTO appointments (doctor, doctor_id, date, time, name, email, phone) 
                VALUES ('$doctor', '$doctor_id', '$date', '$time', '$name', '$email', '$phone')";

        // Execute SQL statement
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["status" => "success", "message" => "Appointment booked successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error booking appointment: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Validation failed or other custom error message."]);
    }
}

mysqli_close($conn);
?>
