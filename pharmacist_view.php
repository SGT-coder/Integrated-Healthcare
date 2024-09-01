<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist View</title>
    <link rel="stylesheet" href="phrama_view.css">
</head>
<body>
    <!-- Video Background -->
    <video id="video-background" autoplay loop muted>
        <source src="pictures/bck%20vid.mp4" type="video/mp4">
        <!-- Add other video sources if needed -->
        Your browser does not support the video tag.
    </video>

    <header>
        <img src="pictures/logoo.png" alt="Logo" style="position:absolute; top:10px; left:10px; height:50px;">
        <h1>Medicine Requests</h1>
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by First Name">
                <input type="submit" value="Search">
            </form>
        </div>
    </header>
    <div class="container">
        <table id="medicine-requests-table">
            <thead>
                <tr>
                    <th><a href="?sort=first_name">First Name</a></th>
                    <th><a href="?sort=last_name">Last Name</a></th>
                    <th><a href="?sort=age">Age</a></th>
                    <th><a href="?sort=gender">Gender</a></th>
                    <th><a href="?sort=medicine_name">Medicine Name</a></th>
                    <th><a href="?sort=quantity">Quantity</a></th>
                    <th><a href="?sort=submission_date">Submission Date</a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database configuration
                $servername = "localhost";
                $username = "root"; // Replace with your MySQL username
                $password = ""; // Replace with your MySQL password
                $dbname = "project101"; // Replace with your database name

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . htmlspecialchars($conn->connect_error));
                }

                // Search and fetch data
                $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
                $sql = "SELECT first_name, last_name, age, gender, medicine_name, quantity, submission_date FROM book_medicine";

                if ($search) {
                    $sql .= " WHERE first_name LIKE '%$search%'";
                }
                
                $sql .= " ORDER BY submission_date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['first_name']) . "</td>
                                <td>" . htmlspecialchars($row['last_name']) . "</td>
                                <td>" . htmlspecialchars($row['age']) . "</td>
                                <td>" . htmlspecialchars($row['gender']) . "</td>
                                <td>" . htmlspecialchars($row['medicine_name']) . "</td>
                                <td>" . htmlspecialchars($row['quantity']) . "</td>
                                <td>" . htmlspecialchars($row['submission_date']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='no-requests'>No requests found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
