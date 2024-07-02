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

// Retrieve paper_id from the POST request
$paperId = $_POST['paper_id'];

// Retrieve user_id from session or wherever you store it
$user_id = $_SESSION['user_id']; // Replace with actual user ID

// Insert into borrow table
$sql = "INSERT INTO borrows (user_id, paper_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $paperId);
$stmt->execute();

if ($stmt->affected_rows === 1) {
    // Retrieve the borrows_id
    $borrow_id = $stmt->insert_id;
    // Send back the borrows_id as JSON response
    echo json_encode(["success" => true, "borrow_id" => $borrow_id]);
} else {
    echo json_encode(["error" => "Error borrowing paper"]);
}

$stmt->close();
$conn->close();
?>