<?php
    session_start();

    include("../../connections.php");

    if(isset($_SESSION["email"])) {

        $email = $_SESSION["email"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email = '$email'");
        $fetch = mysqli_fetch_assoc($authentication);
        $acc_type = $fetch["account_type"];
        $f_name = $fetch["first_name"];

        $_SESSION["first_name"] = $f_name;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver's ID System</title>

    <link rel="icon" type="image/jpg" href="../../img/Brgy Estefania Logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        .container {
            width: 100%;
            height: 100vh;
            color: white;
            text-shadow: 1px 2px 10px rgba(123, 115, 115, 0.711);
        }

        .container .side-nav {
            width: 105px;
            height: 100vh;
            overflow: hidden;
            position: fixed;
            padding: 32px 16px;
            border-radius: 8px;
            background: linear-gradient(45deg,rgb(38, 133, 250),rgba(20, 240, 108, 0.647));
            transition: width .6s;
        }

        .side-nav {
            position: fixed;
            width: 105px; 
            height: 100vh;
            overflow: hidden;
            padding: 32px 16px;
            border-radius: 8px;
            background: linear-gradient(45deg, rgb(38, 133, 250), rgba(20, 240, 108, 0.647));
            transition: width .6s, left .6s;
            left: 0; 
        }


        label .close-btn {
            display: none;
        }

        .container .logo h2 {
            position: relative; 
            font-size: 20px;
            text-transform: uppercase;
            text-align: center;
            opacity: 0;
            bottom: -9px;
            right: -24.5px;
            pointer-events: none;
            transition: .3s ease;
        }

        .container .logo a img {
            position: fixed;
            left: 1em;
        }

        .side-nav .search-bar {
            margin-top: 32px;
            position: relative;
        }

        .side-nav .search-bar i {
            position: absolute;
            color: rgba(0, 0, 0, 0.68);
            font-size: 24px;
            left: 38px;
            top: 50%;
            text-shadow: none;
            transform: translate(-50%, -50%);
        }

        .side-nav .search-bar input {
            width: 50%;
            padding: 11px;
            padding-left: 40px;
            border-radius: 5px;
            border: none;
            outline: none;
            margin-left: 15px;
        }

        .side-nav .logo a img {
            opacity: 0;
            transition: .4s ease;
        }

        .menu-btn {
            position: fixed;
            font-size: 50px;
            top: .60em;
            left: .6em;
            transition: .4s ease;
        }

        .side-nav {
            position: fixed;
            width: 105px; 
            height: 100vh;
            overflow: hidden;
            padding: 32px 16px;
            border-radius: 8px;
            background: linear-gradient(45deg, rgb(38, 133, 250), rgba(20, 240, 108, 0.647));
            transition: width .6s, right .6s; 
            right: 0; 
        }


        .close-btn {
            position: absolute;
            top: 1.5%;
            right: 3%;
            font-size: 24px;
            cursor: pointer;
            z-index: 3; 
        }

        .side-nav .icon-items ul {
            list-style: none;
            margin-top: 32px;
            padding: 0 10px;
        }

        .side-nav .icon-items ul li {
            cursor: pointer;
            margin: 4px;
            padding: 12px 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: .25s ease;
        } 

        .icon-items .active,
        .side-nav .icon-items ul li:hover {
            background: rgb(255, 162, 0);
            box-shadow: 1px 1px 10px rgba(132, 128, 128, 0.595);
        }

        .side-nav .icon-items ul li i {
            font-size: 26px;
            padding: 0 12px;
        }

        .side-nav .icon-items ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 17px;
            padding-left: 18px;
            opacity: 0;
            pointer-events: none;
            transition: .3s ease;
        }

        #click {
            display: none;
        }

        .container label {
            position: absolute;
            left: 3%;
            top: 1.5%;
            font-size: 24px;
            z-index: 1;
            cursor: pointer;
        }

        #click:checked ~ .side-nav {
            width: 260px;
        }

        #click:checked ~ .side-nav .icon-items ul li a {
            opacity: 1;
            pointer-events: auto;
        }

        #click:checked ~.side-nav .logo a img {
            opacity: 1;
            pointer-events: auto;
        }

        #click:checked ~ .side-nav .logo h2 {
            opacity: 1;
            pointer-events: auto;
        }

        #click:checked ~ .side-nav .search-bar input {
            width: 100%;
            margin-left: 0;
        }

        #click:checked ~ .side-nav .search-bar i {
            left: 20px;    
        }

        #click:checked ~ label {
            left: 1%;
        }

        #click:checked ~ label .menu-btn {
            font-size: 25px;
            transition: .4s ease;
        }

        #click:checked ~ label .close-btn {
            display: none;
        }
    </style>

    <div class="container">

        <input type="checkbox" id="click">
        <label for="click">
            <i class='menu-btn bx bx-menu'></i>
            <i class='close-btn bx bx-x-circle'></i>
        </label>
        
        <div class="side-nav">
            <div class="logo">
                <a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank"><img src="../../img/Brgy Estefania Logo.png" alt="Barangay Estefania" width="50px"></a>
                <h2>Welcome <?php echo $_SESSION["first_name"] ?> </h2>
            </div>

            <div class="search-bar">
                <form action="#">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search...">
                </form>
            </div>

            <div class="icon-items">
                <ul>
                    <li >
                        <i class='bx bxs-dashboard'></i>
                        <a href="enhancedsidebar" class="word">Dashboard</a>
                    </li>
                    <li>
                        <i class='bx bxs-user'></i>
                        <a href="../Drivers/drivertable.php" class="word">Driver</a>
                    </li>
                    <li>
                        <i class='bx bxs-registered'></i>
                        <a href="register" class="word">Registration</a>
                    </li>
                    <li>
                        <i class='bx bxs-folder-plus'></i>
                        <a href="renewal.php" class="word">Renewal</a>
                    </li>
                    <li>
                        <i class='bx bxs-message-alt-check'></i>
                        <a href="verify" class="word">To Verify</a>
                    </li>
                    <li>
                        <i class='bx bxs-id-card'></i>
                        <a href="generate" class="word">Generate ID</a>
                    </li>
                    <li>
                        <i class='bx bxs-error-circle'></i>
                        <a href="violation" class="word">Violation</a>
                    </li>
                    <li>
                        <i class='bx bxs-log-out'></i>
                        <a href="../logout" class="word">Log-out</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>