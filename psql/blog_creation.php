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

// Execute query
$sql = "SELECT * FROM blogs"; // Adjust the query as per your table structure
$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $sql . "<br>" . $conn->error;
    // Handle error appropriately
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-image: url('uploads/add_blog.jpeg');
            background-size: cover;
            background-attachment: fixed;
            color: #333;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-container, .blog-list {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            backdrop-filter: blur(5px);
            width: 100%;
            box-sizing: border-box;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 700;
        }
        input[type="text"], textarea, input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            background-color: rgba(255, 255, 255, 0.8);
        }
        input[type="text"]:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        button {
            padding: 12px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2980b9;
        }
        .success, .error {
            margin-top: 15px;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: rgba(46, 204, 113, 0.8);
        }
        .error {
            background-color: rgba(231, 76, 60, 0.8);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.8);
        }
        th {
            background-color: rgba(52, 73, 94, 0.8);
            color: #fff;
        }
        tr {
            transition: transform 0.2s ease;
        }
        tr:hover {
            transform: scale(1.02);
        }
        .delete-btn {
            padding: 8px 15px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
        .description {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Add New Blog</h1>
            <form method="POST" action="add_blog.php" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Blog Title" required>
                <textarea name="content" placeholder="Blog Content" rows="10" required></textarea>
                <input type="file" name="image" required>
                <button type="submit">Add Blog</button>
                <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                    <p class="success">Blog added successfully!</p>
                <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
                    <p class="error">Failed to add blog. Please try again.</p>
                <?php endif; ?>
            </form>
        </div>
        <div class="blog-list">
            <h1>Blog List</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php
   if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td class='description'>" . $row['content'] . "</td>";
        echo "<td>";
        if (!empty($row['image'])) {
            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Blog Image' width='50'>";
        } else {
            echo "<img src='placeholder.jpg' alt='Placeholder Image' width='50'>";
        }
        echo "</td>";
        echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
        echo "<td><button class='delete-btn' data-blog-id='" . $row['id'] . "'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No blogs available.</td></tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<script>
    // Function to handle AJAX delete request
    function deleteBlog(blogId) {
        if (confirm("Are you sure you want to delete this blog?")) {
            // AJAX configuration
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_blog.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            // Send the blog ID as data
            xhr.send("id=" + blogId);
            
            // AJAX response handling
            xhr.onload = function () {
                if (xhr.status == 200) {
                    // Optionally handle response here (e.g., show success message)
                    alert(xhr.responseText);
                    // Reload the page or update blog list as needed
                    location.reload(); // Example: reload the page after delete
                } else {
                    // Handle error cases
                    alert('Error deleting blog. Please try again.');
                }
            };
        }
    }

    // Attach event listener to all delete buttons with class 'delete-btn'
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var blogId = this.getAttribute('data-blog-id');
            deleteBlog(blogId);
        });
    });
</script>
</html>
<?php
$conn->close();
?>
