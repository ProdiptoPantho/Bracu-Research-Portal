<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: signin.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $errors = [];

    // Check project link
    if (empty($_POST['drive_link'])) {
        $errors[] = "Project Link is required";
    } else {
        $drive_link = $_POST['drive_link'];
        
        // Check if the same link already exists
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

        $sql_check_link = "SELECT project_id FROM project WHERE drive_link = ?";
        $stmt_check_link = $conn->prepare($sql_check_link);
        $stmt_check_link->bind_param("s", $drive_link);
        $stmt_check_link->execute();
        $result_check_link = $stmt_check_link->get_result();

        if ($result_check_link->num_rows > 0) {
            $errors[] = "A project with the same Drive link already exists";
        }

        // Close statement
        $stmt_check_link->close();
        $conn->close();
    }

    // If there are no errors, insert project into database
    if (empty($errors)) {
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

        // Prepare SQL statement to insert project
        $sql = "INSERT INTO project (drive_link) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $drive_link);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Project added successfully
            $success_message = "Project added successfully";

            // Redirect to another page
            header('Location: my_projects.php');
            exit; // Make sure to exit after redirection
        } else {
            // Error inserting project
            $error_message = "Error adding project: " . $conn->error;
        }

        // Close project statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Project</title>
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url("./bg.webp");
            background-size: cover;
            margin: 0;
        }

        .form-container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #55392c7e;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .form-container h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #fff;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 96.5%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-group textarea {
            height: 100px;
        }

        .form-group input[type="submit"] {
            background-color: #7c5c41;
            color: #eeeae6;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover {
            background-color: #aa9076a9;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Project</h2>
        
        <?php if (!empty($errors)) { ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form action="add_project.php" method="post">
            <div class="form-group">
                <label for="drive_link">Drive link:</label>
                <input type="text" id="drive_link" name="drive_link" value="<?php echo isset($drive_link) ? htmlspecialchars($drive_link) : ''; ?>">
            </div>

            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>