<?php
// Assuming you have established a connection to your MySQL database

if(isset($_GET['date'])){
    $date = $_GET['date'];
    // You might want to perform some validation or formatting on the date here
}

// Query to retrieve the latest appointment ID
include ("connections.php");
$query = "SELECT sched_id FROM tbl_appointment ORDER BY sched_id DESC LIMIT 1";
$result = mysqli_query($connections, $query);

if($result) {
    // Fetch the row as an associative array
    $row = mysqli_fetch_assoc($result);
    
    // Get the appointment ID
    $latestAppointmentID = $row['sched_id'];

    // Increment the appointment ID for the new appointment
    $appointmentID = $latestAppointmentID;
} else {
    // If there are no existing appointments, start with 1
    $appointmentID = 1;
}

// You can use $appointmentID for your new appointment
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Success</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="icon" href="img/success-icon.png" type="image/png">
</head>
<body>
    <style>
        <?php include("driverportalcss/register_success.css") ?>
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h2 class="text-center" style="color: green;">Appointment Success</h2>
                <h5 class="text-center" style="color: red;"><strong>Please don't forget to screenshot this.</strong></h5>
                    <br>
                    <hr>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p><strong>Appointment ID:</strong> <span style="font-size: 1.42em;"><?php echo $appointmentID; ?></span></p>
                        <p><strong>Your Appointment Date:</strong> <span style="color: #3bc356; font-size: 1.42em;"><?php echo $date; ?></span></p>
                        <p><i>Please prepare your proof of documents that will show it's you and kindly also bring your vehicle.</i></p>
                        <h6><p><span style="color: #808080; font-size: 1.4rem;">You are now prioritized to be catered within this day. The queue will still be first come first serve!</span></p></h6><br>
                        <center><a href="index.php">Back to Driver's Portal</a></center>
                    </div>
                </div>

                <script src="https://cdn.lordicon.com/lordicon.js"></script>
                    <center><lord-icon
                        src="https://cdn.lordicon.com/guqkthkk.json"
                        trigger="hover"
                        style="width:180px;height:180px">
                    </lord-icon></center>
            </div>
        </div>
    </div>
</body>
</html>
