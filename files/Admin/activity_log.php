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

include "../../connections.php"; // Include your database connection

// Fetch data from the database and order by action_date in descending order
$query = "SELECT log_id, fk_admin_id, action_details, action_date, fk_driver_id FROM tbl_log ORDER BY action_date DESC";
$result = mysqli_query($connections, $query);

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
<style>
	table {
		color: #48484C;
	}

	th {
		font-size: 1em;
	}
</style>
    <div class="page-container">
        <?php include "sidebar.php"; ?>
        <div class="main-content">
            <?php include "header.php"; ?>
            <h3 style="color: orange;">Activity Log Summary</h3>
            <hr>

            <h3>Export Log Data</h3>
            <br />

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    let $logTable = jQuery("#new-log-table").DataTable({
                        dom: 'Bfrtip', // Specify the layout of the table controls
                        buttons: [
                            {
                                extend: 'copyHtml5',
                                exportOptions: {
                                    modifier: {
                                        page: 'current' // Export only the current page
                                    }
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                exportOptions: {
                                    modifier: {
                                        page: 'current' // Export only the current page
                                    }
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                exportOptions: {
                                    modifier: {
                                        page: 'current' // Export only the current page
                                    }
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                exportOptions: {
                                    modifier: {
                                        page: 'current' // Export only the current page
                                    }
                                }
                            }
                        ],
                        "order": [[3, "desc"]], // Order by Action Date descending
                        paging: true, // Enable pagination
                        searching: true, // Enable searching
                        info: true, // Enable info display
                        stateSave: true, // Enables state saving
                        pageLength: 10 // Default number of entries to show
                    });

                    // Handle custom input for entries
                    $('#custom-length').on('change', function() {
                        var value = parseInt($(this).val(), 10); // Get the value from input
                        if (!isNaN(value) && value > 0) {
                            $logTable.page.len(value).draw(); // Set the new page length and redraw the table
                        } else {
                            $(this).val($logTable.page.len()); // Reset to current page length if invalid
                        }
                    });
                });
            </script>

            <div id="logTableContainer">
                <label for="custom-length">Show entries:</label>
                <input type="number" id="custom-length" value="10" min="1" style="width: 60px; margin-right: 10px;" />

                <table class="table table-bordered table-responsive" id="new-log-table">
                    <thead>
                        <tr>
                            <th>Log ID</th>
                            <th>Admin ID</th>
                            <th>Action Details</th>
                            <th>Action Date</th>
                            <th>Driver ID</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                        <?php
                        // Check if query was successful and populate the table
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$row['log_id']}</td>
                                    <td>{$row['fk_admin_id']}</td>
                                    <td>{$row['action_details']}</td>
                                    <td>" . date("Y-m-d h:i A", strtotime($row['action_date'])) . "</td>
                                    <td>{$row['fk_driver_id']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No log entries found.</td></tr>";
                        }
                        // Close the database connection
                        mysqli_close($connections);
                        ?>
                    </tbody>
                </table>
            </div>

            <br>
            <?php include "footer.php"; ?>
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


	<script src="assets/js/neon-demo.js"></script>
    <script>
    // Function to fetch and update the log table
    function updateLogTable() {
        // Get the current page index before updating
        var table = $('#new-log-table').DataTable();
        var currentPage = table.page.info().page; // Get current page index
        
        $.ajax({
            url: '', // This will point to the same file to reload the data
            method: 'GET',
            success: function(data) {
                // Get the new rows from the response
                var newRows = $(data).find('#logTableBody').html();
                
                // Clear the existing DataTable
                table.clear(); // Clear existing data
                
                // Update the table body with new rows
                $('#logTableBody').html(newRows); // Update the table body
                
                // Reinitialize the DataTable with the new data
                table.rows.add($('#logTableBody tr')).draw(); // Redraw the DataTable

                // Set back to the previous page
                table.page(currentPage).draw(false); // Go back to the saved page
                console.log("Table updated at: " + new Date().toLocaleTimeString());
            },
            error: function(xhr, status, error) {
                console.error("Error fetching log data: " + error);
            }
        });
    }

    // Refresh the log table every second
    setInterval(updateLogTable, 5000); // Refresh every 5 second
</script>


</body>
</html>
