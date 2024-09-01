<?php 
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

// Fetch doctors data from the database  
$sql = "SELECT *, image_path FROM doctors WHERE specialties = 'psychiatrist'";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psychiatrist Doctors</title>
    <link rel="stylesheet" href="specialty.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch doctor data from the server
            $.ajax({
                url: 'fetch_doctors.php',
                type: 'GET',
                dataType: 'json',
                success: function(doctors) {
                    var doctorList = $('.doctor-list');
                    doctors.forEach(function(doctor) {
                        var doctorCard = $('<div>').addClass('doctor-card');
                            var img = $('<img>').attr('src', doctor.image).attr('alt', 'Doctor Image');
                        var doctorInfo = $('<div>').addClass('doctor-info');
                        var name = $('<h3>').text(doctor.name);
                        var specialties = $('<p>').text('Specialties: ' + doctor.specialties);
                        var location = $('<ul><li><i class="fas fa-map-marker-alt" style="color: blue;"></i> ' + doctor.location + '</li></ul>');
                        var description = $('<p>').text(doctor.description);
                        var bookingOptions = $('<div>').addClass('booking-options');
                        var bookButton = $('<button>').addClass('online-booking').html('<i class="fas fa-calendar-check"></i> Book Online');
                        var phoneNumber = $('<span>').addClass('phone-number').html('<i class="fas fa-phone"></i> 123-456-7890');

                        doctorInfo.append(name, specialties, location, description);
                        bookingOptions.append(bookButton, phoneNumber);
                        doctorCard.append(img, doctorInfo, bookingOptions);
                        doctorList.append(doctorCard);
                    });

                    // Attach click event handler for online booking
                    $('.online-booking').click(preFillDoctorNameAndRedirect);
                },
                error: function() {
                    console.error('Error fetching doctor data');
                }
            });

            function preFillDoctorNameAndRedirect() {
                var doctorName = $(this).closest('.doctor-card').find('.doctor-info h3').text().trim();
                var url = 'book.html?doctor=' + encodeURIComponent(doctorName);
                window.location.href = url;
            }
        });
    </script>
</head>
<body>
    <header>
        <nav>
            <img src="images/logo.png" alt="Healthcare Logo" class="logo">
        </nav>
    </header>
    <section id="primary-care-doctors">
        <h1>Psychiatrist Doctors</h1>
        <div class="doctor-list"></div>
    </section>
</body>
</html>
