<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bracu_research_portal"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search query is submitted
if (isset($_GET['query'])) {
    // Sanitize the search query to prevent SQL injection
    $search_query = mysqli_real_escape_string($conn, $_GET['query']);

    // Construct the SQL query
    $sql = "SELECT * FROM project WHERE project_id LIKE '%$search_query%'";

    // Execute the SQL query
    $result = $conn->query($sql);

    // Display search results
    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['project_id'] . "</li>";
            // You can display more columns here if needed
        }
        echo "</ul>";
    } else {
        echo "No results found";
    }
}

// Close connection
$conn->close();
?>
