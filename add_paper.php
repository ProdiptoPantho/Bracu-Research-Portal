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

// Initialize variables to store form data
$title = $author = $year = $abstract = "";
$titleErr = $authorErr = $yearErr = $abstractErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty($_POST["title"])) {
        $titleErr = "Title is required";
    } else {
        $title = test_input($_POST["title"]);
    }

    // Validate author
    if (empty($_POST["author"])) {
        $authorErr = "Author is required";
    } else {
        $author = test_input($_POST["author"]);
    }

    // Validate year
    if (empty($_POST["year"])) {
        $yearErr = "Year is required";
    } else {
        $year = test_input($_POST["year"]);
    }

    // Validate abstract
    if (empty($_POST["abstract"])) {
        $abstractErr = "Abstract is required";
    } else {
        $abstract = test_input($_POST["abstract"]);
    }

    // If all fields are valid, insert data into database
    if (empty($titleErr) && empty($authorErr) && empty($yearErr) && empty($abstractErr)) {
        $sql = "INSERT INTO paper (title, publishing_year, abstract) VALUES ('$title', '$year', '$abstract') and INSERT INTO ";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Paper added successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Function to sanitize form data
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Paper</title>
    <style>
        /* CSS for the add paper page */
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url(./bg.webp);
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(87, 57, 44, 0.81);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #ead9c3;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #ead9c3;
        }

        .form-group input,
        .form-group textarea {
            width: 96%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #eeeae6;
        }

        button {
            padding: 10px 20px;
            background-color: #7c5c41;
            color: #eeeae6;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2d1c0e6e;
        }

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Add Paper</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $title; ?>">
                <span class="error"><?php echo $titleErr; ?></span>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" value="<?php echo $author; ?>">
                <span class="error"><?php echo $authorErr; ?></span>
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="text" id="year" name="year" value="<?php echo $year; ?>">
                <span class="error"><?php echo $yearErr; ?></span>
            </div>
            <div class="form-group">
                <label for="abstract">Abstract:</label>
                <textarea id="abstract" name="abstract" rows="5"><?php echo $abstract; ?></textarea>
                <span class="error"><?php echo $abstractErr; ?></span>
            </div>
            <button type="submit">Add Paper</button>
        </form>
    </div>
</body>

</html>
