<?php
    session_start(); // Ensure session is started

    include("../../connections.php");

    $view_query = mysqli_query($connections, "SELECT * FROM tbl_admin");

    // Check if the user is logged in
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];

        // Fetch the account type from the database
        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];

        // Check if the account type is not 1 (admin)
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Records</title>
    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
    <style>
        <?php include("admin styles/admin_verify.css"); ?>
    </style>
</head>

<body>
    <table>
        <tr>
            <td><h2>ID</h2></td>
            <td><h2>First Name</h2></td>
            <td><h2>Middle Name</h2></td>
            <td><h2>Last Name</h2></td>
            <td><h2>Gender</h2></td>
            <td><h2>Mobile Number</h2></td>
            <td><h2>Username</h2></td>
            <td><h2>Password</h2></td>
            <td><h2>Attempt</h2></td>
            <td><h2>Relog Time</h2></td>
            <td><h2>Login Time</h2></td>
            <td><h2>Logout Time</h2></td>
            <td><h2>Account Type</h2></td>
            <td><h2>Date Registered</h2></td>
            <td><h2>Image</h2></td>
            <td><h2>Status</h2></td>
            <td><h2>Action</h2></td>
        </tr>

    <?php
    while ($row = mysqli_fetch_assoc($view_query)) {
        $db_id = $row["admin_id"];
        $db_f_name = $row["first_name"];
        $db_m_name = $row["middle_name"];
        $db_l_name = $row["last_name"];
        $db_gender = $row["sex"];
        $db_num = $row["mobile_number"];
        $db_u_name = $row["username"];
        $db_password = $row["password"];
        $db_attempt = $row["attempt"];
        $db_relog_time = $row["relog_time"];
        $db_login_time = $row["login_time"];
        $db_logout_time = $row["logout_time"];
        $db_acc_type = $row["account_type"];
        $db_date_registered = $row["date_registered"];
        $db_img = $row["img"];
        $db_stat = $row["status"];

        // Check if status is empty and set it to "Pending..." if it is
        $display_stat = empty($db_stat) ? "Pending..." : $db_stat;
        if ($db_acc_type == 1) {
            $display_stat = "Approve";
        }        

        echo "<tr>
                <td>$db_id</td>
                <td>$db_f_name</td>
                <td>$db_m_name</td>
                <td>$db_l_name</td>
                <td>$db_gender</td>
                <td>$db_num</td>
                <td>$db_u_name</td>
                <td>$db_password</td>
                <td>$db_attempt</td>
                <td>$db_relog_time</td>
                <td>$db_login_time</td>
                <td>$db_logout_time</td>
                <td>$db_acc_type</td>
                <td>$db_date_registered</td>
                <td>";

        // Fixing the image display syntax
        if (!empty($db_img)) {
            echo "<a href='../../uploads/profile/" . htmlspecialchars($db_img) . "' target='_blank'>
                    <img src='../../uploads/profile/" . htmlspecialchars($db_img) . "' alt='Admin Image' style='width: 125px; height: 120px; border-radius: 5px;'>
                </a>";
        } else {
            echo "<span>No Image</span>";
        }

        echo "</td>
                <td>$display_stat</td>
                <td>
                    <a href='edit_admin.php?admin_id=$db_id'><ion-icon name='pencil' class='icon' id='verify'></ion-icon></a>
                    <a href='admin_confirm_del.php?admin_id=$db_id'><ion-icon name='trash-outline' class='icon' id='delete'></ion-icon></a>
                </td>
            </tr>";
    }
    ?>

    </table>

    <a href='index.php' class='back'>Back</a>
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
