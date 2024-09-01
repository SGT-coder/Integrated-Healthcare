<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload First-Aid Procedure</title>
    <link rel="stylesheet" href="first_aid1.css">
    <style>
        body {
            background-image: url('pictures/wallpaperflare.com_wallpaper%20(10).jpg'); /* Replace 'background_image.jpg' with your actual image file path */
            background-size: cover; /* Ensures the background image covers the entire body */
            background-repeat: no-repeat; /* Prevents the background image from repeating */
            /* Additional background styles can be added here */
        }
    </style>
</head>
<body>
    <h1>Upload First-Aid Procedure</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="type_of_accident">Type of Accident:</label><br>
        <input type="text" id="type_of_accident" name="type_of_accident" required><br><br>
        
        <label for="procedures">Step-by-Step Procedure:</label><br>
        <textarea id="procedures" name="procedures" rows="10" cols="30" required></textarea><br><br>
        
        <label for="image">Upload Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>
        
        <input type="submit" value="Upload">
    </form>

    <?php
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

        $type_of_accident = $_POST['type_of_accident'];
        $procedures = $_POST['procedures'];
        
        // Handle image upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        // Allow certain file formats
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
            
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO first_aid (type_of_accident, procedures, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $type_of_accident, $procedures, $image);

            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }

        $conn->close();
    }
    ?>
</body>
</html>
