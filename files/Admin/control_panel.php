<?php

session_start();

include("../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if ($account_type != 1) {
        header("Location: ../../Forbidden2.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden2.php");
    exit; // Ensure script stops executing after redirection
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Neon Admin Panel">
    <meta name="author" content="">
    <link rel="icon" type="image/jpg" href="../../img/Brgy Estefania Logo.png">
    <title>Barangay Estefania Admin - Driver ID System</title>

    <!-- CSS Links -->
    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" type="text/css" href="../adminportalcss/register.css"> <!-- Your custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CSS -->

    <!-- JavaScript Links -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <script src="assets/js/gsap/TweenMax.min.js"></script>
    <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/joinable.js"></script>
    <script src="assets/js/resizeable.js"></script>
    <script src="assets/js/neon-api.js"></script>
    <script src="assets/js/datatables/datatables.js"></script>
    <script src="assets/js/select2/select2.min.js"></script>
    <script src="assets/js/neon-chat.js"></script>
    <script src="assets/js/neon-custom.js"></script>
    <script src="assets/js/neon-demo.js"></script>

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom CSS for Control Panel -->
    <style>
        .control-panel-header {
            border-radius: 5px;
            background: #2c3e50;
            color: #ecf0f1;
            padding: 10px;
            text-align: center;
        }

        .control-panel-header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .control-panel-container {
            padding: 20px;
        }

        .control-panel-box {
            background: #ecf0f1;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
        }

        .control-panel-box h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .control-panel-btn {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .control-panel-btn i {
            margin-right: 10px;
        }

        a:hover {
            color: white;
            font-size: 14px;
            transition: .3s;
        }

        a:active {
            color: white;
            transform: scale(.9);
        }

        a:focus {
            color: white;
            text-shadow: 1px 2px 3px #4A4A4A;
        }

        .customregister-footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #2c3e50;
            color: #ecf0f1;
        }

        footer {
            border-radius: 5px;
        }
        footer i {
            font-size: 12px;
        }
    </style>
</head>

<body class="page-body" data-url="http://neon.dev">
    <div class="page-container">
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <!-- Header -->
            <?php include "header.php"; ?>

            <br><br>
            <div class="control-panel-header">
                <h1>Barangay Estefania Admin Control Panel</h1>
            </div>

            <div class="control-panel-container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="control-panel-box">
                            <h2>Calendar Control</h2>
                            <a href="#" class="control-panel-btn" id="calendarButton">
                                <i class="fas fa-calendar-alt"></i> Adjust Calendar Dates
                            </a>
                            <script>
                                // Open admin registration page in a new tab when clicking on Calendar Control button
                                document.getElementById('calendarButton').onclick = function() {
                                    window.open("control/calendar_entries.php", "_self");
                                };
                            </script>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="control-panel-box">
                            <h2>Account Access Control</h2>
                            <a href="#" class="control-panel-btn" id="accountButton">
                                <i class="fas fa-user-shield"></i> Manage Accounts
                            </a>
                            <script>
                                // Open driver registration page in a new tab when clicking on Account Access Control button
                                document.getElementById('accountButton').onclick = function() {
                                    window.open("admin_records.php", "_self");
                                };
                            </script>
                        </div>
                    </div>
                </div>
            </div>

            <div class="control-panel-container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="control-panel-box">
                            <h2>Association Settings</h2>
                            <a href="#" class="control-panel-btn" id="associationButton">
                                <i class="fas fa-users"></i> Manage Association Details
                            </a>
                            <script>
                                // Open admin registration page in a new tab when clicking on Calendar Control button
                                document.getElementById('associationButton').onclick = function() {
                                    window.open("control/association_settings.php", "_self");
                                };
                            </script>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="control-panel-box">
                            <h2>Violation Categories</h2>
                            <a href="#" class="control-panel-btn" id="violationButton">
                                <i class="fas fa-gavel"></i> Manage Violation Categories
                            </a>
                            <script>
                                // Open driver registration page in a new tab when clicking on Account Access Control button
                                document.getElementById('violationButton').onclick = function() {
                                    window.open("control/violation_categories.php", "_self");
                                };
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="customregister-footer">
                <i>© 2024 Capstone Project of BSIS 3-A Group 4</i>
            </footer>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>