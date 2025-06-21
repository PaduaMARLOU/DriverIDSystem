<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if ($account_type != 1) {
        header("Location: unauthorized.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: unauthorized.php");
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
    <link rel="icon" type="image/png" href="../../img/Brgy. Estefania Logo (Old).png">
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
    <link rel="stylesheet" type="text/css" href="../adminportalcss/register.css">
    
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
    
    <!-- Ionicons for Action Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body class="page-body" data-url="http://neon.dev">
    <div class="page-container">
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>
        
        <div class="main-content">
            <!-- Header -->
            <?php include "header.php"; ?>
            
            <hr />
            
            <!-- Admin Verification Content -->
            <div class="admin-verify-content">
                <!-- Include admin_verify.css styles specifically for this content section -->
                <style>
                    <?php include("admin styles/admin_approval.css"); ?>
                </style>

                <h1 class="verify">Admin Verification</h1><br>
                <!--
                <a href="index.php">
                    <ion-icon name="arrow-back-outline" class="icon" id="back-btn"></ion-icon>
                </a>-->

                <?php
                // Fetch pending admins
                $query = "SELECT * FROM tbl_admin WHERE status = 'Pending...' ORDER BY admin_id ASC";
                $result = mysqli_query($connections, $query);

                if (mysqli_num_rows($result) > 0) {
                    echo '<table id="admins">';
                    echo '<tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Action</th>
                          </tr>';
                    
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>{$row["admin_id"]}</td>";
                        echo "<td>{$row["first_name"]} {$row["middle_name"]}, {$row["last_name"]}</td>";
                        echo "<td>{$row["username"]}</td>";
                        echo "<td>{$row["sex"]}</td>";
                        echo "<td>{$row["email"]}</td>";
                        echo "<td>
                                <a href='confirm_approval.php?admin_id={$row['admin_id']}'>
                                    <ion-icon name='checkbox-outline' class='icon' id='verify'></ion-icon>
                                </a>
                                <a href='confirm_del.php?admin_id={$row['admin_id']}'>
                                    <ion-icon name='trash-outline' class='icon' id='delete'></ion-icon>
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p class="no-admins">No admins need verification at the moment.</p>';
                }
                ?>
            </div>

            <!-- Footer -->
            <?php include "footer.php"; ?>
        </div>
    </div>
</body>
</html>
