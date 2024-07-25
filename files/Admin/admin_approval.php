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

        if($account_type != 1){
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
    <title>Admin Approval</title>

    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
    <style>
        <?php include("admin styles/admin_verify.css"); ?>
    </style>
</head>
<body>
    <div class="center">
        <h1 class="verify">Admin Verification</h1><br>

        <a href="index.php"><ion-icon name="arrow-back-outline" class="icon" id="back-btn"></ion-icon></a>

        <table id="admins">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Gender</th>
                <th>Password</th>
                <th>Action</th>
            </tr>

            <?php
                $query = "SELECT * FROM tbl_admin WHERE status = 'Pending...' ORDER BY admin_id ASC";
                $result = mysqli_query($connections, $query);

                while ($row = mysqli_fetch_array($result)) {
            ?>
            <tr>
                <td><?php echo $row["admin_id"]; ?></td>
                <td><?php echo $row["first_name"]; ?>  <?php echo $row["middle_name"]; ?>, <?php echo $row["last_name"]; ?></td>
                <td><?php echo $row["username"]; ?></td>
                <td><?php echo $row["sex"]; ?></td>
                <td><?php echo $row["password"]; ?></td>
                <td>
                    <a href="confirm_approval.php?admin_id=<?php echo $row['admin_id']; ?>"><ion-icon name="checkbox-outline" class="icon" id="verify"></ion-icon></a>
                    <a href="confirm_del.php?admin_id=<?php echo $row['admin_id']; ?>"><ion-icon name="trash-outline" class="icon" id="delete"></ion-icon></a>
                </td>
            </tr>
            <?php
                }
            ?>
        </table>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
