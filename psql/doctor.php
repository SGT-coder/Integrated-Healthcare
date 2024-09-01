<?php
session_start();

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$doctorId = $_SESSION['doctor_id'];

// Handle status change button click
if (isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointmentId = $_POST['appointment_id'];
    $status = $_POST['status'];
    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $appointmentId);
    $stmt->execute();
}

// Fetch appointments for the logged-in doctor
$sql = "SELECT * FROM appointments WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctorId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <style>
        /* General Styles */
        body, html {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
}
main {
    flex: 1;
}

/* Video background styling */
#video-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensure video covers entire area without stretching */
    z-index: -1000; /* Ensure video is behind other content */
}

/* Container for content */
.container {
    position: relative;
    z-index: 1; /* Ensure content is above video */
    padding: 20px; /* Example padding */
    background-color: rgba(255, 255, 255, 0.8); /* Example background color with transparency */
    overflow-y: auto; /* Enable vertical scrolling */
    max-height: 100%; /* Limit height for scrolling */
}

/* Additional styling for header */
header {
    text-align: center;
    color: white; /* Example styling */
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.5); /* Example background color with transparency */
}

/* Table styling (example) */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: rgba(255, 255, 255, 0.8); /* Example background color with transparency */
}

table, th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
    color: #333;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    /* Example: Adjust styles for smaller screens */
    header {
        font-size: 1.5rem;
    }
    table {
        font-size: 0.8rem;
    }
}

/* Footer */
footer {
    background-color: #343a40;
    color: #f8f9fa;
    padding: 15px;
    text-align: center;
    flex-shrink: 0;
    font-size: 0.9rem;
    position: fixed;
    bottom: 0;
    width: 100%;
    z-index: 1;
}


 /* Hover Effects */
 .hover-effect {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-effect:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .welcome-message {
            font-size: 2.2rem;
            color: #007bff;
            
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .section-title {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-top: 30px;
        }

        .filter-form {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .filter-form label {
            font-size: 15px;
            margin-right: 10px;
            font-weight: bold;
        }

        .filter-form input[type="date"] {
            font-weight:18px;
            font-size: 15px;
            padding: 15px;
            border: 22px solid #ced4da;
            border-radius: 4px;
        }

        .filter-btn {
           font-size: 18px;
            background-color: #007bff;
            color: white;
            padding: 10px 28px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
        font-size: 15px;
        margin: 7px;
        padding: 0px;
    }
/* Ensure the date input looks consistent across browsers */
    input[type="date"]::-webkit-datetime-edit-fields-wrapper,
    input[type="date"]::-webkit-datetime-edit-text,
    input[type="date"]::-webkit-datetime-edit-month-field,
    input[type="date"]::-webkit-datetime-edit-day-field,
    input[type="date"]::-webkit-datetime-edit-year-field {
        font-size: 17px;
    }

    

        
        
/* Forms */
form   {
    margin-right:130px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

/* Buttons */
button[type="submit"] {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

.status-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.status-btn.checked {
    background-color: blue;
    color: white;
}

.status-btn.unchecked {
    background-color: red;
    color: white;
}

    </style>
</head>
<body>
<video autoplay muted loop id="video-background">
        <source src="uploads/appointment.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <header>
        <h1>Doctor Dashboard</h1>
    </header>   
    <div class="top-bar">
        <span class="welcome-message">Welcome, <?php echo $_SESSION['doctor_name']; ?></span>
        <a href="doctor-login.php" class="logout-btn">Logout</a>
    </div>    
    <main>
    <h2 class="section-title">Appointments</h2>
    <form method="GET" action="">
            <label for="date" class="form" style="color:white; padding: 20px 20px 20px 20px;font-size:20px;">Filter by Date:</label>
            <input style="margin-right:20px;" type="date" id="date" class="form" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
            <button type="submit" class="filter-btn">Filter</button>
        </form>
    <table class="hover-effect">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $date = isset($_GET['date']) ? $_GET['date'] : '';
                if ($date && $row['date'] != $date) {
                    continue;
                }
                ?>
                <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                        <td>
                        <form method="POST" action="">
                                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="status" value="<?php echo $row['status'] == 'Checked' ? 'Unchecked' : 'Checked'; ?>">
                                <button type="submit" class="status-btn <?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></button>
                            </form>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6'>No appointments found.</td></tr>";
        }
        ?>
    </table>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Hospital Management System | All Rights Reserved</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
