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

// Fetch all papers from the database
$sql = "SELECT * FROM paper";
$result = $conn->query($sql);

$papers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $papers[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Our Library</title>
    <style>
        /* CSS for the our library page */
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            margin: 0;
            padding: 0;
            background: url(./bg.webp);
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .paper {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .paper h2 {
            margin-top: 0;
            color: #333;
        }

        .paper p {
            margin-bottom: 5px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Our Library</h1>
        <?php
        if (empty($papers)) {
            echo "<p>No papers found in the library.</p>";
        } else {
            foreach ($papers as $paper) {
                echo '<div class="paper">';
                echo '<h2>' . htmlspecialchars($paper['title']) . '</h2>';
                echo '<p><strong>Author:</strong> ' . htmlspecialchars($paper['author']) . '</p>';
				echo '<p><strong>Type:</strong> ' . htmlspecialchars($paper['type']) . '</p>';
                echo '<p><strong>Year:</strong> ' . htmlspecialchars($paper['publishing_year']) . '</p>';
                echo '<p><strong>Abstract:</strong> ' . htmlspecialchars($paper['abstract']) . '</p>';
                // Add more details as needed
                echo '</div>';
            }
        }
        ?>
    </div>
</body>

</html>
