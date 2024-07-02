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

// Fetch user information from the database based on email
$user_id = $_SESSION['user_id']; // Assuming 'username' is the session variable storing the user's email
$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $department = $row['department'];
        $affiliation = $row['affiliation'];
    } else {
        // Handle error if user not found
        $name = "N/A";
        $email = "N/A";
        $department = "N/A";
        $affiliation = "N/A";
    }
} else {
    // Handle query execution error
    echo "Error: " . $conn->error;
}

// Handle delete user request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $sql_delete = "DELETE FROM user WHERE user_id = '$user_id'";

    if ($conn->query($sql_delete) === TRUE) {
        // Redirect to signout page after deleting user
        header('Location: signout.php');
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Handle update profile request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $department = $_POST['department'];
    $affiliation = $_POST['affiliation'];

    // Update user information in the database
    $sql_update = "UPDATE user SET name = '$name', department = '$department', affiliation = '$affiliation' WHERE user_id = '$user_id'";
    if ($conn->query($sql_update) === TRUE) {
        // Redirect to profile page after updating profile
        header('Location: profile.php');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        /* CSS for the edit profile page */
        * {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            box-sizing: border-box;
        }

        body {
            background-size: cover;
            margin: 0;
            background: url(./bg.webp);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-wrapper {
            width: 400px;
            background-color: rgba(87, 57, 44, 0.81);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-wrapper h2 {
            color: #ead9c3;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #ead9c3;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #ead9c3;
        }

        .form-group input:focus {
            outline: none;
        }

        .button-container {
            display: flex;
            gap: 10px;
        }

        button {
            flex-grow: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ebc7a8;
            color: #0f0e0d;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #d5a67c;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Edit Profile</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" value="<?php echo $department; ?>">
                </div>
                <div class="form-group">
                    <label for="affiliation">Affiliation:</label>
                    <input type="text" id="affiliation" name="affiliation" value="<?php echo $affiliation; ?>">
                </div>
                <div class="button-container">
                    <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">Delete Account</button>
                    <button type="submit" name="update_profile" onclick="return confirm('Are you sure you want to update your profile?')">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
