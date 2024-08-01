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

// Initialize variables for form data
$calendar_date = '';
$calendar_description = '';
$slots = 30; // Default value
$end_time = '15:00'; // Default value
$calendar_control = 'Enable'; // Default value
$submittedDate = null;
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $calendar_date = $_POST['calendar_date'];
    $calendar_description = $_POST['calendar_description'];
    $slots = isset($_POST['slots']) && $_POST['slots'] >= 1 ? $_POST['slots'] : 30; // Default to 30 if not provided or less than 1
    $end_time = isset($_POST['end_time']) ? $_POST['end_time'] : '15:00'; // Default to 15:00 if not provided
    $calendar_control = $_POST['calendar_control'];

    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Delete record
        $stmt = $connections->prepare("DELETE FROM tbl_calendar WHERE calendar_date = ?");
        $stmt->bind_param("s", $calendar_date);
        if ($stmt->execute()) {
            $success = true;
            $message = "Calendar entry deleted successfully!";
        } else {
            $message = "Error deleting calendar entry: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Check if the date already exists in the database
        $stmt = $connections->prepare("SELECT * FROM tbl_calendar WHERE calendar_date = ?");
        $stmt->bind_param("s", $calendar_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $stmt = $connections->prepare("UPDATE tbl_calendar SET calendar_description = ?, slots = ?, end_time = ?, calendar_control = ? WHERE calendar_date = ?");
            $stmt->bind_param("sisss", $calendar_description, $slots, $end_time, $calendar_control, $calendar_date);
        } else {
            // Insert new record
            $stmt = $connections->prepare("INSERT INTO tbl_calendar (calendar_date, calendar_description, slots, end_time, calendar_control) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $calendar_date, $calendar_description, $slots, $end_time, $calendar_control);
        }

        if ($stmt->execute()) {
            $success = true;
            $message = "Calendar entry updated successfully!";
            $submittedDate = $calendar_date; // Set the submitted date
        } else {
            $message = "Error updating calendar entry: " . $stmt->error;
        }

        $stmt->close();
    }

    // Redirect to calendar_entries.php
    if ($success) {
        header("Location: calendar_entries.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar Control</title>
    <link rel="icon" href="../../../img/Brgy Estefania Logo.png" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        html {
            scroll-behavior: smooth; /* Smooth scroll for all pages */
        }
    </style>
    <script>
        window.onload = function() {
            // Scroll to the submitted date if it's set
            const submittedDate = '<?php echo htmlspecialchars($submittedDate); ?>';
            if (submittedDate) {
                document.querySelector('body').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Calendar Control</h2>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$connections->close();
?>
