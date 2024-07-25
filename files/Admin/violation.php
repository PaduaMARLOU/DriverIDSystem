<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />
    <link rel="icon" type="image/jpg" href="../../img/Brgy Estefania Logo.png">
    <title>Barangay Estefania Admin - Driver ID System</title>
    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/js/datatables/datatables.css">
    <link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
    <link rel="stylesheet" href="assets/js/select2/select2.css">
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="page-body" data-url="http://neon.dev">
    <div class="page-container">
        <?php include "sidebar.php" ?>
        <div class="main-content">
            <?php include "header.php" ?>
            <hr />
            <?php
            // Assuming you have already established a database connection
            include "../../connections.php";

            // Fetch data from the database and count the number of violations for each driver
            $query = "SELECT d.formatted_id, d.first_name, d.middle_name, d.last_name, d.driver_category, d.renew_stat, 
                      CONCAT(a.association_name, ' - ', a.association_area) AS association, 
                      COALESCE(COUNT(v.fk_driver_id), 0) AS num_violations
                      FROM tbl_driver d
                      LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
                      LEFT JOIN tbl_violation v ON d.driver_id = v.fk_driver_id AND (v.renewed_date IS NULL OR v.renewed_date = '')
                      WHERE d.renew_stat = 'Active'
                      GROUP BY d.driver_id, association";
            $result = mysqli_query($connections, $query);

            // Check if query was successful
            if ($result) {
                // Update renew_stat for drivers with more than 3 violations
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['num_violations'] >= 3 && $row['num_violations'] % 3 == 0) {
                        // Update renew_stat to indicate renewal needed only if renewed_date is null or empty
                        $update_query = "UPDATE tbl_driver SET renew_stat = 'Revoked due to Violations' WHERE formatted_id = '" . $row['formatted_id'] . "'";
                        mysqli_query($connections, $update_query);
                    }
                }
                ?>
                <h3>Driver Violations</h3>
                <br />
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        var $table4 = jQuery("#table-4");
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
                <table class="table table-bordered datatable" id="table-4">
                    <thead>
                        <tr>
                            <th>Driver ID</th>
                            <th>Driver Name</th>
                            <th>Vehicle Type</th>
                            <th>Association</th>
                            <th>Renew Status</th>
                            <th>Number of Violations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through each row of data
                        mysqli_data_seek($result, 0); // Reset the result set pointer
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['formatted_id']; ?></td>
                                <td><?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></td>
                                <td><?php echo $row['driver_category']; ?></td>
                                <td><?php echo $row['association']; ?></td>
                                <td><?php echo $row['renew_stat']; ?></td>
                                <td class="center">
                                    <?php
                                    if ($row['num_violations'] >= 3 && $row['num_violations'] % 3 == 0) {
                                        echo $row['num_violations'] . " - Maximum violations reached, requires renewal (Please efresh this page)";
                                    } else {
                                        echo $row['num_violations'];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="add_violation.php?driver_id=<?php echo $row['formatted_id']; ?>" class="btn btn-danger btn-sm btn-icon icon-left">
                                        <i class="entypo-flag"></i>
                                        Add Violation
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Driver ID</th>
                            <th>Driver Name</th>
                            <th>Vehicle Type</th>
                            <th>Association</th>
                            <th>Renew Status</th>
                            <th>Number of Violations</th>
                            <th>Actions</th>
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
