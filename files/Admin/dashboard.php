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

    // Get the current date
    $currentDate = date('Y-m-d');

    // Query to count expected number of drivers to verify today
    $queryExpected = "SELECT COUNT(*) AS expected_count FROM tbl_appointment WHERE appointment_date = '$currentDate'";
    $resultExpected = mysqli_query($connections, $queryExpected);
    $rowExpected = mysqli_fetch_assoc($resultExpected);
    $expectedDrivers = $rowExpected['expected_count'];

    // Query to count drivers to verify remaining (pending verification status)
    $queryRemaining = "SELECT COUNT(*) AS remaining_count 
                       FROM tbl_driver d 
                       JOIN tbl_appointment a ON d.fk_sched_id = a.sched_id 
                       WHERE a.appointment_date = '$currentDate' AND d.verification_stat = 'pending'";
    $resultRemaining = mysqli_query($connections, $queryRemaining);
    $rowRemaining = mysqli_fetch_assoc($resultRemaining);
    $remainingDrivers = $rowRemaining['remaining_count'];
?>
				<style>
					.time {
						position: absolute;
						top: 12px;
						left: 505px;
						transition: .3s;
						cursor: cell;
						margin-left: 20px;
					}
					
				</style>
				<script src="https://cdn.lordicon.com/lordicon.js"></script>

				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<h1 title="Date and Time">
								<?php date_default_timezone_set('Asia/Manila'); echo date("F j, Y"); ?>
								<span id="time"></span>
								<lord-icon
									src="https://cdn.lordicon.com/lzgqzxrq.json"
									trigger="hover"
									colors="primary:#3a3347,secondary:#ebe6ef,tertiary:#4bb3fd,quaternary:#000000"
									style="width:70px;height:70px"
									class="time"
									title="Clock">
								</lord-icon> 
							</h1>
							<h2 title="Welcome to the Driver's ID System">Welcome to the Driver's ID Management System <strong title="Admin">Admin <?php echo $first_name; ?></strong></h2>

							<h3 style="color: gray;" title="Drivers to verify">
								<!-- Expected Number of Drivers to Verify Today: <span style="color: blue;"><?php echo $expectedDrivers; ?></span><br> -->
								Drivers to Verify Today Remaining: <a href="verify.php?filter=today" style="color: #4D87D0; text-decoration: none;"><?php echo $remainingDrivers; ?></a>
							</h3>
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

							<!-- Total Registered Drivers -->
							<div class="col-sm-3" title="Total Registered Drivers">
								<a href="driver_data.php" style="text-decoration: none;">
									<div class="tile-stats tile-aqua">
										<div class="icon"><i class="entypo-users"></i></div>
										<div class="num"><?php echo $totalRegistered; ?></div>
										<h3>Total Registered Drivers</h3>
										<p>Total</p>
									</div>
								</a>
							</div>

							<!-- Registered E-Bike Drivers -->
							<div class="col-sm-3" title="Total Registered E-Bike Drivers">
								<a href="driver_data.php?driver_category=E-Bike" style="text-decoration: none;">
									<div class="tile-stats tile-red">
										<div class="icon"><i class="entypo-users"></i></div>
										<div class="num"><?php echo $totalEbike; ?></div>
										<h3>Registered E-Bike Drivers</h3>
										<p>E-Bike</p>
									</div>
								</a>
							</div>

							<!-- Registered Tricycle Drivers -->
							<div class="col-sm-3" title="Total Registered Tricycle Drivers">
								<a href="driver_data.php?driver_category=Tricycle" style="text-decoration: none;">
									<div class="tile-stats tile-green">
										<div class="icon"><i class="entypo-users"></i></div>
										<div class="num"><?php echo $totalTricycle; ?></div>
										<h3>Registered Tricycle Drivers</h3>
										<p>Tricycle</p>
									</div>
								</a>
							</div>

							<!-- Registered Trisikad Drivers -->
							<div class="col-sm-3" title="Total Registered Trisikad Drivers">
								<a href="driver_data.php?driver_category=Trisikad" style="text-decoration: none;">
									<div class="tile-stats tile-orange">
										<div class="icon"><i class="entypo-users"></i></div>
										<div class="num"><?php echo $totalTrisikad; ?></div>
										<h3>Registered Trisikad Drivers</h3>
										<p>Trisikad</p>
									</div>
								</a>
							</div>

						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default" title="Current Registered Drivers">
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

				<div class="row" title="Violations Committed">
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

											// Make the bar clickable and redirect to violation_data.php
											echo "<div class='bar'>
													<div class='bar-label'>$category</div>
													<a href='violation_data.php?violation_category=" . urlencode($category) . "' class='bar-fill' style='background-color: $color; width: {$barWidth}%;'>
														<div class='bar-number'>$count</div>
														<div class='bar-percentage'>" . number_format($percentage, 2) . "%</div>
													</a>
												</div>";
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>



				<div class="row" title="Associations Statistics">
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
									<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
									<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
									<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
								</div>
							</div>
							<div class="panel-body no-padding">
								<div id="association-stats">
									<style>
										.association {
											display: flex;
											flex-direction: column;
											justify-content: space-between; /* Ensures content is evenly distributed */
											min-height: 100px; /* Minimum height to ensure uniformity */
											background-color: inherit;
											border-radius: 12px;
											padding: 10px;
											margin: 5px;
										}

										.association h5,
										.association p {
											color: white;
											text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
											margin: 0;
										}

										.association .association-details {
											flex-grow: 1; /* Allows content to grow and fill available space */
										}
									</style>

									<?php
									// Include database connection
									include "../../connections.php";

									// Updated query with ORDER BY association_name
									$query = "SELECT a.association_id, a.association_category, a.association_name, a.association_area, 
													a.association_color, 
													COUNT(d.driver_id) AS num_drivers,
													/* Subquery for total violations among registered and active drivers */
													(SELECT COUNT(v.violation_id) 
													FROM tbl_violation v 
													INNER JOIN tbl_driver d ON v.fk_driver_id = d.driver_id 
													WHERE d.fk_association_id = a.association_id 
													AND d.verification_stat = 'Registered' 
													AND d.renew_stat = 'Active') AS total_violations,
													/* Subquery for current (unrenewed) violations among registered and active drivers */
													(SELECT COUNT(v.violation_id) 
													FROM tbl_violation v 
													INNER JOIN tbl_driver d ON v.fk_driver_id = d.driver_id 
													WHERE d.fk_association_id = a.association_id 
													AND d.verification_stat = 'Registered' 
													AND d.renew_stat = 'Active'
													AND (v.renewed_date IS NULL OR v.renewed_date = '')) AS current_violations
											FROM tbl_association a
											LEFT JOIN tbl_driver d ON a.association_id = d.fk_association_id
											AND d.verification_stat = 'Registered'
											AND d.renew_stat = 'Active'
											GROUP BY a.association_id
											ORDER BY a.association_name"; // Added ORDER BY clause


									// Execute the query
									$result = mysqli_query($connections, $query);

									// Check for SQL errors
									if (!$result) {
										echo "Error: " . mysqli_error($connections);
									} elseif (mysqli_num_rows($result) > 0) {
										// Display associations if any results were returned
										$counter = 0; // Counter to track rows
										echo '<div class="row">'; // Start a new row for associations
										while ($row = mysqli_fetch_assoc($result)) {
											$association_color = htmlspecialchars($row['association_color']);
											?>
											<div class="col-sm-4"> <!-- 3 items per row, each taking up 4 columns -->
												<a href="driver_data.php?association_name=<?php echo urlencode($row['association_name']); ?>" style="text-decoration: none;">
													<div class="association" style="background-color: <?php echo $association_color; ?>;">
														<h5 style="margin-top: 0;"><?php echo htmlspecialchars($row['association_name']); ?></h5>
														<p>Association Category: <strong><?php echo htmlspecialchars($row['association_category']); ?></strong></p>
														<p>Association Area: <strong><?php echo htmlspecialchars($row['association_area']); ?></strong></p>
														<p>Number of Drivers: <strong><?php echo htmlspecialchars($row['num_drivers']); ?></strong></p>
														<div class="association-details">
															<!--<p>
																<a href="violation.php?association_name=<?php /*echo urlencode($row['association_name']); ?>&view=total" style="color: white; text-decoration: underline;">
																	Total Violations: <strong><?php echo htmlspecialchars($row['total_violations']); */?></strong>
																</a>
															</p>-->
															<p>
																<a href="violation.php?association_name=<?php echo urlencode($row['association_name']); ?>&view=current" style="color: white; text-decoration: underline;">
																	Current Violations (not renewed): <strong><?php echo htmlspecialchars($row['current_violations']); ?></strong>
																</a>
															</p>
														</div>
													</div>
												</a>
											</div>
											<?php
											$counter++; 
											if ($counter % 3 == 0) {
												echo '</div><div class="row">'; // Create a new row every 3 items
											}
										}
										echo '</div>'; // End the final row
									} else {
										// No associations found
										echo "<p>No associations found.</p>";
									}

									// Close database connection
									mysqli_close($connections);
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
