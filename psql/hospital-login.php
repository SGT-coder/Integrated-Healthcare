<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $dbname = "healthcare"; // Replace with your MySQL database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $hospitalId = $_POST['hospital_id'];
    $hospitalPassword = $_POST['password'];

    // Fetch hospital_id based on provided hospital_id and password
    $sql = "SELECT * FROM hospital WHERE hospital_id=? AND password=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $hospitalId, $hospitalPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $hospital = $result->fetch_assoc();
            $_SESSION['hospital_id'] = $hospital['hospital_id']; // Store hospital_id in session
            $_SESSION['hospital_name'] = $hospital['name'];
            header("Location: hospital.php");
            exit();
        } else {
            $error = "Invalid hospital ID or password.";
        }

        $stmt->close();
    } else {
        $error = "Failed to prepare SQL statement.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Login</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            color: white;
            margin-top: 10px;
        }

        h1 {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        button {
            padding: 10px 20px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 90%;
        }

        button:hover {
            background-color: #4cae4c;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <video autoplay muted loop id="video-background">
        <source src="uploads/admin_login.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="login-container">
        <h1>Hospital Login</h1>
        <form method="POST" action="">
            <input type="text" name="hospital_id" placeholder="Hospital ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
