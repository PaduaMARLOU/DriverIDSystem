<?php
include("connections.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/Brgy Estefania Logo.png" type="image/png">
    <link rel="icon" href="img/wrong.png" type="image/png">
    <title>Permission Denied</title>
</head>
<body>
    <style>
        <?php include("files/Admin/admin styles/admin_forbid.css") ?>

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('img/security.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            filter: blur(4px);
            -webkit-filter: blur(5px);
            z-index: -1; /* Ensure it stays behind other content */
        }
    </style>
    <center>
        <br>
        <br>
        <br>
        <h1>You don't have permission to access that page!</h1>
        <br>
        <h2>You will vanish in <span id="countDown">5...</span></h2>
        <br>
        <h3>ERROR 404 NOT FOUND</h3>
        <br>
        <a href="files/Admin/index.php" class="btn-delete"><h5>I Understand</h5></a>
    </center>

    <div class="background"></div>

    <script>
        let sec = 5;
        function countdown() {
            sec--;
            document.getElementById("countDown").textContent = sec + "...";
            if (sec <= 0) {
                window.location.href = "index.php";
            }
        }
        setInterval(countdown, 1000);
    </script>
</body>
</html>

