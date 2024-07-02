<!DOCTYPE html>
<html lang="en">
  <head id="head">
    <title id="title">BRACU Research Portal</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <style>
    .header {
      width: 100%;
      height: 100vh;
      background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)),
        url("./wp.png");
      background-size: cover;
    }

    .nav {
      width: 100%;
      height: 100px;
      /* color: rgb(189, 178, 146); */
      display: flex;
      justify-content: space-around;
      align-items: center;
    }
    .logo {
      font-size: 2em;
      letter-spacing: 2px;
      color: rgb(244, 237, 230);
    }
    .menu a {
      text-decoration: none;
      color: rgb(236, 230, 211);
      padding: 10px 20px;
      font-size: 20px;
    }
    .menu a:hover {
      text-decoration: underline;
    }

    .register a {
      text-decoration: none;
      color: white;
      padding: 10px 20px;
      font-size: 20px;
      border-radius: 20%;
      background: rgb(167, 122, 74);
    }
    .register a:hover {
      background-color: #97551bbe;
      font-size: 18px;
    }
    .h-txt {
      margin-top: 2%;
      /* margin-left: 1%; */
      max-width: 650px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: left;
      font-style: bold;
    }
    .h-txt span {
      letter-spacing: 5px;
    }
    .h-txt h1 {
      font-size: 4em;
    }
    .h-txt a {
      text-decoration: none;
      background: rgb(18, 52, 80);
      color: white;
      padding: 10px 20px;
      letter-spacing: 5px;
    }
    #h2 {
      color: rgb(242, 216, 136);
    }
    #h3 {
      color: rgb(239, 226, 187);
    }
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      /* background-color:  #8f7553; */
      min-width: 200px;
      /* box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2); */
      z-index: 1;
    }
    .dropbtn{

      text-decoration: none;
      color: rgb(236, 230, 211);
      padding: 10px 20px;
      font-size: 20px;
    }
    

    .dropdown-content option {
      display: block;
      padding: 12px 16px;
      text-decoration: none;
      color: black;
      cursor: pointer;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    #sign-out-btn 
    {
      background-color: #97551bbe;
      color: white;
      padding: 10px 20px;
      font-size: 20px;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    #sign-out-btn:hover 
    {
      background-color: #7b462caa;
    }
  </style>
  <body class="header">
    <header>
      <nav class="nav">
        <div class="logo">BRACU Research Portal</div>
        <div class="menu">
          <a href="./projects.php">Projects</a>

          <a href="./papers.php">Our library</a>

          <div class="dropdown">
            <div class="dropbtn">Genre</div>
            <div class="dropdown-content">
              <select>
                <option value="option1">CSE</option>
                <option value="option2">Mathematics</option>
                <option value="option3">Physics</option>
                <option value="option3">Biotechnology</option>

              </select>
              </div>
            </div>
            <button id="sign-out-btn">Sign Out</button>
        </div>
      </nav>
      <section class="h-txt">
        <h2 id="h2">About us</h2>
        <h3 id="h3">
          An interactive repository for BRACU where you will be able to connect
          with and help other researchers from the institution, share resources,
          volunteer for projects and know about each other.
        </h3>
        <br />
      </section>
    </header>

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
