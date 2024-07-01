<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />

	<link rel="icon" href="assets/images/favicon.ico">

	<title>Neon | Data Tables</title>

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
	    // Assuming you have already established a database connection
	    include "../../connections.php";

	    // Fetch data from the database where renew_stat is 'To Renewal' or blank
	    $query = "SELECT formatted_id, first_name, middle_name, last_name, driver_category, association, renew_stat FROM tbl_driver WHERE renew_stat IN ('For Renewal', 'Revoked due to Violations')";
	    $result = mysqli_query($connections, $query);

	    // Check if query was successful
	    if ($result) {
	        ?>
	        <h3>Renew Driver Data</h3>
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


</body>
</html>