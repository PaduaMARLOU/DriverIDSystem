<?php

	if (session_status() === PHP_SESSION_NONE) {
	    session_start();
	}

	include("../../connections.php");

	if(isset($_SESSION["username"])) {
	    $username = $_SESSION["username"];

	    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
	    $fetch = mysqli_fetch_assoc($authentication);
	    $account_type = $fetch["account_type"];
	    $first_name = $fetch["first_name"];

	    if($account_type != 1 && $account_type != 2){
	        header("Location: ../../Forbidden.php");
	        exit; // Ensure script stops executing after redirection
	    }
	} else {
	    header("Location: ../../Forbidden.php");
	    exit; // Ensure script stops executing after redirection
	}

    // Fetch total registered drivers
    $totalRegisteredQuery = "SELECT COUNT(*) AS total_registered FROM tbl_driver WHERE verification_stat = 'Registered'";
    $totalRegisteredResult = mysqli_query($connections, $totalRegisteredQuery);
    $totalRegistered = mysqli_fetch_assoc($totalRegisteredResult)['total_registered'];

    // Fetch totals for each driver category
    $ebikeQuery = "SELECT COUNT(*) AS total_ebike FROM tbl_driver WHERE driver_category = 'E-Bike' AND verification_stat = 'Registered'";
    $ebikeResult = mysqli_query($connections, $ebikeQuery);
    $totalEbike = mysqli_fetch_assoc($ebikeResult)['total_ebike'];

    $tricycleQuery = "SELECT COUNT(*) AS total_tricycle FROM tbl_driver WHERE driver_category = 'Tricycle' AND verification_stat = 'Registered'";
    $tricycleResult = mysqli_query($connections, $tricycleQuery);
    $totalTricycle = mysqli_fetch_assoc($tricycleResult)['total_tricycle'];

    $trisikadQuery = "SELECT COUNT(*) AS total_trisikad FROM tbl_driver WHERE driver_category = 'Trisikad' AND verification_stat = 'Registered'";
    $trisikadResult = mysqli_query($connections, $trisikadQuery);
    $totalTrisikad = mysqli_fetch_assoc($trisikadResult)['total_trisikad'];

    // Fetch total violations for each category
    $violationQuery = "SELECT violation_category, COUNT(*) AS total_violations FROM tbl_violation GROUP BY violation_category";
    $violationResult = mysqli_query($connections, $violationQuery);

    $totalViolations = [];
    while ($row = mysqli_fetch_assoc($violationResult)) {
        $totalViolations[$row['violation_category']] = $row['total_violations'];
    }
