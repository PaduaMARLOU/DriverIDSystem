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
    $admin_id = $fetch["admin_id"]; // Assuming admin_id is the primary key of tbl_admin

    if($account_type != 1){
        header("Location: ../../Forbidden3.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden3.php");
    exit; // Ensure script stops executing after redirection
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew Driver</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .message {
            margin-bottom: 20px;
        }
        .link {
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
<?php

include "../../connections.php";

// Check if a driver ID is provided
if (isset($_GET['id'])) {
    $driver_id = $_GET['id'];

    // Fetch the current renew_stat of the driver
    $query = "SELECT renew_stat, driver_id FROM tbl_driver WHERE formatted_id = '$driver_id'";
    $result = mysqli_query($connections, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $renew_stat = $row['renew_stat'];
        $driver_id_internal = $row['driver_id']; // internal driver ID

        // Set timezone to Asia/Manila and get the current date and time
        $timezone = new DateTimeZone('Asia/Manila');
        $current_date = new DateTime('now', $timezone);
        $current_date_formatted = $current_date->format('Y-m-d H:i:s');

        if ($renew_stat === 'Revoked due to Violations') {
            // Update renew_stat to 'Active'
            $update_query = "UPDATE tbl_driver SET renew_stat = 'Active' WHERE formatted_id = '$driver_id'";
            $update_result = mysqli_query($connections, $update_query);
           
            if ($update_result) {
                // Update the renewed_date in tbl_violation for NULL entries
                $update_violation_query = "
                    UPDATE tbl_violation 
                    SET renewed_date = '$current_date_formatted' 
                    WHERE fk_driver_id = '$driver_id_internal' 
                    AND renewed_date IS NULL
                ";
                $update_violation_result = mysqli_query($connections, $update_violation_query);

                if ($update_violation_result) {
                    echo "<p class='message'>Renewal status updated to 'Active' and violation dates updated for Driver ID: $driver_id.</p>";
                    
                    // Log the renewal action
                    $action_details = "Renewed Violations of Driver ID: $driver_id";
                    $log_query = "INSERT INTO tbl_log (fk_admin_id, fk_driver_id, action_details, action_date) VALUES ('$admin_id', '$driver_id_internal', '$action_details', '$current_date_formatted')";
                    mysqli_query($connections, $log_query); // Ignore errors for logging
                } else {
                    echo "<p class='message'>Error updating renewed date in tbl_violation: " . mysqli_error($connections) . "</p>";
                }
            } else {
                echo "<p class='message'>Error updating renewal status: " . mysqli_error($connections) . "</p>";
            }
        } elseif ($renew_stat === 'For Renewal') {
            // Update renew_stat to 'Active' and driver_registered to the current date
            $update_query = "
                UPDATE tbl_driver 
                SET renew_stat = 'Active', driver_registered = '$current_date_formatted' 
                WHERE formatted_id = '$driver_id'
            ";
            $update_result = mysqli_query($connections, $update_query);

            if ($update_result) {
                echo "<p class='message'>Renewal status updated to 'Active' and driver_registered date set to current for Driver ID: $driver_id.</p>";
                
                // Log the renewal action
                $action_details = "Renewed Driver ID: $driver_id";
                $log_query = "INSERT INTO tbl_log (fk_admin_id, fk_driver_id, action_details, action_date) VALUES ('$admin_id', '$driver_id_internal', '$action_details', '$current_date_formatted')";
                mysqli_query($connections, $log_query); // Ignore errors for logging
            } else {
                echo "<p class='message'>Error updating renewal status: " . mysqli_error($connections) . "</p>";
            }
        }

        echo '<p><a href="javascript:history.go(-1)" class="link">Go back to previous page</a></p>';
    } else {
        echo "<p class='message'>Driver ID not found or renew_stat missing.</p>";
        echo '<p><a href="javascript:history.go(-1)" class="link">Go back to previous page</a></p>';
    }
} else {
    echo "<p class='message'>Driver ID not provided.</p>";
    echo '<p><a href="javascript:history.go(-1)" class="link">Go back to previous page</a></p>';
}

// Close the database connection
mysqli_close($connections);
?>



</div>
</body>
</html>
