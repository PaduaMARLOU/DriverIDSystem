<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Barangay Estefania Driver's Portal</title>
</head>
<body>
    <style>
        <?php include("driverportalcss/indexstyle.css"); ?>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('img/Barangay Estefania Hall.jpeg'); /* Removed backslashes */
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the background image */
            background-attachment: fixed; /* Makes the background image static */
            height: 100vh; /* Ensures the background covers the full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
            animation: fadeIn 1s ease forwards;
        }

        .back-btn {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 30px;
            filter: drop-shadow(1px 1px 2px gray);
            color: rgb(255, 255, 255);
            transition: .2s;
        }

        .back-btn:active {
            display: inline-block;
            transform: scale(.9);
        }
    </style>
    <div class="container">
    
        <a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
            <img src="img/Brgy. Estefania Logo (Old).png" alt="Barangay Estefania Logo" class="logo">
        </a>
        <h1>Driver's Portal</h1>
        <div class="btns">
            <a href="registrationpage.php" class="btn btn-register" id="btnRegister">Register</a>
            <a href="renewpage.php" class="btn btn-renew" id="btnRenew">Renew</a>
            <a href="scan.php" class="btn btn-scan" id="btnScan">Scan or Comment</a>
        </div>
        <p style="text-align: justify;">
            <strong style="color: green;">Register:</strong> Pindoton para makapili sang adlaw kung san-o gusto nga magpa-schedule para magparehistro sang ID. <br><br>
            <strong style="color: red;">Renew:</strong> Pindoton para malantaw kung active ukon indi ang status sang imo nga ID.<br><br>
            <strong style="color: blue;">Scan or Comment:</strong> Pindoton para mascan ang QR Code sang Driver's ID kag pwede man nga magkumento na hamtang sa ila.
        </p>
        <br><br>
        <footer class="footer">
            <i>© 2024 Capstone Project of BSIS 4-A Group 4</i>
        </footer>
    </div>
    <!-- <footer>
        © 2024 Capstone Project of BSIS 3-A Group 4
    </footer> -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>