<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papers Available</title>
</head>
<style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url(./bg.webp);

        background-size: cover;
        margin: 0;
    }

    .profile-page {
        display: flex;
        gap: 10px;
        height: 100vh;
        margin: 0;
    }

    .item-list {
        margin-top: 7%;
        color: #eeeae6;
    }

    .item {
        margin-top: 4%;
    }

    .button-container h1 {
        font-style: bold;
        margin-left: 15%;
        color: #e6dad3;
        border-bottom: solid 1px #f5f1f1;
        font-size: xx-large;
        border-bottom-left-radius: 10px;
    }
    #submit{
        display: inline-block;
        padding: 10px 20px;
        background-color: #7c5c41;
        color: #eeeae6;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-left: 35%

    }


    #submit:hover {
        background-color: #aa9076a9;
        font-size: 12px;
    } 

    .cite-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #7c5c41;
        color: #eeeae6;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .cite-button:hover {
        background-color: #aa9076a9;
        font-size: 15px;
    }
    #form{
        display: flex
    }
</style>

<body>
<div class="profile-page">
    <nav>
        <div class="button-container">
            <h1>Papers</h1>
            <form id="form" method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="order">Order:</label>
                <select name="order" id="order">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
                <label for="criteria">Sort by:</label>
                <select name="criteria" id="criteria">
                    <option value="cited">Cited</option>
                    <option value="publishing_year">Publishing Year</option>
                </select>
                <button id="submit" type="submit">Apply</button>
            </form>
                

        </div>
    </nav>
    <ul class="item-list">
<?php
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

// Handle form submission
$order = $_GET['order'] ?? 'asc'; // Default order is ascending
$criteria = $_GET['criteria'] ?? 'cited'; // Default criteria is by cited

// Construct SQL query based on user's selections
$sql = "SELECT paper.paper_id, paper.title, paper.publishing_year, paper.cited, GROUP_CONCAT(user.name SEPARATOR ', ') AS author
        FROM paper
        INNER JOIN paper_author ON paper.paper_id = paper_author.paper_id
        INNER JOIN user ON paper_author.user_id = user.user_id
        GROUP BY paper.paper_id, paper.title, paper.publishing_year
        ORDER BY $criteria $order";

$result = $conn->query($sql);
if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li class='item'>";
        echo "<h3>Title: " . $row['title'] . "</h3>";
        echo "<p>Authors: " . $row['author'] . "</p>";
        echo "<p>Year: " . $row['publishing_year'] . "</p>";
        echo "<p>Cited: " . $row['cited'] . "</p>";
        echo "<button class='cite-button' onclick='borrowPaper(" . $row['paper_id'] . ")'>Borrow</button>";
        echo "</li>";
    }
} else {
    echo "No papers found";
}
?>
        ?>
    </ul>
</div>

<script>
function borrowPaper(paperId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "borrow_new_paper.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.error) {
                    alert(response.error);
                } else {
                    alert("Paper borrowed successfully!");
                    window.location.href = "borrow.php";
                }
            }
        };
        xhr.send("paper_id=" + paperId);
    }
</script>
</body>
</html>