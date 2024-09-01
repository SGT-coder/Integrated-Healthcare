<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM blogs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hospitals Near You</title>
    <link rel="stylesheet" href="newstyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="neyjava.js"></script>
</head>
<body>
    <!-- Existing Code Starts -->
    <div class="video-overlay">
        <video autoplay muted loop id="bg-video">
            <source src="uploads/home_page.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
    </div>

    <div class="container">
        <header>
            <nav>
                <img src="uploads/logoo.png" alt="Healthcare Logo" class="logo">
            </nav>
        </header>
        <section id="search">
            <div class="headings">
                <h1>Find Hospital Near You</h1>
            </div>
            <form id="search-form" aria-label="serp-block-form" class="search-form">
                <div class="input-group">
                    <input id="search-query" type="text" autocomplete="off" placeholder="Search Hospitals, specialties or location" name="search_query">
                    <span class="input-group-prepend">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <button type="submit" aria-label="Search">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>
            </form>
            <div id="search-results" style="display: none;">
                <!-- Search results will be displayed here -->
            </div>   
        </section>

        <!-- Hospital Section -->
        <!-- Your Existing Content -->

    </div>

    <!-- Blog Section Starts Here -->
    <div class="container">
        <h1>Our Blog Posts</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="blog">
                    <div class="blog-image" style="background-image: url('<?php echo (!empty($row['image']) && file_exists($row['image'])) ? htmlspecialchars($row['image']) : 'placeholder.jpg'; ?>');"></div>
                    <div class="blog-content">
                        <h2 class="blog-title"><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p class="blog-description"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        <span class="read-more" onclick="toggleDescription(this)">Read More</span>
                        <p class="blog-time">Published on: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-blogs">No blogs available at the moment. Check back soon!</p>
        <?php endif; ?>
    </div>

    <?php $conn->close(); ?>
    <!-- Blog Section Ends Here -->

    <footer>
        <p>&copy; 2024 Healthcare Finder. All rights reserved.</p>
    </footer>

    <script>
        function toggleDescription(button) {
            var description = button.previousElementSibling;
            if (description.style.maxHeight === "100px" || description.style.maxHeight === "") {
                description.style.maxHeight = description.scrollHeight + "px";
                button.textContent = "Read Less";
            } else {
                description.style.maxHeight = "10011px";
                button.textContent = "Read More";
            }
        }
    </script>
</body>
</html>
