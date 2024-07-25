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
					
		
		<h3 style="color: orange;">Driver Table Summary</h3>

		<hr>
		
		
		<?php
		// Assuming you have already established a database connection
		include "../../connections.php";

		// Fetch data from the database including association details
		$query = "SELECT d.formatted_id, d.first_name, d.middle_name, d.last_name, d.driver_category, a.association_name, a.association_area, d.verification_stat, d.renew_stat 
				FROM tbl_driver d
				LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
				WHERE d.verification_stat = 'Registered'";
		$result = mysqli_query($connections, $query);

		// Check if query was successful
		if ($result) {
			?>
			<h3>Export Driver Data</h3>
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
						<th>Driver Status</th>
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
							<td><?php echo $row['association_name'] . ' - ' . $row['association_area']; ?></td>
							<td class="center"><?php echo $row['verification_stat'] . ' (' . $row['renew_stat'] . ')'; ?></td>
							<td>
								<a href="edit_driver.php?formatted_id=<?php echo $row['formatted_id']; ?>" class="btn btn-default btn-sm btn-icon icon-left edit-btn">
									<i class="entypo-pencil"></i>
									Edit
								</a>
								<a href="delete_driver.php?formatted_id=<?php echo $row['formatted_id']; ?>" class="btn btn-danger btn-sm btn-icon icon-left delete-btn">
									<i class="entypo-cancel"></i>
									Delete
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
						<th>Driver Status</th>
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



		<br>
        <!-- Add the "View Full Driver Information" button 
        <a href="Drivers/drivertable.php" style="background-color: green; color: white; text-decoration: none; padding: 10px;">View Full Driver Information</a>
		-->
		

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