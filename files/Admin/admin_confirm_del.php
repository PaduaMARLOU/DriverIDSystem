<?php
session_start();
include("../../connections.php");

if (!isset($_SESSION["username"])) {
    header("Location: admin_login.php");
    exit;
}

$u_name = $_SESSION["username"];
$query_acc_type = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$u_name'");
$get_acc_type = mysqli_fetch_assoc($query_acc_type);
$acc_type = $get_acc_type["account_type"];

if ($acc_type != 1) {
    echo "You do not have permission to delete admins.";
    exit;
}

if (isset($_GET["admin_id"])) {
    $admin_id = $_GET["admin_id"];
    $query = $connections->prepare("SELECT first_name FROM tbl_admin WHERE admin_id = ?");
    $query->bind_param("i", $admin_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $fetch = $result->fetch_assoc();
        $f_name = $fetch["first_name"];
    } else {
        $f_name = "User"; // Fallback name if admin_id is not found
    }

    $query->close();
} else {
    header("Location: admin_approval.php"); // Redirect if no admin_id is passed
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Confirmation</title>
    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
</head>
<body>
    <style>
        <?php include("admin styles/admin_del.css"); ?>

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../../img/remove.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            filter: blur(8px);
            -webkit-filter: blur(2px);
            z-index: -1; /* Ensure it stays behind other content */
        }
    </style>

    <div class="background"></div>
        <center class="all"><br>
            <h1 class="confirmation">Admin Delete Confirmation</h1>
            <div class="container">
                <h2 class="confirm">Are you sure you want to delete <?php echo htmlspecialchars($f_name); ?>?</h2>

                <form action="deny_admin.php" method="POST">
                    <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>">
                    <button type="submit" name="delete" class="options" id="yes">Yes</button>
                </form>
                <a href="admin_records.php" class="options" id="no">No</a>
            </div>
        </center>
</body>
</html>
