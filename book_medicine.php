<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Medicine</title>
    <link rel="stylesheet" href="book_medicine.css">
    <script>
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        window.onload = function() {
            const medicineName = getQueryParam('medicine');
            if (medicineName) {
                document.getElementById('medicine-name').value = medicineName;
            }

            // Get current local date and time in Ethiopian time zone
            const currentDate = new Date().toLocaleString('en-US', { timeZone: 'Africa/Addis_Ababa' }); // 'Africa/Addis_Ababa' is the IANA time zone identifier for Ethiopian time
            document.getElementById('submission-date').value = currentDate;
        }
    </script>
</head>
<body>
    <header>
        <h1>Book Medicine</h1>
    </header>
    <div class="container">
        <form id="booking-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first-name" aria-label="First Name" required>

            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last-name" aria-label="Last Name" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" aria-label="Age" required min="0">

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" aria-label="Gender" required>
                <option value="" disabled selected>Select your gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="medicine-name">Medicine Name:</label>
            <input type="text" id="medicine-name" name="medicine-name" aria-label="Medicine Name" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" aria-label="Quantity" required min="1">

            <!-- Hidden input field for submission date -->
            <input type="hidden" id="submission-date" name="submission-date">

            <button type="submit">Book Medicine</button>
        </form>

        <?php
        $successMessage = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

            // Set Ethiopian time zone for date handling
            date_default_timezone_set('Africa/Addis_Ababa');

            // Get form data and sanitize
            $firstName = htmlspecialchars(trim($_POST['first-name']));
            $lastName = htmlspecialchars(trim($_POST['last-name']));
            $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
            $gender = htmlspecialchars(trim($_POST['gender']));
            $medicineName = htmlspecialchars(trim($_POST['medicine-name']));
            $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
            $submissionDate = date('Y-m-d H:i:s', strtotime($_POST['submission-date'])); // Convert to MySQL DATETIME format

            // Validate data
            if (!$firstName || !$lastName || !$age || !$gender || !$medicineName || !$quantity) {
                $successMessage = "Invalid input";
            } else {
                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO book_medicine (first_name, last_name, age, gender, medicine_name, quantity, submission_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssissis", $firstName, $lastName, $age, $gender, $medicineName, $quantity, $submissionDate);

                // Execute the statement
                if ($stmt->execute()) {
                    $successMessage = "Booking successful!";
                } else {
                    $successMessage = "Error: " . $stmt->error;
                }

                // Close statement
                $stmt->close();
            }

            // Close connection
            $conn->close();
        }
        ?>

        <!-- Display success message -->
        <?php if ($successMessage): ?>
            <p><?php echo $successMessage; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
