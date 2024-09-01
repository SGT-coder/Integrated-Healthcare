<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharma Upload</title>
    <link rel="stylesheet" href="pharma_upload3.css">
    <style>
        /* CSS for video background */
        #video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
        }
        form {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.8);
            color: black;
        }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <video id="video-background" autoplay loop muted>
        <source src="pictures/medicine_upload.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <h1>Upload Medicine Information</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="medicine_name">Name of the Medicine:</label><br>
        <input type="text" id="medicine_name" name="medicine_name" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="image">Upload Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <input type="submit" value="Upload">
    </form>

    <?php
    // Define database connection variables
    $servername = "localhost";
    $username = "root"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $dbname = "project101";

    // Function to sanitize input data
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Establish MySQL database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Sanitize input data from the form
        $medicine_name = sanitize_input($_POST['medicine_name']);
        $description = sanitize_input($_POST['description']);
        $image = $_FILES['image'];

        // Upload image handling code goes here...
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        $allowed_formats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_formats)) {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (!move_uploaded_file($image["tmp_name"], $target_file)) {
            die("Sorry, there was an error uploading your file.");
        }

        // Prepare and execute SQL INSERT statement
        $stmt = $conn->prepare("INSERT INTO pharma_upload (medicine_name, description, image_pat) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $medicine_name, $description, $target_file);

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }

    // Handle delete operation if delete_med parameter is set
    if (isset($_GET['delete_med'])) {
        // Establish MySQL database connection for delete operation
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Sanitize input data for delete operation
        $delete_med = sanitize_input($_GET['delete_med']);

        // Prepare and execute SQL DELETE statement
        $stmt = $conn->prepare("DELETE FROM pharma_upload WHERE medicine_name = ?");
        $stmt->bind_param("s", $delete_med);

        if ($stmt->execute()) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }

    // Display all records from the pharma_upload table
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT medicine_name, description, image_pat FROM pharma_upload";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Medicine Name</th><th>Description</th><th>Image</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['medicine_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['image_pat']) . "' alt='Image' style='width:100px;'></td>";
            // Delete link for each row
            echo "<td><a href=\"" . $_SERVER['PHP_SELF'] . "?delete_med=" . urlencode($row['medicine_name']) . "\">Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }

    // Close connection
    $conn->close();
    ?>

    <script src="script.js"></script>
</body>
</html>
