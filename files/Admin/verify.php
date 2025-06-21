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

// Fetch expected drivers
$expectedDriversQuery = "SELECT COUNT(*) as expected_count 
                          FROM tbl_appointment 
                          WHERE DATE(appointment_date) = CURDATE()"; // Use the correct column name

$expectedDriversResult = mysqli_query($connections, $expectedDriversQuery);

if ($expectedDriversResult) {
    $expectedDrivers = mysqli_fetch_assoc($expectedDriversResult)['expected_count'];
} else {
    // Output the error message for debugging
    echo "Error fetching expected drivers: " . mysqli_error($connections);
    $expectedDrivers = 0; // Set to 0 if the query fails
}

// Fetch remaining drivers
$remainingDriversQuery = "SELECT COUNT(*) as remaining_count 
                          FROM tbl_driver 
                          INNER JOIN tbl_appointment 
                          ON tbl_driver.fk_sched_id = tbl_appointment.sched_id 
                          WHERE tbl_driver.verification_stat = 'Pending' 
                          AND DATE(tbl_appointment.appointment_date) = CURDATE()"; // Use the correct column name

$remainingDriversResult = mysqli_query($connections, $remainingDriversQuery);

if ($remainingDriversResult) {
    $remainingDrivers = mysqli_fetch_assoc($remainingDriversResult)['remaining_count'];
} else {
    // Output the error message for debugging
    echo "Error fetching remaining drivers: " . mysqli_error($connections);
    $remainingDrivers = 0; // Set to 0 if the query fails
}

// Fetch total number of drivers to verify
$totalDriversQuery = "SELECT COUNT(*) as total_count 
                      FROM tbl_driver 
                      WHERE verification_stat = 'Pending'"; // Count all drivers with pending verification

$totalDriversResult = mysqli_query($connections, $totalDriversQuery);

if ($totalDriversResult) {
    $totalDrivers = mysqli_fetch_assoc($totalDriversResult)['total_count'];
} else {
    // Output the error message for debugging
    echo "Error fetching total drivers: " . mysqli_error($connections);
    $totalDrivers = 0; // Set to 0 if the query fails
}

// Close the database connection
mysqli_close($connections);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />
    <link rel="icon" href="../../img/profile.png" type="image/png">
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
</head>
<body class="page-body" data-url="http://neon.dev">
<div class="page-container">
    <?php include "sidebar.php" ?>
    <div class="main-content">
        <?php include "header.php" ?>
        <hr />
        
        <h3 style="color: gray;">
            Expected Number of Drivers to Verify Today: <span style="color: blue;"><?php echo $expectedDrivers; ?></span><br>
            Drivers to Verify Today Remaining: <a href="verify.php?filter=today" style="color: blue; text-decoration: underline;"><?php echo $remainingDrivers; ?></a><br>
            Total Drivers to Verify: <a href="verify.php" style="color: blue; text-decoration: underline;"><?php echo $totalDrivers; ?></a>
        </h3>

        
        <?php
        // Check if filter parameter is set in the URL
        $filterToday = isset($_GET['filter']) && $_GET['filter'] == 'today';

        // Prepare the SQL query based on the filter
        if ($filterToday) {
            // Only show drivers that need to be verified today
            $query = "SELECT 
                          tbl_driver.fk_sched_id,
                          tbl_driver.formatted_id, 
                          tbl_driver.first_name, 
                          tbl_driver.middle_name, 
                          tbl_driver.last_name, 
                          tbl_driver.driver_category, 
                          tbl_driver.verification_stat, 
                          tbl_association.association_name, 
                          tbl_association.association_area
                      FROM 
                          tbl_driver 
                      INNER JOIN 
                          tbl_association 
                      ON 
                          tbl_driver.fk_association_id = tbl_association.association_id 
                      INNER JOIN 
                          tbl_appointment 
                      ON 
                          tbl_driver.fk_sched_id = tbl_appointment.sched_id 
                      WHERE 
                          tbl_driver.verification_stat = 'Pending' 
                      AND 
                          DATE(tbl_appointment.appointment_date) = CURDATE()"; // Assuming appointment_date is the date field
        } else {
            // Show all drivers that need to be verified
            $query = "SELECT 
                          tbl_driver.fk_sched_id,
                          tbl_driver.formatted_id, 
                          tbl_driver.first_name, 
                          tbl_driver.middle_name, 
                          tbl_driver.last_name, 
                          tbl_driver.driver_category, 
                          tbl_driver.verification_stat, 
                          tbl_association.association_name, 
                          tbl_association.association_area
                      FROM 
                          tbl_driver 
                      INNER JOIN 
                          tbl_association 
                      ON 
                          tbl_driver.fk_association_id = tbl_association.association_id 
                      WHERE 
                          tbl_driver.verification_stat = 'Pending'";
        }

        $result = mysqli_query($connections, $query);

        // Check if query was successful
        if ($result) {
            ?>
            <h3>Verify Driver Data</h3>
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
                        <th>Appointment ID</th>
                        <th>Driver ID</th>
                        <th>Driver Name</th>
                        <th>Vehicle Type</th>
                        <th>Association</th>
                        <th>Verification Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['fk_sched_id']; ?></td>
                            <td><?php echo $row['formatted_id']; ?></td>
                            <td><?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></td>
                            <td><?php echo $row['driver_category']; ?></td>
                            <td><?php echo $row['association_name'] . ' - ' . $row['association_area']; ?></td>
                            <td class="center">
                                <span style="color: #609AE5;"><?php echo $row['verification_stat'] . "..."; ?></span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-success btn-sm btn-icon icon-left" onclick="confirmVerify('<?php echo $row['formatted_id']; ?>')">
                                    <i class="entypo-check"></i>
                                    Check for Verification
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Driver ID</th>
                        <th>Driver Name</th>
                        <th>Vehicle Type</th>
                        <th>Association</th>
                        <th>Verification Status</th>
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
        <!-- Success Verification -->
        <script type="text/javascript">
            function confirmVerify(driverId) {
                if (confirm("Let's review the Driver's Profile first?")) {
                    // Open in a new tab
                    window.open("driver_profile_unverified.php?id=" + driverId, '_self');
                }
            }
        </script>
    </div>
</div>
</body>
</html>
