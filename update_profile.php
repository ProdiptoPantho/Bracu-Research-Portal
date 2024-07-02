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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $department = $_POST['department'];
    $affiliation = $_POST['affiliation'];

    // Update user information in the database
    $update_sql = "UPDATE user SET name='$name', department='$department', affiliation='$affiliation' WHERE user_id='$user_id'";
    if ($conn->query($update_sql) === TRUE) {
        // Redirect to profile page
        echo '<script>window.location.href = "profile.php";</script>';
        exit;
    } else {
        // Handle error if update fails
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
