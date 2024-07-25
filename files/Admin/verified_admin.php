<?php
session_start();
include("../../connections.php");

if (!isset($_SESSION["username"])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_POST["approve"])) {
    $id = $_POST["admin_id"];
    $update = "UPDATE tbl_admin SET account_type = 2, status = 'Approved' WHERE admin_id = ?";
    $stmt = $connections->prepare($update);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script type='text/javascript'>";
        echo "alert('Verified Admin Successfully! 🤩');";
        echo "window.location.href='admin_approval.php';";
        echo "</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
