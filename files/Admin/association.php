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

    if ($account_type != 1 && $account_type != 2) { // Check if account_type is not 1 or 2
        header("Location: unauthorized.php");
        exit;
    }
} else {
    header("Location: unauthorized.php");
    exit;
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
    <link rel="icon" type="image/jpg" href="../../img/Brgy. Estefania Logo (Old).png">
    <title>Barangay Estefania Admin - Association List</title>

    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <script src="assets/js/jquery-1.11.3.min.js"></script>
</head>

<body class="page-body" data-url="http://neon.dev">

<style>
    table {
        color: #48484C;
    }

    th {
        font-size: 1em;
    }
</style>

<div class="page-container">
    <?php include "sidebar.php" ?>

    <div class="main-content">
        <?php include "header.php" ?>

        <hr />

        <h3 style="color: orange;" title="Association Summary Table">Association Table Summary</h3>
        <p><i>Notice: To manage associations, go to control panel.</i></p>
        <hr />

        <?php
        // Fetch data from the database including association details
        $query = "SELECT a.association_id, a.association_category, a.association_name, a.association_area, 
        COUNT(CASE WHEN d.verification_stat = 'Registered' AND d.renew_stat = 'Active' THEN 1 END) AS num_drivers
        FROM tbl_association a
        LEFT JOIN tbl_driver d ON a.association_id = d.fk_association_id
        GROUP BY a.association_id";
        $result = mysqli_query($connections, $query);


        // Check if query was successful
        if ($result) {
        ?>
            <!--<h3 title="Export Association Data">Export Association Data</h3>
            <br />-->

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    let $table5 = jQuery("#table-5");
                    let accountType = <?php echo json_encode($account_type); ?>;

                    $table5.DataTable({
                        dom: 'Bfrtip',
                        buttons: accountType == 1 ? [ // Only show buttons if account_type is 1
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ] : [] // No buttons if account_type is not 1
                    });
                });
            </script>

            <table class="table table-bordered datatable" id="table-5" title="Association Table">
                <thead>
                    <tr>
                        <th style="color: #48484C;">Association ID</th>
                        <th style="color: #48484C;">Category</th>
                        <th style="color: #48484C;">Name</th>
                        <th style="color: #48484C;">Area</th>
                        <th style="color: #48484C;">Number of Drivers</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['association_id']; ?></td>
                            <td><?php echo $row['association_category']; ?></td>
                            <td><?php echo $row['association_name']; ?></td>
                            <td><?php echo $row['association_area']; ?></td>
                            <td><?php echo $row['num_drivers']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="color: #48484C;">Association ID</th>
                        <th style="color: #48484C;">Category</th>
                        <th style="color: #48484C;">Name</th>
                        <th style="color: #48484C;">Area</th>
                        <th style="color: #48484C;">Number of Drivers</th>
                    </tr>
                </tfoot>
            </table>

            <br />
        <?php
        } else {
            // Query failed
            echo "Error: " . mysqli_error($connections);
        }

        // Close the database connection
        mysqli_close($connections);
        ?>

        <!-- Footer -->
        <?php include "footer.php" ?>
    </div>
</div>

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

</body>
</html>
