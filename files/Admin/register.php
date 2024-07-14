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
</head>
<body class="page-body" data-url="http://neon.dev">
    <div class="page-container">
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>
        
        <div class="main-content">
            <!-- Header -->
            <?php include "header.php"; ?>
            
            <hr />
            
            <!-- Your Content -->
            <div class="customregister-body">
                <div class="customregister-container">
                    <a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
                        <img src="../../img/Brgy Estefania Logo.png" alt="Barangay Estefania Logo" class="customregister-logo">
                    </a>
                    <h1 class="customregister-h1">Barangay Estefania Admin Access Registration Portal</h1>
                    <div>
                        <a href="#" class="customregister-btn customregister-btn-admin" id="adminButton">Admin</a>
                        <a href="#" class="customregister-btn customregister-btn-driver" id="driverButton">Driver</a>
                    </div>
                </div>
                <footer class="customregister-footer">
                    <i>© 2024 Capstone Project of BSIS 3-A Group 4</i>
                </footer>
            </div>
        </div>
    </div>

    <script>
        // Open admin registration page in a new tab when clicking on Driver button
        document.getElementById('adminButton').onclick = function() {
            window.open("admin_register.php", "_blank");
        };
        // Open driver registration page in a new tab when clicking on Driver button
        document.getElementById('driverButton').onclick = function() {
            window.open("../../registrationpage.php", "_blank");
        };
    </script>
</body>
</html>
