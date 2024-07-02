<?php
session_start();

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

// Retrieve borrow_id from the POST request
$borrow_id = $_POST['borrow_id'];

// Delete the entry from the borrows table
$sql = "DELETE FROM borrows WHERE borrow_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $borrow_id); // Corrected to use $borrow_id
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Return success response
    echo json_encode(["success" => true]);
} else {
    // Return error response
    echo json_encode(["error" => "Failed to return paper"]);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
