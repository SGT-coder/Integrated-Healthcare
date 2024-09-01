<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambulance Booking Service</title>
    <link rel="stylesheet" href="ambulance_home_page1.css">
</head>
<body>
<video autoplay muted loop class="background-video">
        <source src="pictures/home_page.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="overlay"></div>
    <header>
    <img src="pictures/logoo.png" alt="Logo" class="logo">
        <h1>Ambulance Booking Service</h1>
    </header>
    <main class="container">
        <section class="provider" onclick="location.href='formlist.php?provider_id=1&provider=Tebita Ambulance'">
            <img src="pictures/tebitaambu.JPG" alt="Tebita Ambulance">
            <span>Tebita Ambulance</span>
            <p>Address: Fikremariam Aba Techan St, Addis Ababa</p>
            <p>Phone number: +251 91 122 5464</p>
        </section>

        <section class="provider" onclick="location.href='formlist.php?provider_id=2&provider=Tedla Ambulance'">
            <img src="pictures/tadlaambulance.png" alt="Tedla Ambulance">
            <span>Tedla Ambulance</span>
            <p>Address: Eshal tower 3rd floor, Wengelawit Bulding, Ethio China Street, Gotera Next to, Addis Ababa</p>
            <p>Phone number: +251 98 558 5888</p>
        </section>
        <section class="provider" onclick="location.href='formlist.php?provider_id=3&provider=ICMC Ambulance'">
            <img src="pictures/icmcambu.jpg" alt="ICMC Ambulance">
            <span>ICMC Ambulance</span>
            <p>Address: Behind Tsehay Real Estate, Addis Ababa</p>
            <p>Phone number: +251 93 994 9596</p>
        </section>
        <section class="provider" onclick="location.href='formlist.php?provider_id=4&provider=Nebiela Ambulance'">
            <img src="pictures/Nebiela.jpg" alt="Nebiela Ambulance">
            <span>Nebiela Ambulance</span>
            <p>Location: Around Alfoz Plaza | Gerji</p>
            <p>Phone number: +251 97 810 2244</p>
        </section>
        <section class="map-section">
            <img src="pictures/ambulance%20map.png" alt="Map of Ambulance Service Providers">
        </section>
    </main>
</body>
</html>
