<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <!-- Background video -->
    <video autoplay muted loop class="background-video">
        <source src="pictures/admin_login.mp4" type="video/mp4">
        <!-- Include additional video sources for cross-browser compatibility -->
        Your browser does not support the video tag.
    </video>
    <div class="login-container">
        <h1>Ambulance Login</h1>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>
        <?php
        session_start();

        // Initialize error message
        $error_message = "";

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
            $servername = "localhost";
            $db_username = "root";
            $db_password = ""; // your MySQL password
            $dbname = "project101";

            // Create connection
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get POST data
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare and bind
            $stmt = $conn->prepare("SELECT password FROM am_pro_username_passw WHERE username = ?");
            $stmt->bind_param("s", $username);

            // Execute and check results
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($stored_password);

            if ($stmt->num_rows > 0) {
                $stmt->fetch();
                if ($password === $stored_password) { // Or use password_verify() if passwords are hashed
                    $_SESSION['username'] = $username;

                    switch ($username) {
                        case 'tebita':
                            header("Location: projects/tebita_request_list.php");
                            exit();
                        case 'tedla':
                            header("Location: projects/tedla_request_list.php");
                            exit();
                        case 'icmc':
                            header("Location: projects/icmc_request_list.php");
                            exit();
                        case 'nebiela':
                            header("Location: projects/nebiela_request_list.php");
                            exit();
                        case 'Tirita':
                            header("Location: projects/Tirita_request_list.php");
                            exit();
                        case 'user6':
                            header("Location: page6.php");
                            exit();
                        default:
                            $error_message = "Invalid user";
                    }
                } else {
                    $error_message = "Wrong username or password";
                }
            } else {
                $error_message = "Wrong username or password";
            }

            $stmt->close();
            $conn->close();
        }
        // Display error message if set
        if (!empty($error_message)) {
            echo '<p class="error">' . $error_message . '</p>';
        }
        ?>
    </div>
</body>
</html>