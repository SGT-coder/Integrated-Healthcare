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
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Define time limits
$lunchStart = '12:00:00';
$lunchEnd = '13:00:00';
$sleepStart = '22:00:00';
$sleepEnd = '06:00:00';

// Function to convert 12-hour format to 24-hour format
function convertTo24HourFormat($time) {
    return date("H:i:s", strtotime($time));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve appointment data from the POST request
    $hospital = $_POST['hospital'];
    $hospital_id = $_POST['hospital_id'];
    $date = $_POST['date'];
    $time = convertTo24HourFormat($_POST['time']); // Convert time to 24-hour format
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Convert time to seconds for comparison
    $timeInSeconds = strtotime($time);

    // Check if selected time is within lunch, sleep, or other break times
    if (
        ($timeInSeconds >= strtotime($lunchStart) && $timeInSeconds <= strtotime($lunchEnd)) ||
        ($timeInSeconds >= strtotime($sleepStart) || $timeInSeconds <= strtotime($sleepEnd))
    ) {
        echo json_encode(["status" => "error", "message" => "Selected time is within break hours."]);
        exit();
    }

    // Check if the selected date and time are already booked or if the next 20 minutes are booked
    $endTime = date("H:i:s", strtotime('+20 minutes', $timeInSeconds)); // Calculate the end time

    $sql = "SELECT * FROM hospital_appointments WHERE hospital_id = '$hospital_id' AND date = '$date' AND (
        (time >= '$time' AND time < '$endTime') OR 
        (time <= '$time' AND time > DATE_SUB('$time', INTERVAL 20 MINUTE))
    )";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Selected date and time are already booked or within 20 minutes of an existing appointment."]);
        exit();
    }

    // Create SQL statement to insert the appointment
    $sql = "INSERT INTO hospital_appointments (hospital, hospital_id, date, time, name, email, phone) VALUES ('$hospital', '$hospital_id', '$date', '$time', '$name', '$email', '$phone')";

    // Execute SQL statement
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Appointment booked successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error booking appointment: " . mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
