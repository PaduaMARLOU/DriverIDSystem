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

        <h3 style="color: orange;">Driver Table Summary</h3>

        <hr>

        <?php
        include "../../connections.php";

        $association_name = isset($_GET['association_name']) ? mysqli_real_escape_string($connections, $_GET['association_name']) : '';
        $driver_category = isset($_GET['driver_category']) ? mysqli_real_escape_string($connections, $_GET['driver_category']) : '';

        $query = "SELECT d.formatted_id, d.first_name, d.middle_name, d.last_name, d.address, d.mobile_number, a.association_name, a.association_area, d.verification_stat, d.renew_stat, d.driver_registered
                FROM tbl_driver d
                LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
                WHERE d.verification_stat = 'Registered'";

        if (!empty($association_name)) {
            $query .= " AND a.association_name = '$association_name'";
        }

        if (!empty($driver_category)) {
            $query .= " AND d.driver_category = '$driver_category'";
        }

        $result = mysqli_query($connections, $query);

        if ($result) {
            $totalEntries = mysqli_num_rows($result);
            mysqli_data_seek($result, 0);
            ?>
            <h3>Export Driver Data</h3>
            <br />

            <div>
                <button id="toggleVerifiedOnBtn" class="btn btn-primary">Show Verified On</button>
                <label for="calendarFilter">Filter by Verified On (Year-Month):</label>
                <input type="month" id="calendarFilter" class="form-control-inline" />
                <label for="showEntriesInput">Show entries:</label>
                <input type="number" id="showEntriesInput" value="10" min="1" style="width: 60px;" />
                <span id="totalEntries"></span>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    let $table4 = jQuery("#table-4");

                    let dataTable = $table4.DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            { extend: 'copyHtml5', exportOptions: { modifier: { page: 'current' } } },
                            { extend: 'excelHtml5', exportOptions: { modifier: { page: 'current' } } },
                            { extend: 'csvHtml5', exportOptions: { modifier: { page: 'current' } } },
                            { extend: 'pdfHtml5', exportOptions: { modifier: { page: 'current' } } }
                        ],
                        "pageLength": 10,
                        "lengthChange": false,
                        columnDefs: [
                            { targets: [6], visible: false } // Initially hide the Verified On column
                        ]
                    });

                    $('#toggleVerifiedOnBtn').on('click', function () {
                        let col = dataTable.column(6); // Verified On column index
                        col.visible(!col.visible()); // Toggle visibility
                    });

                    $('#showEntriesInput').on('change', function () {
                        let entries = $(this).val();
                        dataTable.page.len(entries).draw();
                    });

                    $('#calendarFilter').on('change', function () {
                        let filterDate = $(this).val();
                        if (filterDate) {
                            // Extract year and month from the selected value
                            let [year, month] = filterDate.split('-');
                            let searchValue = `${year}-${month}`;

                            // Filter by the Verified On column
                            dataTable.column(6).search(searchValue).draw();
                        } else {
                            dataTable.column(6).search('').draw(); // Clear filter if no month is selected
                        }
                    });

                    $('#totalEntries').text(`out of ${<?php echo $totalEntries; ?>} entries`);
                });
            </script>

            <table class="table table-bordered datatable" id="table-4">
                <thead>
                    <tr>
                        <th>Driver ID</th>
                        <th>Driver Name</th>
                        <th>Address</th>
                        <th>Mobile Number</th>
                        <th>Association</th>
                        <th>Driver Status</th>
                        <th>Verified On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['formatted_id']; ?></td>
                            <td><?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['mobile_number']; ?></td>
                            <td><?php echo $row['association_name'] . ' - ' . $row['association_area']; ?></td>
                            <td><?php echo $row['verification_stat'] . ' (' . $row['renew_stat'] . ')'; ?></td>
                            <td><?php echo $row['driver_registered']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Driver ID</th>
                        <th>Driver Name</th>
                        <th>Address</th>
                        <th>Mobile Number</th>
                        <th>Association</th>
                        <th>Driver Status</th>
                        <th>Verified On</th>
                    </tr>
                </tfoot>
            </table>
        <?php
        } else {
            echo "Error: " . mysqli_error($connections);
        }

        mysqli_close($connections);
        ?>

        <br>

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