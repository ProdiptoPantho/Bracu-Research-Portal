<?php
session_start();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION['loggedin'])) {
        // User is not logged in, return error
        http_response_code(401);
        exit(json_encode(array("error" => "Unauthorized")));
    }

    // Check if the paper_id is provided
    if (!isset($_POST['paper_id'])) {
        // paper_id is missing, return error
        http_response_code(400);
        exit(json_encode(array("error" => "Missing paper_id")));
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
        // Connection failed, return error
        http_response_code(500);
        exit(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
    }

    // Increment the cited count for the paper in the database
    $paper_id = $_POST['paper_id'];
    $sql = "UPDATE paper SET cited = cited + 1 WHERE paper_id = $paper_id";
    if ($conn->query($sql) === TRUE) {
        // Fetch BibTeX citation for the paper
        $sql = "SELECT bibtex FROM bibliography WHERE paper_id = $paper_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Cited count updated successfully, return BibTeX citation
            http_response_code(200);
            exit(json_encode(array("bibtex" => $row['bibtex'])));
        } else {
            // BibTeX citation not found, return error
            http_response_code(404);
            exit(json_encode(array("error" => "BibTeX citation not found")));
        }
    } else {
        // Failed to update cited count
        http_response_code(500);
        exit(json_encode(array("error" => "Failed to update citation: " . $conn->error)));
    }

    // Close database connection
    $conn->close();
} else {
    // Invalid request method
    http_response_code(405);
    exit(json_encode(array("error" => "Method Not Allowed")));
}
?>
