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
<body class="page-body" data-url="http://neon.dev">
    <style>
        .center {
            color: #F5462C;
            text-align: center;
        }
    </style>
    <div class="page-container">
        <?php include "sidebar.php" ?>
        <div class="main-content">
            <?php include "header.php" ?>
            <hr />
            <?php
            // Assuming you have already established a database connection
            include "../../connections.php";

            // Fetch data from the database and count the number of violations for each driver

            // Get the association name and view parameter from the URL
    $association_name = isset($_GET['association_name']) ? mysqli_real_escape_string($connections, $_GET['association_name']) : '';
    $view = isset($_GET['view']) ? mysqli_real_escape_string($connections, $_GET['view']) : '';

    // Initialize the base query to fetch data for all drivers, including those with violations
$query = "SELECT d.formatted_id, d.first_name, d.middle_name, d.last_name, d.driver_category, d.renew_stat, 
CONCAT(a.association_name, ' - ', a.association_area) AS association, 
COALESCE(COUNT(v.fk_driver_id), 0) AS num_violations
FROM tbl_driver d
LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
LEFT JOIN tbl_violation v ON d.driver_id = v.fk_driver_id AND (v.renewed_date IS NULL OR v.renewed_date = '')
WHERE d.renew_stat = 'Active'";

// Add filtering condition for association name
if ($association_name) {
$query .= " AND a.association_name = '$association_name'";
}

// Check for `view` parameter to modify behavior
if ($view == 'current') {
// Show only drivers with violations where `renewed_date` is NULL or empty
$query .= " AND v.fk_driver_id IS NOT NULL";
}

// Grouping to aggregate violations per driver and association
$query .= " GROUP BY d.driver_id, a.association_name, a.association_area";

// Optional: Order results by the number of violations in descending order
$query .= " ORDER BY num_violations DESC";

// Execute the query
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
                <h3 style="color: red;">Driver Violations for <?php echo htmlspecialchars($association_name); ?> (<?php echo ($view == 'total') ? 'Total Violations' : 'Current Violations'; ?>)</h3>
                <a href="<?php 
    // Check if `view=current` is in the current URL
    if (isset($_GET['view']) && $_GET['view'] === 'current') {
        // Remove the view parameter by linking to `violation.php` without any query string
        echo 'violation.php';
    } else {
        // Add `view=current` to the URL
        echo 'violation.php?view=current';
    }
?>" class="btn btn-primary">
    <?php 
    // Toggle button text based on the current `view` parameter
    echo (isset($_GET['view']) && $_GET['view'] === 'current') 
        ? "Click this to show All Drivers" 
        : "Click this to show All With Violations only"; 
    ?>
</a>

                <br />
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        let $table4 = jQuery("#table-4");
                        let accountType = <?php echo json_encode($account_type); ?>;
                        $table4.DataTable({
                    dom: 'Bfrtip',
                    buttons: accountType == 1 ? [ // Only show buttons if account_type is 1
                        {
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column (Actions)
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column (Actions)
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column (Actions)
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column (Actions)
                            }
                        }
                    ] : [] // No buttons if account_type is not 1
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
                                <td style="color: <?php echo ($row['renew_stat'] == 'Active') ? 'green' : 'black'; ?>;">
                                    <?php echo $row['renew_stat']; ?>
                                </td>
                                
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
