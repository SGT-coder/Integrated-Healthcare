<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display First-Aid Procedures</title>
    <link rel="stylesheet" href="first_aid_display3.css">
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("firstAidTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function showModal(procedures) {
            var modal = document.getElementById("myModal");
            var modalContent = document.getElementById("modalContent");
            modalContent.innerText = procedures;
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</head>
<body>
<video autoplay muted loop id="backgroundVideo">
        <source src="pictures/home_page.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    <h1>First-Aid Procedures</h1>

    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by Type of Accident...">
    </div>

    <!-- Manually added First-Aid procedures -->
    <table id="firstAidTable">
        <tr><th>Image</th><th>Type of Accident</th><th>Procedure</th></tr>
        
        <tr>
            <td><img src="pictures/burn.jpg" alt="Burn"></td>
            <td>Burn</td>
            <td>
                Cool the burn with running water for at least 10 minutes, cover with cling film or a sterile dressing. 
                <a href="#" data-procedures="Cool the burn under cold running water for 10-30 minutes, cover with cling film or a sterile dressing. Seek medical help if necessary." onclick="showModal(this.dataset.procedures)">Read More</a>
            </td>
        </tr>
        
        <tr>
            <td><img src="pictures/uplaod first aid.jpg" alt="Cut"></td>
            <td>Cut</td>
            <td>
                Clean the wound, apply pressure to stop the bleeding, and cover with a sterile dressing. 
                <a href="#" data-procedures="Clean the wound with running water, apply pressure with a clean cloth to stop the bleeding, cover with a sterile dressing. Seek medical attention if the wound is deep or doesn't stop bleeding." onclick="showModal(this.dataset.procedures)">Read More</a>
            </td>
        </tr>
        
        <tr>
            <td><img src="pictures/sprain.jpg" alt="Sprain"></td>
            <td>Sprain</td>
            <td>
                Rest the injured area, apply ice, compress with a bandage, and elevate the limb. 
                <a href="#" data-procedures="Rest the injured area, apply ice for 15-20 minutes every hour, compress with a bandage, and elevate the limb to reduce swelling." onclick="showModal(this.dataset.procedures)">Read More</a>
            </td>
        </tr>
        
        <tr>
            <td><img src="pictures/Seizure.jpg" alt="Poisoning"></td>
            <td>Poisoning</td>
            <td>
                Identify the poison, call emergency services, and follow their instructions. 
                <a href="#" data-procedures="If the person is conscious, try to identify the poison, call emergency services immediately, and follow their instructions. Do not induce vomiting unless advised." onclick="showModal(this.dataset.procedures)">Read More</a>
            </td>
        </tr>
    </table>
    <!-- End of manually added First-Aid procedures -->

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project101";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT type_of_accident, procedures, image FROM first_aid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table id="firstAidTable">';
        echo '<tr><th>Image</th><th>Type of Accident</th><th>Procedure</th></tr>';
        while($row = $result->fetch_assoc()) {
            $short_procedures = htmlspecialchars(substr($row['procedures'], 0, 100)) . '...';
            $full_procedures = htmlspecialchars($row['procedures']);
            echo '<tr>';
            echo '<td><img src="pictures/' . htmlspecialchars($row['image']) . '"></td>';
            echo '<td>' . htmlspecialchars($row['type_of_accident']) . '</td>';
            echo '<td>' . $short_procedures . ' <a href="#" data-procedures="' . $full_procedures . '" onclick="showModal(this.dataset.procedures)">Read More</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalContent"></p>
        </div>
    </div>
</body>
</html>
