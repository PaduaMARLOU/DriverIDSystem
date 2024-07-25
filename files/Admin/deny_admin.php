<?php
session_start();
include("../../connections.php");

if (!isset($_SESSION["username"])) {
    header("Location: ../admin_login.php");
    exit;
}

$u_name = $_SESSION["username"];
$query_acc_type = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$u_name'");
$get_acc_type = mysqli_fetch_assoc($query_acc_type);
$acc_type = $get_acc_type["account_type"];

if ($acc_type != 1) {
    echo "<script>alert('You do not have permission to delete admins.'); window.location.href = 'admin_approval.php';</script>";
    exit;
}

if (isset($_POST["admin_id"])) {
    $admin_id = $_POST["admin_id"];
    $query = $connections->prepare("DELETE FROM tbl_admin WHERE admin_id = ?");
    $query->bind_param("i", $admin_id);

    if ($query->execute()) {
        echo "<script>alert('Admin Denied 💀'); window.location.href = 'admin_approval.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting admin.'); window.location.href = 'admin_approval.php';</script>";
    }

    $query->close();
} else {
    header("Location: admin_approval.php");
    exit;
}
?>
