<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $dbname = "healthcare"; // Replace with your MySQL database name

    // Establish a connection to MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $image);

    // Set parameters
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle file upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }

    // If everything is ok, try to upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;

            // Execute the prepared statement
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header("Location: blog_creation.php?status=success");
                exit();
            } else {
                $stmt->close();
                $conn->close();
                header("Location: blog_creation.php?status=error");
                exit();
            }
        } else {
            header("Location: blog_creation.php?status=error");
            exit();
        }
    } else {
        header("Location: blog_creation.php?status=error");
        exit();
    }
} else {
    header("Location: blog_creation.php?status=error");
    exit();
}
?>