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
$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $department = $row['department'];
        $affiliation = $row['affiliation'];
        $profile_picture = $row['profile_photo']; // Assuming the column name in your database is 'profile_photo'
    } else {
        // Handle error if user not found
        $name = "N/A";
        $email = "N/A";
        $department = "N/A";
        $affiliation = "N/A";
        $profile_picture = "OIP.jpg"; // Default profile picture
    }
} else {
    // Handle query execution error
    echo "Error: " . $conn->error;
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);

        // Update profile picture in the database
        $update_sql = "UPDATE user SET profile_photo = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $null = NULL;
        $stmt->bind_param("bi", $null, $user_id); // "b" for blob type

        if ($stmt->send_long_data(0, $imageData) && $stmt->execute()) {
            // Provide feedback to the user
            echo "Profile picture updated successfully.";
            // Redirect to profile page after successful update
            header("Location: profile.php");
            exit;
        } else {
            // Handle error updating profile picture
            echo "Error updating profile picture: " . $conn->error;
        }
    } else {
        // Handle file upload error
        echo "Error uploading file: " . $_FILES["image"]["error"];
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile Picture</title>
    </head>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)),
                url(./bg.webp);
            margin: 0;
        }

        .profile-page {
            display: flex;
            gap: 10px;
            height: 100vh;

            margin-left: 35%;
        }

        .button-container {
            font-style: bold;
            color: #e6dad3;
            font-size: x-large;
            margin-top: 7%;
            align: center;

            border-bottom-left-radius: 10px;
        }

        #upload-btn{
        margin-left: 20%;
        display: inline-block;
        padding: 10px 20px;
        background-color: #7c5c41;
        color: #eeeae6;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top:15%

        }
        #upload-btn:hover {
        background-color: #aa9076a9;
        font-size: 15px;
    }
    </style>
</head>
<body>
<div class="profile-page">
    <div class="button-container">
    <h3>Update Profile Picture</h3>
            <form id="uploadForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" id="image">
                <input id="upload-btn" type="submit" value="Upload">
                <br>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Preview" id="preview">
                <br>
            </form>
        </div>

</div>
<script>
        document.getElementById("image").onchange = function(event) {
            var preview = document.getElementById("preview");
            preview.style.display = "inline";
            preview.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
</body>
</html>
