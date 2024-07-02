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

// Fetch user information from the database based on email
$user_id = $_SESSION['user_id']; // Assuming 'username' is the session variable storing the user's email
$sql = "SELECT * FROM user WHERE user_id = '$user_id' ";

$result = $conn->query($sql);


if ($result = $conn->query($sql)) {
  // Check if any rows are returned
  
      // Fetch data from the first row (assuming only one row is returned)
      $row = $result->fetch_assoc();
      $name = $row['name'];
      $email = $row['email'];
      $department = $row['department'];
      $affiliation = $row['affiliation'];
      $profile_photo_blob = $row['profile_photo'];
      $profile_picture = base64_encode($profile_photo_blob);  
 
} else {
  // Handle if user not found
  $name = "N/A";
  $email = "N/A";
  $department = "N/A";
  $affiliation = "N/A";
  $genre = "N/A";// Set default profile picture here
  $profile_picture = "./OIP.jpg";
}

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html>

<head>
  <title>Profile Page</title>

</head>

<body id="body">


  <style>
    /* CSS for the profile page */
    body {
      background-size: cover;
      margin: 0;

    }

    .profile-page {
      display: flex;
      gap: 10px;
      height: 100vh;
      background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url(./bg.webp);
      background-size: cover;
      margin: 0;



    }

    /* CSS for the left section */
    .left-section {
      align-items: center;
      width: 14%;
      padding: 10px;
      background-color: #3928196c;

      /* background: url(./crypt.jpeg); */

    }

    .profile-picture {
      margin-left: 20px;
      width: 150px;
      height: 150px;
      border-radius: 50%;
      margin-bottom: 20px;

    }

    .button {
      margin-top: 20px;
      margin-left: 30px;
      display: block;
      width: 49%;
      padding: 8px;
      margin-bottom: 10px;
      background-color: #7c5c41;
      color: #eeeae6;
      text-align: center;
      text-decoration: solid;
      font-size: 14px;
      border: solid 1.5px #000000;
      transition: font-size 0.2s ease;
      border-radius: 7px;

    }

    .button:hover {
      background-color: #2d1c0e6e;
      font-size: 18px;

    }

    /* CSS for the middle section */
    .middle-section {

      flex-grow: 1;
      padding: 20px;
      background-color: #6e533d6e;

    }

    .middle-section p {
      margin-top: 30px;
      font-size: 18px;
      margin-left: 30px;
      color: #eeeae6;
    }



    .right-section {
      width: 20%;
      padding: 20px;
      background-color: #3928196c;
      color: #eeeae6;
      /* background: url(./crypt.jpeg); */
    }

    .recent-works {
      font-weight: bold;
      color: #eeeae6;
    }

    .work-item {
      margin-bottom: 10px;
    }

    #h1 {
      color: #eeeae6;
    }

    .dp-section {
      position: relative;
      /* Container for the image */
    }



    .dp-section button#dp {
      position: absolute;
      bottom: 5px;
      right: 30px;

    }

    #dp {
      margin-left: 10px;

      width: 49%;
      padding: 3px;
      margin-bottom: 19px;
      background-color: #dedacc;
      color: #fff3f3;
      text-align: center;
      text-decoration: solid;
      font-weight: 700;
      font-size: 11px;
      border: solid 3px #000000;
      transition: font-size 0.2s ease;
      border-radius: 7px;
    }

    #dp:hover {
      background-color: #b8b1ac;
      font-size: 12px;
    }

        #sign-out-btn {
            margin-top: 20px;
            margin-left: 30px;
            display: block;
            width: 49%;
            padding: 8px;
            margin-bottom: 10px;
            background-color: #7c5c41;
            color: #eeeae6;
            text-align: center;
            text-decoration: none; /* Changed from solid to none */
            font-size: 14px;
            border: solid 1.5px #000000;
            transition: font-size 0.2s ease;
            border-radius: 7px;
        }

        #sign-out-btn:hover {
            background-color: #2d1c0e6e;
            font-size: 18px;
        }

  </style>

  <body>
    <div class="profile-page">
      <div class="left-section">
      <div class="dp-section">
      <img class="profile-picture" src="data:image/jpeg;base64,<?php echo $profile_picture; ?>" alt="Profile Picture">
      <button id="dp"><a href="update_dp.php">Change Photo</a></button>
</div>
		<a class="button" href="./home_for_both.php">Home</a>
        <a class="button" href="./my_projects.php">My projects</a>
        <a class="button" href="./my_papers.php">My papers</a>
        <a class="button" href="./borrow.php">Borrowed</a>
		<a class="button" href="./edit_profile.php">Edit Profile</a>
        <a class="button" id="sign-out-btn" href="#">Sign Out</a>
        

      </div>

      <div class="middle-section">
      <h1 id="h1">Profile Information</h1>
      <p>Name: <?php echo $name; ?></p>
      <p>Email: <?php echo $email; ?></p>
      <p>Department: <?php echo $department; ?></p>
      <p>Affiliation: <?php echo $affiliation; ?></p>
      </div>



      </div>


      <div class="right-section">
        <h1 class="recent-works">Recent Works</h1>
        <ul>
          <li class="work-item">Work 1</li>
          <li class="work-item">Work 2</li>
          <li class="work-item">Work 3</li>
        </ul>
      </div>
    </div>

    <script>
    document.getElementById('sign-out-btn').addEventListener('click', function() {
        // First confirmation
        if (confirm("Are you sure you want to sign out?")) {
            // Second confirmation
            if (confirm("This action cannot be undone. Are you absolutely 100% sure? Like really really sure?")) {
                window.location.href = "signout.php"; // Redirect to signout.php
            }
        }
    });
    </script>
  </body>

</html>