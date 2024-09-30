<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />
    <link rel="icon" type="image/jpg" href="../../img/Brgy Estefania Logo.png">
    <title>Barangay Estefania Admin - Driver ID System</title>
    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/js/datatables/datatables.css">
    <link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
    <link rel="stylesheet" href="assets/js/select2/select2.css">
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="page-body" data-url="http://neon.dev">
    <div class="page-container">
        <?php include "sidebar.php" ?>
        <div class="main-content">
            <?php include "header.php" ?>
            <hr />
            <?php
            // Assuming you have already established a database connection
            include "../../connections.php";
            
           
            mysqli_close($connections);
            ?>
            <h2>Association Violation</h2>
            <br />
            <!-- Footer -->
		    <?php include "footer.php" ?>
            
            <!-- Imported styles on this page -->
            <link rel="stylesheet" href="assets/js/datatables/datatables.css">
            <link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
            <link rel="stylesheet" href="assets/js/select2/select2.css">
            <!-- Bottom scripts (common) -->
            <script src="assets/js/gsap/TweenMax.min.js"></script>
            <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
            <script src="assets/js/bootstrap.js"></script>
            <script src="assets/js/joinable.js"></script>
            <script src="assets/js/resizeable.js"></script>
            <script src="assets/js/neon-api.js"></script>
            <!-- Imported scripts on this page -->
            <script src="assets/js/datatables/datatables.js"></script>
            <script src="assets/js/select2/select2.min.js"></script>
            <script src="assets/js/neon-chat.js"></script>
            <!-- JavaScripts initializations and stuff -->
            <script src="assets/js/neon-custom.js"></script>
            <!-- Demo Settings -->
            <script src="assets/js/neon-demo.js"></script>
        </div>
    </div>
</body>
</html>
