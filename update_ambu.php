<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ambulance Info</title>
    <link rel="stylesheet" href="update_ambu.css">
</head>
<body>
    <header>
        <h1>Update Ambulance Info</h1>
    </header>
    <div class="container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";  // Change this if your database username is different
            $password = "";  // Change this if your database password is different
            $dbname = "project101";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get form data
            $totalAmbulances = intval($_POST['total_ambulances']);
            $onDutyAmbulances = intval($_POST['on_duty_ambulances']);
            $offDutyAmbulances = intval($_POST['off_duty_ambulances']);

            if ($onDutyAmbulances + $offDutyAmbulances > $totalAmbulances) {
                echo "<div id='update-message' style='color: red;'>Error: The number of on-duty and off-duty ambulances exceeds the total number of ambulances.</div>";
            } else {
                // Insert data into database
                $sql = "INSERT INTO update_ambu (total_ambulances, `Ambulances_on_duty`, `Ambulances_off_duty`)
                        VALUES ('$totalAmbulances', '$onDutyAmbulances', '$offDutyAmbulances')";

                if ($conn->query($sql) === TRUE) {
                    echo "<div id='update-message' style='color: green;'>Ambulance information updated successfully!</div>";
                } else {
                    echo "<div id='update-message' style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                }
            }

            $conn->close();
        }
        ?>
        <form id="update-form" method="POST" action="">
            <label for="total-ambulances">Total Ambulances:</label>
            <input type="number" id="total-ambulances" name="total_ambulances" required>

            <label for="on-duty-ambulances">Ambulances on Duty:</label>
            <input type="number" id="on-duty-ambulances" name="on_duty_ambulances" required>

            <label for="off-duty-ambulances">Ambulances off Duty:</label>
            <input type="number" id="off-duty-ambulances" name="off_duty_ambulances" required>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
