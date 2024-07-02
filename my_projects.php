<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

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

// Fetch user information from the database based on user_id
$user_id = $_SESSION['user_id'];
$sql = "SELECT profile_photo FROM user WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    // Fetch data from the first row
    $row = $result->fetch_assoc();
    // Get the profile photo BLOB data
    $profile_photo_blob = $row['profile_photo'];
    // Convert BLOB data to base64 for displaying in HTML
    $profile_picture = base64_encode($profile_photo_blob);
} else {
    // Set default profile picture if not found or error occurred
    $profile_picture = "./OIP.jpg";
}

// Fetch project data from the database
$sql = "SELECT p.*
        FROM project p
        JOIN project_author pa ON p.project_id = pa.project_id
        WHERE pa.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming user_id is an integer, use "i" as the type specifier

// Execute the statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Projects</title>
    <style>
        body {
            background-size: cover;
            margin: 0;
        }

        .profile-page {
            display: flex;
            gap: 10px;
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)),
                url(./bg.webp);
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
            text-decoration: solid;
            font-size: 14px;
            border: solid 1.5px #000000;
            transition: font-size 0.2s ease;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #3928196c;
            font-size: 18px;
        }

        .middle-section {
            flex-grow: 1;
            padding-top: 20px;
            padding-left: 50px;
            background-color: #6148326c;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            margin-right: 20px;
            border-bottom: solid 1.5px #000000;
        }

        .button-container h1 {
            margin-left: 10px;
            color: #e6dad3;
        }

        .button-container .add-button {
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

        .add-button:hover {
            background-color: #3928196c;
            font-size: 19px;
        }

        .item-list {
            color: #eeeae6;
        }

        .item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="profile-page">
    <div class="left-section">
        <img class="profile-picture" src="data:image/jpeg;base64,<?php echo $profile_picture; ?>" alt="Profile Picture">
        <a class="button" href="./profile.php">My profile</a>
        <a class="button" href="./my_papers.php">My papers</a>
        <a class="button" href="./borrow.php">Borrowed</a>
    </div>

    <div class="middle-section">
        <div class="button-container">
            <h1> My projects</h1>
            <button class="add-button" onclick="window.location.href = 'add_project.php'">Add new project</button>
        </div>

        <ul class="item-list">
            <?php
            // Check if the query was successful
            if ($result && $result->num_rows > 0) {
                // Output project data
                while ($row = $result->fetch_assoc()) {
                    // Output the drive_link as project title
                    echo "<li class='item'>";
                    echo "<h3>Title: <a href='" . $row['drive_link'] . "' target='_blank'>" . $row['drive_link'] . "</a></h3>";
                    // You can output additional project details here if needed
                    echo "</li>";
                }
            } else {
                // Output message if no projects found
                echo "<li class='item'>No projects found</li>";
            }
            ?>
        </ul>
    </div>
</div>
</body>
</html>

<?php
// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();
?>
