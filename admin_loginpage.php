<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css"> <!-- Link to external CSS file -->
    <!-- Optional: Add CSS for styling -->
    <style>
    </style>
</head>
<body>
    <!-- Background video -->
    <video autoplay muted loop class="background-video">
        <source src="pictures/admin_login.mp4" type="video/mp4">
        <!-- Include additional video sources for cross-browser compatibility -->
        Your browser does not support the video tag.
    </video>
    <div class="login-container">
        <h2>Admin Login Page</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <button type="submit" name="submit">Login</button>
        </form>
    </div>

    <?php
    // PHP script starts here
    session_start();

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection parameters
        $servername = "localhost";
        $db_username = "root"; // Replace with your MySQL username
        $db_password = ""; // Replace with your MySQL password
        $dbname = "project101"; // Replace with your database name
        $table = "admin_loginpage"; // Replace with your table name

        // Establishing a connection to MySQL
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetching username and password from POST request
        $username = $_POST['username'];
        $password = $_POST['password'];

        // SQL query to fetch user data
        $sql = "SELECT * FROM $table WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Correct credentials, redirect to another page
            $_SESSION['username'] = $username; // Storing username in session for further use if needed
            header("Location: amservicepro.html"); // Redirect to welcome.php or any other page
            exit(); // Ensure no further execution after redirection
        } else {
            // Incorrect credentials, display error message
            echo "<p style='color: red;'>Invalid username or password. Please try again.</p>";
        }

        $conn->close(); // Close MySQL connection
    }
    ?>
</body>
</html>
