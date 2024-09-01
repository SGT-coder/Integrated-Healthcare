<?php
function getDoctorAppointments($doctor_id) {
    $servername = "localhost";
    $username = "root"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $dbname = "healthcare"; // Replace with your MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch appointments for the specific doctor
    $sql = "SELECT date, time, name, email, phone FROM appointments WHERE doctor_id='$doctor_id'";
    $result = $conn->query($sql);

    $appointments = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    $conn->close();

    return $appointments;
}
?>
