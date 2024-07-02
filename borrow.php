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

// Fetch user's profile photo
$user_id = $_SESSION['user_id'];
$sql_profile = "SELECT profile_photo FROM user WHERE user_id = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("i", $user_id);
$stmt_profile->execute();
$stmt_profile->store_result();

// Check if the query was successful
if ($stmt_profile->num_rows > 0) {
    $stmt_profile->bind_result($profile_photo_blob);
    $stmt_profile->fetch();

    // Convert blob to base64 encoded string
    $profile_photo = base64_encode($profile_photo_blob);
} else {
    // Set a default profile photo path if not found
    $profile_photo = "./default_profile_photo.jpg";
}

// Fetch borrowed papers data from the database
$sql = "SELECT borrows.borrow_id, paper.title AS title, paper.type AS type, paper.publishing_year AS publishing_year, 
GROUP_CONCAT(genre.genre SEPARATOR ', ') AS genres,
GROUP_CONCAT(user.name SEPARATOR ', ') AS author
FROM borrows 
LEFT JOIN paper ON borrows.paper_id = paper.paper_id 
LEFT JOIN genre ON paper.paper_id = genre.paper_id 
LEFT JOIN paper_author ON paper.paper_id = paper_author.paper_id
LEFT JOIN user ON paper_author.user_id = user.user_id
WHERE borrows.user_id = ?
GROUP BY paper.paper_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming user_id is an integer, use "i" as the type specifier
$stmt->execute();

// Get the result set
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Papers</title>
    <style>
        body {
            background-size: cover;
            margin: 0;
        }

        .profile-page {
            display: flex;
            gap: 10px;
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url(./bg.webp);
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
            color: #eeeae6;
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

        .add-button {
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
            display: inline-block;
            text-decoration: none;
            text-align: center;
            line-height: 40px;
        }

        .add-button:hover {
            background-color: #3928196c;
            font-size: 19px;
        }

        .item-list {
            list-style: none;
            padding: 0;
        }

        .item {
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 5px;
        }

        .item h3,
        .item p {
            margin: 5px 0;
        }

        .button-container .add-button,
        .button-container h1 {
            color: #eeeae6;
        }
    </style>
</head>
<body>
<div class="profile-page">
    <div class="left-section">
        <img class="profile-picture" src="data:image/jpeg;base64,<?php echo $profile_photo; ?>" alt="Profile Picture">
        <a class="button" href="./profile.php">My profile</a>
        <a class="button" href="./my_projects.php">My projects</a>
        <a class="button" href="./my_papers.php">My papers</a>
    </div>

    <div class="middle-section">
        <div class="button-container">
            <h1> Borrowed Papers</h1>
            <a class="add-button" href="./borrow_paper.php">Borrow new paper</a>
        </div>

        <ul class="item-list">
            <?php
            // Check if there are borrowed papers
            if ($result->num_rows > 0) {
                // Output borrowed papers
                while ($row = $result->fetch_assoc()) {
                    echo "<li class='item'>";
                    echo "<h3>Title: " . $row['title'] . "</h3>";
                    echo "<p>Author: " . $row['author'] . "</p>";
                    echo "<p>Subject: " . $row['genres'] . "</p>";
                    echo "<p>Publishing Year: " . $row['publishing_year'] . "</p>";
                    echo "<a class='button' href='#' onclick='return confirmReturn(" . $row['borrow_id'] . ")'>Return Paper</a>";
                    echo "</li>";
                }
            } else {
                // Output message if no borrowed papers found
                echo "<li class='item'>No borrowed papers found</li>";
            }
            ?>
        </ul>
    </div>
</div>
<script>
function confirmReturn(borrowsId) {
    if (confirm("Are you sure you want to return this paper?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "return_paper.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Paper returned successfully!");
                    // Reload or update the page as needed
                    location.reload();
                } else {
                    alert("Failed to return paper: " + response.error);
                }
            }
        };
        xhr.send("borrow_id=" + borrowsId);
    }
    return false; // Prevent the default link behavior
}
</script>
</body>
</html>

<?php
// Close the database connection
$stmt_profile->close();
$stmt->close();
$conn->close();
?>
