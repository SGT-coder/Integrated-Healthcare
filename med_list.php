<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Booking</title>
    <link rel="stylesheet" href="med_list1.css">
</head>
<body>
<video autoplay muted loop id="video-bg">
        <source src="pictures/dnaa.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    
    <header>
        <img src="pictures/logoo.png" alt="Logo" class="logo">
        <h1>Book Medicine from Health Guidance</h1>
        <form method="get" action="" class="search-bar">
            <input type="text" name="search" placeholder="Search Medicine">
            <button type="submit">Search</button>
        </form>
    </header>

    <div class="container">
        <!-- Manually added medicine list -->
        <div class="medicine" onclick="location.href='book_medicine.php?medicine=Paracetamol'">
            <img src="pictures/paracetamol.jpg" alt="Paracetamol">
            <p>Paracetamol</p>
            <p class="description">Used to treat pain and fever.</p>
        </div>

        <div class="medicine" onclick="location.href='book_medicine.php?medicine=Aspirin'">
            <img src="pictures/advil.jpg" alt="Aspirin">
            <p>Advil</p>
            <p class="description">Used to reduce pain, fever, or inflammation.</p>
        </div>

        <div class="medicine" onclick="location.href='book_medicine.php?medicine=Ibuprofen'">
            <img src="pictures/ibuprofen.jpg" alt="Ibuprofen">
            <p>Ibuprofen</p>
            <p class="description">Commonly used to relieve pain and reduce fever.</p>
        </div>

        
        <?php
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

        $searchQuery = "";
        if (isset($_GET['search'])) {
            $searchQuery = $conn->real_escape_string($_GET['search']);
        }

        // Fetch data from database
        $sql = "SELECT medicine_name, description, image_pat FROM pharma_upload";
        if ($searchQuery !== "") {
            $sql .= " WHERE medicine_name LIKE '%$searchQuery%'";
        }
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="medicine" onclick="location.href=\'book_medicine.php?medicine=' . urlencode($row["medicine_name"]) . '\'">';
                echo '<img src="' . htmlspecialchars($row["image_pat"]) . '" alt="' . htmlspecialchars($row["medicine_name"]) . '">';
                echo '<p>' . htmlspecialchars($row["medicine_name"]) . '</p>';
                echo '<p class="description">' . htmlspecialchars($row["description"]) . '</p>';
                echo '</div>';
            }
        } else {
            echo "No records found";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
