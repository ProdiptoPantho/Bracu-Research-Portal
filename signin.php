<?php
// Start session
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs to prevent SQL injection
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to fetch user from the database based on username and password
    $query = "SELECT user.*, GROUP_CONCAT(user_role.role) AS roles 
    FROM user LEFT JOIN user_role ON user.user_id = user_role.user_id  
    WHERE user.email='$username' AND user.password='$password' GROUP BY user.user_id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Username and password match, set session variables
        $user = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['name'];
        $_SESSION['user_id'] = $user['user_id'];
        $roles = $user['roles'];

        // Check if the user has both 'Researcher' and 'Volunteer' roles
        if (strpos($roles, 'Researcher') !== false && strpos($roles, 'Volunteer') !== false) {
          // Redirect to home_after_both.php if user has both roles
          header("Location: home_for_both.php");
          exit;
      } elseif (strpos($roles, 'Researcher') !== false || strpos($roles, 'Admin') !== false) {
          // Redirect to home_after.php for Researcher or Admin
          header("Location: home_for_both.php");
          exit;
      } else {
          // Redirect to home_after_volunteer.php for other users
          header("Location: home_after_volunteer.php");
          exit;
      }

    } else {
        // Invalid username or password
        $error_message = "Invalid username or password.";
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Your CSS styles -->
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
    /* #para{
    margin-left: 30%;
    padding-bottom: 10px;
} */      
    </style>
</head>
<body>
<div class="contains">
      <div class="form">
        <h1>Sign In</h1>
        <form action="signin.php" method="POST"> <!-- Update action attribute -->
          <div class="input">
            <div class="field">
              <i class="fa-solid fa-user"></i>
              <input type="text" placeholder="Username" name="username" /> <!-- Add name attribute -->
            </div>

            <div class="field">
              <i class="fa-solid fa-lock"></i>
              <input type="password" placeholder="Password" name="password" /> <!-- Add name attribute -->
            </div>
          </div>
          <style>
            .btn :hover {
              background-color: #f2f1f0f6;
              font-size: 18px;
            }
          </style>
          <div class="btn">
            <button type="submit">Sign in</button> <!-- Changed anchor tag to submit button -->
          </div>
          <style>
            #para {
              margin-top: 3%;
            }
          </style>
          <div id="para"></div>
          <?php if(isset($error_message)) { ?> <!-- Display error message if exists -->
          <p style="color: red;"><?php echo $error_message; ?></p>
          <?php } ?>
          <p id="ac">
            Don't have an account?
            <a id="ac" href="./signup.php">Click Here!</a>
          </p>
        </form> <!-- Moved closing tag to the correct position -->
      </div>
    </div>
</body>
</html>
