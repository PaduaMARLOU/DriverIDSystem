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

    <script src="assets/js/jquery-1.11.3.min.js"></script>

    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    
    <?php include "sidebar.php" ?>

    <div class="main-content">
                
        <?php include "header.php" ?>
        
        <hr />
                    
        
        <?php
        include "../../connections.php";

        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Get the current year
        $current_year = date('Y');
        
        // SQL query to update renew_stat to "For Renewal" if the registration year was last year
        $update_query = "
            UPDATE tbl_driver
            SET renew_stat = 'For Renewal'
            WHERE YEAR(driver_registered) = ($current_year - 1)
            AND renew_stat != 'For Renewal';
        ";
        
        // Execute the update query
        mysqli_query($connections, $update_query);
        
        // Now fetch the data for display as usual
        $query = "SELECT formatted_id, first_name, middle_name, last_name, driver_category, 
                  CONCAT(a.association_name, ' - ', a.association_area) AS association, renew_stat,
                  DATE_ADD(DATE_FORMAT(driver_registered, '%Y-01-01'), INTERVAL 1 YEAR) AS date_expired 
                  FROM tbl_driver d 
                  LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id 
                  WHERE renew_stat IN ('For Renewal', 'Revoked due to Violations')";
        $result = mysqli_query($connections, $query);

        // Check if query was successful
        if ($result) {
            ?>
            <h3>Renew Driver Data</h3>
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
                        <th>Date of Expiration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['formatted_id']; ?></td>
                            <td><?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></td>
                            <td><?php echo $row['driver_category']; ?></td>
                            <td><?php echo $row['association']; ?></td>
                            <td class="center"><?php echo $row['renew_stat']; ?></td>
                            <td><?php echo $row['date_expired']; ?></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm btn-icon icon-left" onclick="confirmRenew('<?php echo $row['formatted_id']; ?>')">
                                    <i class="entypo-arrows-ccw"></i>
                                    Renew
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
                        <th>Date of Expiration</th>
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

    <!-- Renew Success -->
    <script type="text/javascript">
        function confirmRenew(driverId) {
            if (confirm("Are you sure you want to renew this driver?")) {
                // Redirect to renew.php with the driver ID
                window.location.href = "successrenew.php?id=" + driverId;
            }
        }
    </script>
    
<!-- Footer -->
<?php include "footer.php" ?>

</body>
</html>
