<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            box-sizing: border-box;
        }

        .contains {
            width: 100%;
            height: 100vh;
            background-image: linear-gradient(
                rgba(44, 27, 21, 0.61),
                rgba(0, 0, 0, 0.736)
            ),
            url(./login.jpeg);
            background-position: center;
            background-size: cover;
            position: relative;
        }

        .form {
            width: 90%;
            height: 80vh;
            max-width: 450px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #55392c7e;
            padding: 50px 60px 70px;
            text-align: center;
        }

        .form h1 {
            font-size: 30px;
            margin-bottom: 60px;
            color: #ead9c3;
            position: relative;
        }

        .form h1::after {
            content: "";
            width: 100px;
            height: 3px;
            border-radius: 3px;
            background: #ebc7a8;
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translate(-50%);
        }

        .field {
            background: #ead9c3;
            margin: 15px 0;
            border-radius: 3px;
            display: flex;
            align-items: center;
        }

        input {
            width: 100%;
            background: transparent;
            border: 0;
            outline: 0;
            padding: 18px 15px;
        }

        .field {
            margin-left: 15px;
            color: 999;
        }

        #signup-message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }

        #p {
            text-align: center;
            font-size: 15px;
            padding-bottom: 100px;
            margin-left: 600px;
            color: #cfb45a;
        }

        #p a {
            text-decoration: none;
            color: #cfb45a;
        }

        button {
            background: #ebc7a8;
            color: #0f0e0d;
            outline: 0;
            border: 0;
            cursor: pointer;
            transition: 1s;
            height: 40px;
            border-radius: 20px;
            width: 100px;
            font-size: 15px;
            margin-top: 5%;
        }

        #ac {
            color: #c5a790;
        }

        #checkboxes {
            color: #d7beab;
            display: flex;
            flex-direction: row;
            gap: 50px;
        }

    </style>
</head>
<body>
<div class="contains">
    <div class="form">
        <h1>Create Account</h1>
        <form action="signup.php" method="post">
            <div class="input">
                <div class="field">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="name" placeholder="Name" required>
                </div>

                <div class="field">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="field">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="field">
                    <i class="fa-solid fa-lock"></i>
                    <input type="text" name="department" placeholder="Department" required>
                </div>

                <div id="checkboxes">
                    <input type="checkbox" id="option1" name="role[]" value="Researcher">
                    <label for="option1">Researcher</label>
                    <input type="checkbox" id="option2" name="role[]" value="Volunteer">
                    <label for="option2">Volunteer</label>
                </div>

                <div class="btn">
                    <button type="submit">Sign up</button>
                </div>
            </div>
        </form>
        <p id="signup-message"></p>
        <p id="ac">
            Already have an account? <a href="./signin.php">Click Here!</a>
        </p>
    </div>
</div>
</body>
</html>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $department = $_POST['department'];
    $roles = isset($_POST['role']) ? $_POST['role'] : [];

    // Convert the array of roles to a comma-separated string
    // $selectedRoles = '';
    // foreach ($roles as $role) {
    //     $selectedRoles .= $role . ', ';
    // }
    // // Remove the trailing comma and space
    // $selectedRoles = rtrim($selectedRoles, ', ');

    // Database connection
    $servername = "localhost";
    $username = "root";
    $db_password = ""; // Rename to 'db_password' to avoid conflict
    $dbname = "bracu_research_portal";

    // Create connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user already exists in the database
    $check_query = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // User with the same email already exists
        echo '<script>document.getElementById("signup-message").innerText = "User with the same email already exists.";</script>';
    } else {

        

        // Insert data into the 'user' table
        $sql = "INSERT INTO user (name, email, password, department) VALUES ('$name', '$email', '$password', '$department')";

        if ($conn->query($sql) === TRUE) {
            // Get the newly inserted user ID
            $userId = $conn->insert_id;
            // Insert user roles into the 'user_role' table
            foreach ($roles as $role) {
                $sqlUserRole = "INSERT INTO user_role (user_id, role) VALUES ('$userId', '$role')";
                $conn->query($sqlUserRole);
            }

            // Display signup success message
            echo '<script>document.getElementById("signup-message").innerText = "Signup successful!";</script>';
            // Redirect to home page after a short delay
            echo '<script>window.setTimeout(function(){window.location.href = "./home_after.php";}, 2000);</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>