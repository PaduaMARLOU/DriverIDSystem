<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("../../connections.php");

    if(isset($_SESSION["username"])) {
        $username = $_SESSION["username"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];

        if($account_type != 1 && $account_type != 2){
            header("Location: ../../Forbidden.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }
</style>

<div class="row">
    <!-- Profile Info and Notifications -->
    <div class="col-md-6 col-sm-8 clearfix">
        <ul class="user-info pull-left pull-none-xsm" style="margin-top: 10px;">
            <li style="font-weight: bold; font-size: 30px; color: #333;" title="Brgy. Estefania Driver's ID System">Barangay Estefania Driver's ID System</li>
        </ul>
    </div>

    <!-- Raw Links -->
    <div class="col-md-6 col-sm-4 clearfix hidden-xs" title="Log-Out">
        <ul class="list-inline links-list pull-right">
            <li class="sep"></li>
            <li class="sep"></li>
            <li>
                <a href="../logout.php">
                    Log Out <i class="entypo-logout right"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
