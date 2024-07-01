<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logging Out</title>

  <link rel="icon" type="image/jpg" href="../img/Brgy Estefania Logo.png">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        color: white;
    }

    body {
      background-image: url('../img/High-resolution\ photography\ of\ computer\ code\ art\ a.jpg');
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			background-attachment: fixed;
			text-align: center;
      overflow-x: hidden;
      overflow-y: hidden;
    }

    .main {
      position: relative;
      top: -17.5em;
    }

    .leave {
      position: relative;
      top: 17.5em;
    }

    .main-log {
      position: relative;
      top: 6.5em;
    }

    .log-out {
      position: relative;
      bottom: -8em;
      right: -.1em;
      font-size: 1.3em;
      text-shadow: 1px 1px 10px black;
    }

    .loader {
      box-shadow: 1px 1px 6px white;
      width: 350px;
      height: 30px;
      border-radius: 20px;
      color: white;
      border: 2px solid;
      position: relative;
      top: 10em;
    }
    .loader::before {
      content: "";
      position: absolute;
      margin: 2px;
      inset: 0 100% 0 0;
      border-radius: inherit;
      background: currentColor;
      animation: l6 3s infinite;
    }
    @keyframes l6 {
        100% {inset:0}
    }
  </style>
</head>
<body>

  <div class="main">
    <div class="leave">
      <img src="../img/loadleave-leave.gif" alt="leaving...">
    </div>

    <div class="main-log">
    <center>
      <div class="loader"></div>
    </center>
    

    <?php
    session_start();

    $logout = md5($_SESSION['username']);
    $username_md5 = md5($logout);
    unset($_SESSION['username']);

    session_unset();
    session_destroy();

    echo "<center class='log-out'><h2>Logging out...</h2></center></div></div>";

  // Redirect after 3 seconds
    echo "<script>
          setTimeout(function() {
              window.location.href = 'login.php?logout=$logout&v_1=$username_md5';
          }, 3000); 
        </script>";
  exit();
  ?>
</body>
</html>
