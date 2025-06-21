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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Neon Admin Panel">
    <meta name="author" content="">
    <link rel="icon" href="../../img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Admin Records</title>

    <!-- CSS Links -->
    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">

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
    <style>
        <?php include("admin styles/admin_records.css"); ?>
    </style>
</head>

<body class="page-body" data-url="http://neon.dev">

<div class="page-container">
        <?php include("sidebar.php"); ?>

        <div class="main-content">
            <?php include("header.php"); ?>
            <hr />
            <h3>Admin Records</h3>
            <br />

            <?php
            include "../../connections.php";

            $view_query = mysqli_query($connections, "SELECT * FROM tbl_admin");

            if ($view_query) {
            ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        let $table4 = jQuery("#table-4");
                        $table4.DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ]
                        });
                    });
                </script>
                
                <!-- Add a button above the table -->
                <button id="toggleColumnsButton" onclick="toggleColumns()">Show More Columns</button>

                <!-- Your table structure -->
                <table class="table table-bordered datatable" id="table-4">
                    <thead>
                        <tr>
                            <th width="8%" style="color: #434448; font-size: 1.4rem;">No.</th>
                            <th style="color: #434448;">First Name</th>
                            <th style="color: #434448;">Middle Name</th>
                            <th style="color: #434448;">Last Name</th>
                            <th style="color: #434448;">Gender</th>
                            <th style="color: #434448;">Mobile Number</th>
                            <th style="color: #434448;">Username</th>
                            <th class="extra-column" style="color: #434448;">Password</th>
                            <th class="extra-column" style="color: #434448;">Attempt</th>
                            <th class="extra-column" style="color: #434448;">Relog Time</th>
                            <th class="extra-column" style="color: #434448;">Login Time</th>
                            <th class="extra-column" style="color: #434448;">Logout Time</th>
                            <th class="extra-column" style="color: #434448;">Account Type</th>
                            <th class="extra-column" style="color: #434448;">Date Registered</th>
                            <th class="extra-column" style="color: #434448;">Image</th>
                            <th class="extra-column" style="color: #434448;">Status</th>
                            <th class="extra-column" style="color: #434448;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $counter = 1;

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

                            $display_stat = empty($db_stat) ? "Pending..." : $db_stat;
                            if ($db_acc_type == 1) {
                                $display_stat = "Approve";
                            }

                            echo "<tr>
                                <td style='font-size: 1.4rem;'>$counter.</td>
                                <td style='font-size: 1.4rem;'>$db_f_name</td>
                                <td style='font-size: 1.4rem;'>$db_m_name</td>
                                <td style='font-size: 1.4rem;'>$db_l_name</td>
                                <td style='font-size: 1.4rem;'>$db_gender</td>
                                <td style='font-size: 1.4rem;'>$db_num</td>
                                <td style='font-size: 1.4rem;'>$db_u_name</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$db_password</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$db_attempt</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$db_relog_time</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$db_login_time</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$db_logout_time</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$db_acc_type</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$db_date_registered</td>
                                <td class='extra-column'>";

                            if (!empty($db_img)) {
                                echo "<a href='../../uploads/profile/" . htmlspecialchars($db_img) . "' target='_blank'>
                                        <img src='../../uploads/profile/" . htmlspecialchars($db_img) . "' alt='Admin Image' style='width: 125px; height: 120px; border-radius: 5px;'>
                                    </a>";
                            } else {
                                echo "<span>No Image</span>";
                            }

                            echo "</td>
                                <td class='extra-column' style='font-size: 1.4rem;'>$display_stat</td>
                                <td class='extra-column'>
                                    <a href='edit_admin.php?admin_id=$db_id'><ion-icon name='pencil' class='icon' id='verify'></ion-icon></a>
                                    <a href='admin_confirm_del.php?admin_id=$db_id'><ion-icon name='trash-outline' class='icon' id='delete'></ion-icon></a>
                                </td>
                            </tr>";

                            $counter++;
                        }
                        ?>
                    </tbody>
                </table>
                <br />
            <?php
            } else {
                echo "Error: " . mysqli_error($connections);
            }

            mysqli_close($connections);
            ?>
            <br />
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

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
        function toggleColumns() {
            const button = document.getElementById('toggleColumnsButton');
            const columns = document.querySelectorAll('.extra-column');
            
            columns.forEach(column => {
                if (column.style.display === 'none' || column.style.display === '') {
                    column.style.display = 'table-cell';
                    button.textContent = 'Show Less Columns';
                } else {
                    column.style.display = 'none';
                    button.textContent = 'Show More Columns';
                }
            });
        }

        // Initially hide the extra columns
        document.addEventListener('DOMContentLoaded', () => {
            const columns = document.querySelectorAll('.extra-column');
            columns.forEach(column => column.style.display = 'none');
        });
    </script>
</body>

</html>