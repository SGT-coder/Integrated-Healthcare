<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the blog ID from POST request
$blogId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// Delete the blog from database
$sql = "DELETE FROM blogs WHERE id = $blogId";
if ($conn->query($sql) === TRUE) {
    echo "Blog deleted successfully.";
} else {
    echo "Error deleting blog: " . $conn->error;
}

$conn->close();
?>
