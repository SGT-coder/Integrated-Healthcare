<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambulance Requests</title>
    <link rel="stylesheet" href="request_list.css">
</head>
<body>
    
<!-- Video Background -->
<video id="video-background" autoplay loop muted>
        <source src="pictures/bck%20vid.mp4" type="video/mp4">
        <!-- Add other video sources if needed -->
        Your browser does not support the video tag.
    </video>


    <header>
        <h1>Tirita Ambulance Requests</h1>
    </header>
    <div class="container">
        <table id="requests-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Location</th>
                    <th>Phone Number</th>
                    <th>Submission Date</th>
                    <th>Preferred Time</th> <!-- Added column -->
                </tr>
            </thead>
            <tbody>
                <?php
                    // Database connection details
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "project101";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // SQL query to select data where provider_id is 1 and fetch submission_date and time
                    $sql = "SELECT name, age, sex, location, phone_number, submission_date, time FROM ambulance_request WHERE provider_id = 5 ORDER BY submission_date DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row["name"]) . "</td>
                                    <td>" . htmlspecialchars($row["age"]) . "</td>
                                    <td>" . htmlspecialchars($row["sex"]) . "</td>
                                    <td>" . htmlspecialchars($row["location"]) . "</td>
                                    <td>" . htmlspecialchars($row["phone_number"]) . "</td>
                                    <td>" . htmlspecialchars($row["submission_date"]) . "</td>
                                    <td>" . htmlspecialchars($row["time"]) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr>
                                <td colspan='7'>No ambulance requests available</td>
                              </tr>";
                    }

                    // Close connection
                    $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
