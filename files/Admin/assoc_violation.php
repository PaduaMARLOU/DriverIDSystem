<?php

session_start();

include("../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />
    <link rel="icon" type="image/png" href="../../img/Brgy. Estefania Logo (Old).png">
    <title>Barangay Estefania Admin - Driver ID System</title>

    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<style>
    table {
        color: #434448
    }

    .red-text {
        color: #F5462C;
    }
</style>

<body class="page-body" data-url="http://neon.dev">
    <div class="page-container">
        <?php include "sidebar.php" ?>
        <div class="main-content">
            <?php include "header.php" ?>
            <hr />
            <h3 style="color: #F5462C;">Association Violations</h3>
            <br />
            <?php
            include "../../connections.php";

            $query = "
                SELECT 
                    CONCAT(a.association_name, ' - ', a.association_area) AS association, 
                    COALESCE(COUNT(v.fk_driver_id), 0) AS num_violations 
                FROM tbl_association a
                LEFT JOIN tbl_driver d ON a.association_id = d.fk_association_id
                LEFT JOIN tbl_violation v ON d.driver_id = v.fk_driver_id 
                WHERE d.renew_stat = 'Active'
                GROUP BY a.association_id";

            $result = mysqli_query($connections, $query);

            if ($result) {
            ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        let $table4 = jQuery("#table-4");
                        let accountType = <?php echo json_encode($account_type); ?>;

                        $table4.DataTable({
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
                <table class="table table-bordered datatable" id="table-4">
                    <thead>
                        <tr>
                            <th width="8%" style="color: #434448; font-size: 1.4rem;">No.</th> <!-- Added Display Number Column -->
                            <th width="50%" style="color: #434448; font-size: 1.4rem;">Association</th>
                            <th style="color: #434448; font-size: 1.4rem;">Total Violations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Initialize counter for display number
                        $counter = 1;

                        // Loop through each row of data
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td style="font-size: 1.4rem; font-weight:800px;"><?php echo $counter++ . ".)"; ?></td> <!-- Display Incremented Number -->
                                <td style="font-size: 1.4rem;"><?php echo htmlspecialchars($row['association']); ?></td>
                                <td style="font-size: 1.4rem;" class="red-text"><?php echo $row['num_violations']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="color: #434448; font-size: 1.4rem;">No.</th> 
                            <th style="color: #434448; font-size: 1.4rem;">Association</th>
                            <th style="color: #434448; font-size: 1.4rem;">Total Violations</th>
                        </tr>
                    </tfoot>
                </table>
                <br />
            <?php
            } else {
                echo "Error: " . mysqli_error($connections);
            }

            mysqli_close($connections);
            ?>
            <br />
            <!-- Footer -->
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

</body>

</html>