<!DOCTYPE html>
<html>
<head>
    <title>My Papers</title>

</html>

<style>
    body{
    background-size: cover;
    margin: 0;

}
.profile-page {
    display: flex;
    gap: 10px;
    height: 100vh;
    background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)),url(./bg.webp);
    background-size: cover;
}

.left-section {
    align-items: center;
    background-color: #3928196c;
    width: 14%;
    padding: 10px;
    
}

.profile-picture {
    margin-left: 20px;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 20px;
}

.button {
    margin-top: 20px;
    margin-left: 45px;
    display: block;
    width: 90px;
    padding: 8px;
    margin-bottom: 10px;
    background-color: #7c5c41;
    color: #eeeae6;
    text-align: center;
    text-decoration:solid;
    font-size: 14px;
    border: solid 1.5px #000000;
    transition: font-size 0.2s ease;
    border-radius: 5px;

    
}

.button:hover {
    background-color: #3928196c;
    font-size: 18px;
    
}

/* CSS for the middle section */
.middle-section {
    
    flex-grow: 1;
    padding-top: 20px;
    padding-left: 50px;
    background-color: #6148326c;
}
.middle-section p{
    margin-top: 10px;
    font-size: 18px;
    margin-left: 30px;
    
   
}
.button-container{
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
    margin-right: 20px;
    border-bottom: solid 1.5px #000000;
    
}
.button-container h1{
    margin-left: 10px;
    color: #e6dad3;
    
    
    
}
.button-container .add-button{
    margin-top: 15px;
    margin-right: 30px;
    width: 175px;
    height: 40px;
    background-color: #7c5c41;
    color: #eeeae6;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    border: solid 1.8px #000000;
    font-size: 18px;
    transition: font-size 0.2s ease;
}
.add-button:hover{
    background-color: #3928196c;
    font-size: 19px;
    
}



.recent-works {
    font-weight: bold;
}

.work-item {
    margin-bottom: 10px;
}

#borrow{
    background-color: #3928196c;
    color: black;
    font-size: larger;

}
.item-list{
    color: #eeeae6;

}

</style>
<body>
<div class="profile-page">
    <div class="left-section">
    <?php
        session_start();

        // Check if user is logged in
        if (!isset($_SESSION['loggedin'])) {
            header('Location: signin.php');
            exit;
        }

        // Retrieve user_id from session
        $user_id = $_SESSION['user_id'];

        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bracu_research_portal";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to fetch profile photo
        $sql = "SELECT profile_photo FROM user WHERE user_id = '$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            $row = $result->fetch_assoc();
            // Get the profile photo BLOB data
            $profile_photo_blob = $row['profile_photo'];
            // Convert BLOB data to base64 for displaying in HTML
            $profile_picture = base64_encode($profile_photo_blob);
            // Display profile picture
            echo "<img class='profile-picture' src='data:image/jpeg;base64,$profile_picture' alt='Profile Picture'>";
        } else {
            // Set default profile picture if not found or error occurred
            echo "<img class='profile-picture' src='./OIP.jpg' alt='Profile Picture'>";
        }
        ?>
        <!-- profile picture will be added from database -->
        <a class="button" href="./profile.php">My profile</a>
        <a class="button" href="./my_projects.php">My projects</a>
        <a class="button" href="./borrow.php">Borrowed</a>
    </div>

    <div class="middle-section">
        <div class="button-container">
            <h1> My Papers</h1>
            <button class="add-button" onclick="redirectToAddPaper()">Add new paper</button>
            <script>
                function redirectToAddPaper() {
                    window.location.href = "./add_paper.php";
                }
            </script>
        </div>

        <ul class="item-list">
        <?php

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

// Retrieve user_id from session
$user_id = $_SESSION['user_id'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bracu_research_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch paper details
$sql = "SELECT 
            IFNULL(paper.title, 'N/A') AS title, 
            IFNULL(paper.type, 'N/A') AS type, 
            IFNULL(paper.publishing_year, 'N/A') AS publishing_year, 
            IFNULL(GROUP_CONCAT(genre.genre SEPARATOR ', '), 'N/A') AS genres
        FROM user 
        LEFT JOIN paper_author ON user.user_id = paper_author.user_id 
        LEFT JOIN paper ON paper_author.paper_id = paper.paper_id 
        LEFT JOIN genre ON paper.paper_id = genre.paper_id 
        WHERE user.user_id = '$user_id' 
        GROUP BY paper.paper_id";
$result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<li class='item'>";
                    echo "<h3>Title: " . $row["title"] . "</h3>";
                    echo "<p>Type: " . $row["type"] . "</p>";
                    echo "<p>Publishing Year: " . $row["publishing_year"] . "</p>";
                    echo "<p>Genre: " . $row["genres"] . "</p>";
                    echo "</li>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </ul>
    </div>
</div>
</body>
</html>

