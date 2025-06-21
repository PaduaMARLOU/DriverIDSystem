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

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

    <?php include "sidebar.php"; ?>

    <div class="main-content">
        <?php include "header.php"; ?>

        <hr />
        <h3 style="color: orange;">Driver Comment Summary</h3>
        <hr>

        <?php
        include "../../connections.php"; // Include your database connection

        // Build the SQL query to include only comments
        $query = "
            SELECT d.driver_id, d.formatted_id, d.first_name, d.middle_name, d.last_name, 
                a.association_name, a.association_area, 
                c.comment_date, c.comment_details
            FROM tbl_driver d
            INNER JOIN tbl_association a ON d.fk_association_id = a.association_id
            INNER JOIN tbl_comment c ON d.driver_id = c.fk_driver_id
            WHERE d.verification_stat = 'Registered'";

        // Add order clause
        $query .= " ORDER BY c.comment_date ASC";

        $result = mysqli_query($connections, $query);

        // Check if query was successful
        if ($result) {
            ?>
            <h3>Export Comment Data</h3>
            <br />

            <div>
                <label for="showEntriesInput">Show entries: </label>
                <input type="number" id="showEntriesInput" value="10" min="1" style="width: 60px;" />
                <span id="totalEntries"></span> <!-- Placeholder for total entries -->
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    let $table4 = jQuery("#table-4");

                    let dataTable = $table4.DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'copyHtml5',
                                text: 'Copy',
                                exportOptions: {
                                    rows: ':visible' // Allow exporting only visible rows
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                exportOptions: {
                                    rows: ':visible' // Allow exporting only visible rows
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                text: 'CSV',
                                exportOptions: {
                                    rows: ':visible' // Allow exporting only visible rows
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                exportOptions: {
                                    rows: ':visible' // Allow exporting only visible rows
                                }
                            }
                        ],
                        "pageLength": 10, // Default page length
                        "lengthChange": false, // Disable length change dropdown
                    });

                    // Function to update DataTable page length based on input
                    $('#showEntriesInput').on('change', function() {
                        let entries = $(this).val();
                        dataTable.page.len(entries).draw(); // Update page length and redraw
                    });

                    // Set total entries text
                    $('#totalEntries').text(`out of <?php echo mysqli_num_rows($result); ?> entries`); // Update total entries text
                });
            </script>

            <table class="table table-bordered datatable" id="table-4">
                <thead>
                    <tr>
                        <th style="color: #48484C;">Driver ID</th>
                        <th style="color: #48484C;">Driver Name</th>
                        <th style="color: #48484C;">Association</th>
                        <th style="color: #48484C;">Comment Date</th>
                        <th style="color: #48484C;">Comment Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Format the comment date to AM/PM format
                        $formatted_comment_date = date("Y-m-d", strtotime($row['comment_date']));
                        $formatted_comment_time = date("h:i A", strtotime($row['comment_date']));
                        ?>
                        <tr>
                            <td><?php echo $row['formatted_id']; ?></td>
                            <td><?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></td>
                            <td><?php echo $row['association_name'] . ' - ' . $row['association_area']; ?></td>
                            <td><?php echo $formatted_comment_date; ?><br><?php echo $formatted_comment_time; ?></td>
                            <td><?php echo $row['comment_details']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="color: #48484C;">Driver ID</th>
                        <th style="color: #48484C;">Driver Name</th>
                        <th style="color: #48484C;">Association</th>
                        <th style="color: #48484C;">Comment Date</th>
                        <th style="color: #48484C;">Comment Details</th>
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
        <!-- Footer -->
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


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>