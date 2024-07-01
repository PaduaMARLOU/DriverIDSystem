<?php

session_start();

include("../../connections.php");

if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email='$email'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if($account_type != 1){
        header("Location: ../../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

?>