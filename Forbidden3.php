<?php
include("connections.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=javascript:window.close();">
    <link rel="icon" href="img/Brgy Estefania Logo.png" type="image/png">
    <title>Permission Denied</title>
</head>
<body>
    <center>
        <br>
        <br>
        <br>
        <h1>You don't have permission to access that page!</h1>
        <br>
        <!-- <h2 id="countdown" style="color: red">You will vanish within 3 seconds!</h2> -->
        <br>
        <h3>PAGE NOT FOUND 404</h3>
        <br>
        <br>
        <h5><a href="files/Admin/index.php" class="btn-delete">I Understand</a></h5>
    </center>

    <script>
        var seconds = 3;
        function countdown() {
            seconds--;
            document.getElementById("countdown").textContent = "You will vanish within " + seconds + " seconds!";
            if (seconds <= 0) {
                window.close();
            }
        }
        setInterval(countdown, 1000);
    </script>
</body>
</html>