?>
				<style>
					.time {
						position: absolute;
						top: 12px;
						left: 555px;
						transition: .3s;
						cursor: cell;
					}
				</style>
				<script src="https://cdn.lordicon.com/lordicon.js"></script>

				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<h1>
								<?php date_default_timezone_set('Asia/Manila'); echo date("F j, Y"); ?>
								<span id="time"></span>
								<lord-icon
									src="https://cdn.lordicon.com/lzgqzxrq.json"
									trigger="hover"
									colors="primary:#3a3347,secondary:#ebe6ef,tertiary:#4bb3fd,quaternary:#000000"
									style="width:70px;height:70px"
									class="time"
									title="Time Navigation">
								</lord-icon>
							</h1>
							<h3>Welcome to the Driver's ID Management System <strong>Admin <?php echo $first_name; ?></strong></h3>
						</div>
					</div>
				</div>

				<script>
					function updateTime() {
						const options = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit' };
						const now = new Intl.DateTimeFormat('en-US', options).format(new Date());
						document.getElementById('time').textContent = `(${now})`;
					}

					setInterval(updateTime, 1000);
					updateTime(); // Initial call to display the time immediately
				</script>


				<div class="row">

				    <div class="col-sm-12">

				        <div class="row">

				            <div class="col-sm-3">
				                <div class="tile-stats tile-aqua">
				                    <div class="icon"><i class="entypo-users"></i></div>
				                    <div class="num"><?php echo $totalRegistered; ?></div>
				                    <h3>Total Registered Drivers</h3>
				                    <p>Total</p>
				                </div>
				            </div>

				            <div class="col-sm-3">
				                <div class="tile-stats tile-red">
				                    <div class="icon"><i class="entypo-users"></i></div>
				                    <div class="num"><?php echo $totalEbike; ?></div>
				                    <h3>Registered E-Bike Drivers</h3>
				                    <p>E-Bike</p>
				                </div>
				            </div>

				            <div class="col-sm-3">
				                <div class="tile-stats tile-green">
				                    <div class="icon"><i class="entypo-users"></i></div>
				                    <div class="num"><?php echo $totalTricycle; ?></div>
				                    <h3>Registered Tricycle Drivers</h3>
				                    <p>Tricycle</p>
				                </div>
				            </div>

				            <div class="col-sm-3">
				                <div class="tile-stats tile-orange">
				                    <div class="icon"><i class="entypo-users"></i></div>
				                    <div class="num"><?php echo $totalTrisikad; ?></div>
				                    <h3>Registered Trisikad Drivers</h3>
				                    <p>Trisikad</p>
				                </div>
				            </div>

				        </div>
				    </div>
				</div>


				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="panel-title">Latest Registered Drivers</div>
								<div class="panel-options">
									<!-- <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> -->
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
									<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
									<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
								</div>
							</div>
							<table class="table table-bordered table-responsive">
								<thead>
									<tr>
										<th>ID Number</th>
										<th>Name</th>
										<th>Vehicle Type</th>
										<th>Association</th>
										<th>Verified by</th>
										<th>Verified On</th> <!-- New column for Verified On -->
									</tr>
								</thead>
								<tbody>
									<?php
									// Assuming you have already established a database connection
									include "../../connections.php";

									// Fetch data from the database and order by driver_registered in descending order
									$query = "SELECT d.driver_id, d.driver_category, d.formatted_id, d.first_name, d.middle_name, d.last_name, 
													d.suffix_name, d.nickname, a.association_name, a.association_area, 
													ad.first_name AS verified_by, d.driver_registered
											FROM tbl_driver d
											LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
											LEFT JOIN tbl_admin ad ON d.fk_admin_id = ad.admin_id
											WHERE d.verification_stat = 'Registered' AND d.renew_stat = 'Active'
											ORDER BY d.driver_registered DESC, d.driver_id DESC
											LIMIT 30";

									$result = mysqli_query($connections, $query);

									// Check if query was successful
									if ($result) {
										while ($row = mysqli_fetch_assoc($result)) {
											?>
											<tr>
												<td><?php echo $row['formatted_id']; ?></td>
												<td><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?>
													<?php echo !empty($row['suffix_name']) ? ' ' . $row['suffix_name'] . '.' : ''; ?>
													<?php echo !empty($row['nickname']) ? ' "' . $row['nickname'] . '"' : ''; ?>
												</td>
												<td><?php echo $row['driver_category']; ?></td>
												<td><?php echo $row['association_name'] . ' - ' . $row['association_area']; ?></td>
												<td><?php echo $row['verified_by']; ?></td>
												<td><?php echo date('Y-m-d h:i A', strtotime($row['driver_registered'])); ?></td> <!-- Display verified date in 12-hour format -->
											</tr>
											<?php
										}
									} else {
										echo "<tr><td colspan='6'>No registered drivers found.</td></tr>";
									}

									// Close the database connection
									mysqli_close($connections);
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-13">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="panel-title">Violations</div>
								<div class="panel-options">
									<!-- <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> -->
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
									<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
									<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
								</div>
							</div>
							<div class="panel-body">
								<style>
									.bar-graph {
										display: flex;
										flex-direction: column; /* Stacked vertically */
										width: 100%;
										margin-top: 20px; /* Add some margin at the top */
									}

									.bar {
										display: flex;
										margin-bottom: 10px; /* Add some margin between bars */
									}

									.bar-label {
										width: 100px; /* Fixed width for the label */
										text-align: right; /* Align text to the right */
										margin-right: 10px; /* Add some margin between label and bar */
									}

									.bar-fill {
										box-shadow: 1px 2px 4px #98989B;
										border-radius: 3.5px;
										height: 30px; /* Height of the bar */
										background-color: #337ab7; /* Default color for the bar */
										position: relative; /* Position relative for number */
									}

									.bar-number {
										position: absolute; /* Position absolute for number */
										left: 5px; /* Adjust number position */
										color: white; /* Set number color */
									}

									.bar-percentage {
										position: absolute; /* Position absolute for percentage */
										right: 5px; /* Adjust percentage position */
										color: white; /* Set percentage color */
									}

									.no-violations {
										font-size: 18px;
										color: #333;
										margin-top: 20px;
									}
								</style>

								<div class="bar-graph">
									<?php
									// Check if there are any violations
									if (empty($totalViolations) || array_sum($totalViolations) == 0) {
										echo "<div class='no-violations'>No Violations Found</div>";
									} else {
										// Calculate the total number of violations
										$totalCount = array_sum($totalViolations);
										// Calculate the maximum count for scaling the bars
										$maxCount = max($totalViolations);

										// Generate bars for each violation category
										foreach ($totalViolations as $category => $count) {
											// Generate a unique color for each bar
											$color = "#" . substr(md5($category), 0, 6);
											// Calculate the width of the bar based on the count
											$barWidth = ($count / $maxCount) * 100;
											// Calculate the percentage of this category
											$percentage = ($count / $totalCount) * 100;

											echo "<div class='bar'>
													<div class='bar-label'>$category</div>
													<div class='bar-fill' style='background-color: $color; width: {$barWidth}%;'>
														<div class='bar-number'>$count</div>
														<div class='bar-percentage'>" . number_format($percentage, 2) . "%</div>
													</div>
												</div>";
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="panel-title">
								<h4>
									Association Statistics
									<br>
									<small>Number of Associations and Drivers per Association</small>
								</h4>
							</div>
							<div class="panel-options">
								<!-- <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> -->
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>
						<div class="panel-body no-padding">
							<div id="association-stats">
								<style>
									.association h5,
									.association p {
										color: white;
										
									}
								</style>


								<?php
								// Assuming you have a database connection established
								include "../../connections.php";
								// Retrieve data from tbl_driver with association details
								$query = "SELECT d.fk_association_id, a.association_category, a.association_name, a.association_area, 
										a.association_color, COUNT(*) as num_drivers
										FROM tbl_driver d
										INNER JOIN tbl_association a ON d.fk_association_id = a.association_id
										WHERE d.verification_stat = 'Registered' AND d.renew_stat = 'Active'
										GROUP BY d.fk_association_id";
								$result = mysqli_query($connections, $query); // assuming $connections is your database connection

								// Check if there are any associations
								if(mysqli_num_rows($result) > 0) {
									// Loop through the fetched data and generate HTML dynamically
									while ($row = mysqli_fetch_assoc($result)) {
										$association_color = htmlspecialchars($row['association_color']);
										?>
										<div class="association" style="display: inline-block; background-color: <?php echo $association_color; ?>; border-radius: 12px; padding: 10px; margin: 5px;">
											<h5 style="margin-top: 0;"><?php echo htmlspecialchars($row['association_name']); ?></h5>
											<p style="margin-bottom: 5px;">Association Category: <strong><?php echo htmlspecialchars($row['association_category']); ?></strong></p>
											<p style="margin-bottom: 5px;">Association Area: <strong><?php echo htmlspecialchars($row['association_area']); ?></strong></p>
											<p style="margin-bottom: 5px;">Number of Drivers: <strong><?php echo htmlspecialchars($row['num_drivers']); ?></strong></p>
										</div>
									<?php
									}
								} else {
									// If no associations found
									echo "<p>No associations found.</p>";
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

		<!-- Di lang pagkaksa kay ga black tanan ang ari sa dalom hahahaha -->
			<div class="row" style="display: none;">
				<div class="col-sm-13">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="panel-title">
								<h4>
									Real Time Stats
									<br />
									<small>current server uptime</small>
								</h4>
							</div>

							<div class="panel-options">
								<!-- <a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a> -->
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>

						<div class="panel-body no-padding">
							<div id="rickshaw-chart-demo-2">
								<div id="rickshaw-legend"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
