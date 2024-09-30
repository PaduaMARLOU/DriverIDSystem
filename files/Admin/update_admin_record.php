<?php
include("../../connections.php");

$admin_id = $_POST["admin_id"];
$new_f_name = $_POST["new_f_name"];
$new_m_name = $_POST["new_m_name"];
$new_l_name = $_POST["new_l_name"];
$new_gender = $_POST["new_gender"];
$new_phone_num = $_POST["new_phone_num"];
$new_u_name = $_POST["new_u_name"];
$new_password = $_POST["new_password"];
$new_acc_type = $_POST["new_acc_type"];

mysqli_query($connections, "UPDATE tbl_admin SET first_name = '$new_f_name', middle_name = '$new_m_name', 
                last_name = '$new_l_name', sex = '$new_gender', mobile_number = '$new_phone_num', 
                username = '$new_u_name', password = '$new_password', account_type = '$new_acc_type' WHERE admin_id = '$admin_id'");

echo "<script>alert('Record has been updated!');</script>";
echo "<script>window.location.href='admin_records.php';</script>";
?>
