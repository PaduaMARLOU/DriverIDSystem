<?php

session_start();

include("../../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if($account_type != 1){
        header("Location: ../../../Forbidden2.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../../Forbidden2.php");
    exit; // Ensure script stops executing after redirection
}

?>


<?php
include '../../../connections.php';
date_default_timezone_set('Asia/Manila');

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['calendar_date'])) {
    $calendarDateToDelete = $_GET['calendar_date'];
    $deleteStmt = $connections->prepare("DELETE FROM tbl_calendar WHERE calendar_date = ?");
    $deleteStmt->bind_param("s", $calendarDateToDelete);
    $deleteStmt->execute();
    $deleteStmt->close();
}

// Get the month and year from the request or use the current month and year by default
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$currentMonthYear = $currentYear . '-' . $currentMonth;

// Fetch calendar entries for the selected month and year, ordered by date
$stmt = $connections->prepare("SELECT calendar_date, calendar_description, slots, end_time, calendar_control FROM tbl_calendar WHERE DATE_FORMAT(calendar_date, '%Y-%m') = ? ORDER BY calendar_date");
$stmt->bind_param("s", $currentMonthYear);
$stmt->execute();
$calendarEntries = $stmt->get_result();

// Calculate months and years for dropdowns
$months = array(
    '01' => 'January', '02' => 'February', '03' => 'March',
    '04' => 'April', '05' => 'May', '06' => 'June',
    '07' => 'July', '08' => 'August', '09' => 'September',
    '10' => 'October', '11' => 'November', '12' => 'December'
);

$years = range(date('Y') - 10, date('Y') + 10); // Last 10 years and next 10 years
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar Entries</title>
    <link rel="icon" href="../../../img/Brgy Estefania Logo.png" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        html {
            scroll-behavior: smooth; /* Smooth scroll for all pages */
        }
    </style>
    <script>
        function openModal(date, description, slots, endTime, control) {
            document.getElementById('calendar_date').value = date || '';
            document.getElementById('calendar_description').value = description || '';
            document.getElementById('slots').value = slots || 30;
            document.getElementById('end_time').value = endTime || '15:00';
            document.getElementById('calendar_control').value = control || 'Enable';
            document.getElementById('action').value = date ? 'update' : 'create';
            $('#calendarModal').modal('show');
        }

        function confirmDelete(date) {
            if (confirm('Are you sure you want to delete this entry?')) {
                window.location.href = 'calendar_entries.php?action=delete&calendar_date=' + encodeURIComponent(date);
            }
        }

        function scrollToBottom() {
            window.scrollTo(0, document.body.scrollHeight);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Calendar Entries</h2>

        <!-- Add New Entry Button -->
        <button class="btn btn-primary" onclick="openModal()">Add New Entry</button>

        <hr>

        <!-- Month and Year Dropdowns -->
        <form method="GET" action="calendar_entries.php" onsubmit="scrollToBottom()">
            <div class="form-group">
                <label for="month">Month:</label>
                <select class="form-control" id="month" name="month">
                    <?php foreach ($months as $key => $month): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($currentMonth == $key) ? 'selected' : ''; ?>><?php echo $month; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <select class="form-control" id="year" name="year">
                    <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year; ?>" <?php echo ($currentYear == $year) ? 'selected' : ''; ?>><?php echo $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="goButton">Go</button>
        </form>

        <hr>
        <h3>Calendar Entries for <?php echo date('F Y', strtotime($currentMonthYear)); ?></h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Slots</th>
                    <th>End Time</th>
                    <th>Control</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $calendarEntries->fetch_assoc()): ?>
                    <tr id="date_<?php echo htmlspecialchars($row['calendar_date']); ?>">
                        <td><?php echo htmlspecialchars($row['calendar_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['calendar_description']); ?></td>
                        <td><?php echo htmlspecialchars($row['slots']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['calendar_control']); ?></td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="openModal('<?php echo htmlspecialchars($row['calendar_date']); ?>', '<?php echo htmlspecialchars($row['calendar_description']); ?>', '<?php echo htmlspecialchars($row['slots']); ?>', '<?php echo htmlspecialchars($row['end_time']); ?>', '<?php echo htmlspecialchars($row['calendar_control']); ?>')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo htmlspecialchars($row['calendar_date']); ?>')">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="calendarModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Calendar Control</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="calendar_control.php">
                        <input type="hidden" id="action" name="action" value="update">
                        <div class="form-group">
                            <label for="calendar_date">Date:</label>
                            <input type="date" class="form-control" id="calendar_date" name="calendar_date" required>
                        </div>
                        <div class="form-group">
                            <label for="calendar_description">Description:</label>
                            <input type="text" class="form-control" id="calendar_description" name="calendar_description">
                        </div>
                        <div class="form-group">
                            <label for="slots">Slots:</label>
                            <input type="number" class="form-control" id="slots" name="slots" placeholder="Default: 30" min="1">
                        </div>
                        <div class="form-group">
                            <label for="end_time">End Time:</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" placeholder="Default: 15:00" value="15:00">
                        </div>
                        <div class="form-group">
                            <label for="calendar_control">Control:</label>
                            <select class="form-control" id="calendar_control" name="calendar_control">
                                <option value="Enable">Enable</option>
                                <option value="Disable">Disable</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$connections->close();
?>
