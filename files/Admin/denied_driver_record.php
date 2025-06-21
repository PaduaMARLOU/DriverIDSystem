<?php

session_start();

include "../../connections.php"; // Ensure the correct database connection is made

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $admin_id = $fetch["admin_id"];
    $account_type = $fetch["account_type"];

    if ($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

// Handle Reconsider Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'reconsider' && isset($_POST['formatted_id'])) {
        $formatted_id = $_POST['formatted_id'];

        // Fetch the driver_id using the formatted_id
        $driver_query = "SELECT driver_id FROM tbl_driver WHERE formatted_id = ?";
        if ($stmt = mysqli_prepare($connections, $driver_query)) {
            mysqli_stmt_bind_param($stmt, 's', $formatted_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $driver_id);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($driver_id) {
                // Prepare the query to update the driver's verification status to 'Pending'
                $query = "UPDATE tbl_driver SET verification_stat = 'Pending' WHERE formatted_id = ?";
                if ($stmt = mysqli_prepare($connections, $query)) {
                    mysqli_stmt_bind_param($stmt, 's', $formatted_id);
                    if (mysqli_stmt_execute($stmt)) {
                        // Log the Reconsider action
                        $action_details = "Admin reconsidered Driver ID: $formatted_id";
                        date_default_timezone_set('Asia/Manila');
                        $action_date = date('Y-m-d H:i:s');
                        $log_query = "INSERT INTO tbl_log (fk_admin_id, fk_driver_id, action_details, action_date) VALUES (?, ?, ?, ?)";
                        if ($log_stmt = mysqli_prepare($connections, $log_query)) {
                            mysqli_stmt_bind_param($log_stmt, 'isss', $admin_id, $driver_id, $action_details, $action_date);
                            if (!mysqli_stmt_execute($log_stmt)) {
                                error_log("Error logging reconsider action: " . mysqli_error($connections));
                            }
                        }

                        echo "Driver reconsidered successfully.";
                    } else {
                        echo "Error updating driver's verification status: " . mysqli_error($connections);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "Error preparing the reconsider query: " . mysqli_error($connections);
                }
            } else {
                echo "Driver not found.";
            }
        } else {
            echo "Error preparing the driver query: " . mysqli_error($connections);
        }
    }

    // Handle Delete Action
    if ($action === 'delete' && isset($_POST['formatted_id'])) {
        $formatted_id = $_POST['formatted_id'];

        // Fetch the driver_id using the formatted_id
        $driver_query = "SELECT driver_id FROM tbl_driver WHERE formatted_id = ?";
        if ($stmt = mysqli_prepare($connections, $driver_query)) {
            mysqli_stmt_bind_param($stmt, 's', $formatted_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $driver_id);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($driver_id) {
                // Redirect to delete_driver.php with the formatted_id as a parameter
                header("Location: delete_driver.php?formatted_id=" . urlencode($formatted_id));
                exit; // Ensure the script stops executing after the redirection
            } else {
                echo "Driver not found.";
            }
        } else {
            echo "Error preparing the driver query: " . mysqli_error($connections);
        }
    }

    exit; // Stop further script execution after handling the AJAX request
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
	<?php include "sidebar.php" ?>
	<div class="main-content">
		<?php include "header.php" ?>
		<hr />
		<h3 style="color: orange;" title="Driver Summary Table">Denied Driver Table Summary</h3>
		<hr>

		<?php
		include "../../connections.php";

		$query = "SELECT d.formatted_id, d.first_name, d.middle_name, d.last_name, d.driver_category, a.association_name, a.association_area, d.verification_stat 
				FROM tbl_driver d
				LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
				WHERE d.verification_stat = 'Denied'";
		$result = mysqli_query($connections, $query);

		if ($result) {
			?>
			<h3 title="Export Driver Data">Export Denied Driver Data</h3>
			<br />

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

                // Handle Reconsider action
                $(document).on('click', '.reconsider-btn', function(e) {
                    e.preventDefault();
                    let formattedId = $(this).data('id');
                    let driverName = $(this).closest('tr').find('td:nth-child(2)').text(); // Get Driver Name from the table row

                    if (confirm('Are you sure you want to reconsider this driver?')) {
                        $.ajax({
                            url: '',
                            method: 'POST',
                            data: {
                                action: 'reconsider',
                                formatted_id: formattedId
                            },
                            success: function(response) {
                                alert(driverName + ' has been reconsidered and status set to Pending.');
                                location.reload(); // Reload the page to reflect changes
                            },
                            error: function() {
                                alert('An error occurred while reconsidering the driver.');
                            }
                        });
                    }
                });

                // Handle Delete action
                $(document).on('click', '.delete-btn', function(e) {
                    e.preventDefault();
                    let formattedId = $(this).data('id');
                    let driverName = $(this).closest('tr').find('td:nth-child(2)').text(); // Get Driver Name from the table row

                    if (confirm('Are you sure you want to delete this driver?')) {
                        $.ajax({
                            url: '', // This should be the same file that contains the delete action logic
                            method: 'POST',
                            data: {
                                action: 'delete',
                                formatted_id: formattedId
                            },
                            success: function(response) {
                                alert(driverName + '`s record deletion at the next step...');
                                // Redirect to delete_driver.php
                                window.location.href = 'delete_driver.php?formatted_id=' + encodeURIComponent(formattedId);
                            },
                            error: function() {
                                alert('An error occurred while deleting the driver.');
                            }
                        });
                    }
                });

            });
        </script>


			<table class="table table-bordered datatable" id="table-4" title="Driver's Table">
				<thead>
					<tr>
						<th style="color: #48484C;">Driver ID</th>
						<th style="color: #48484C;">Driver Name</th>
						<th style="color: #48484C;">Vehicle Type</th>
						<th style="color: #48484C;">Association</th>
						<th style="color: #48484C;">Driver Status</th>
						<th style="color: #48484C;" width="28%">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($row = mysqli_fetch_assoc($result)) {
						?>
						<tr>
							<td><?php echo $row['formatted_id']; ?></td>
							<td><?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></td>
							<td><?php echo $row['driver_category']; ?></td>
							<td><?php echo $row['association_name'] . ' - ' . $row['association_area']; ?></td>
							<td class="center">
								<?php echo $row['verification_stat']; ?>
							</td>
							<td>
								<a href="#" class="btn btn-default btn-sm btn-icon icon-left reconsider-btn" data-id="<?php echo $row['formatted_id']; ?>" title="Reconsider">
									<i class="entypo-pencil"></i> Reconsider
								</a>
								<a href="#" class="btn btn-danger btn-sm btn-icon icon-left delete-btn" data-id="<?php echo $row['formatted_id']; ?>" title="Delete">
									<i class="entypo-cancel"></i> Delete
								</a>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th style="color: #48484C;">Driver ID</th>
						<th style="color: #48484C;">Driver Name</th>
						<th style="color: #48484C;">Vehicle Type</th>
						<th style="color: #48484C;">Association</th>
						<th style="color: #48484C;">Driver Status</th>
						<th style="color: #48484C;">Actions</th>
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
