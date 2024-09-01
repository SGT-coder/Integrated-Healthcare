<!-- formlist.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Ambulance</title>
    <link rel="stylesheet" href="book_medicine.css">
</head>
<body>
    <header>
        <h1>Request Ambulance</h1>
    </header>
    <div class="container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost"; // Adjust if your database server is different
            $username = "root"; // Your database username
            $password = ""; // Your database password
            $dbname = "project101"; // Your database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $provider_id = $_POST['provider_id'];
            $am_provider = $_POST['am_provider'];
            $name = $_POST['name'];
            $age = $_POST['age'];
            $sex = $_POST['sex'];
            $location = $_POST['location'];
            $phone = $_POST['phone'];
            $time = $_POST['time'];

            // Get the current date and time in EAT
            $date = new DateTime("now", new DateTimeZone('Africa/Nairobi'));
            $submission_date = $date->format('Y-m-d H:i:s');

            $sql = "INSERT INTO ambulance_request (provider_id, am_provider, name, age, sex, location, phone_number, submission_date, time) VALUES ('$provider_id', '$am_provider', '$name', '$age', '$sex', '$location', '$phone', '$submission_date', '$time')";

            if ($conn->query($sql) === TRUE) {
                echo "<p>New record created successfully</p>";
            } else {
                echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }

            $conn->close();
        }

        $provider_id = isset($_GET['provider_id']) ? $_GET['provider_id'] : '';
        $provider_name = isset($_GET['provider']) ? $_GET['provider'] : '';
        ?>
        <form id="request-form" method="POST" action="">
            <input type="hidden" id="provider_id" name="provider_id" value="<?php echo htmlspecialchars($provider_id); ?>">
            <label for="am_provider">Ambulance Provider Name:</label>
            <input type="text" id="am_provider" name="am_provider" required value="<?php echo htmlspecialchars($provider_name); ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="age">Age:</label>
            <select id="age" name="age" required>
                <option value="" disabled selected>Select your age</option>
                <option value="18-25">18-25</option>
                <option value="26-35">26-35</option>
                <option value="36-45">36-45</option>
                <option value="46-55">46-55</option>
                <option value="56-65">56-65</option>
                <option value="66+">66+</option>
            </select>

            <label for="sex">Sex:</label>
            <select id="sex" name="sex" required>
                <option value="" disabled selected>Select your sex</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="091-234-5678">

            <label for="time">Preferred Time:</label>
            <input type="text" id="time" name="time" required>

            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
