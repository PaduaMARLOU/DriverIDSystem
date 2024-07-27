<?php

session_start();

include("../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $admin_id = $fetch["admin_id"]; // Assuming admin_id is the primary key of tbl_admin

    $account_type = $fetch["account_type"];

    if($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Success</title>
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
        // Check if a driver ID is provided
        if (isset($_GET['id'])) {
            $formatted_id = $_GET['id'];

            // Get the current date and time
            date_default_timezone_set('Asia/Manila');
            $current_datetime = date('Y-m-d H:i:s');

            // Update the verification_stat and renew_stat for the driver
            $update_driver_query = "UPDATE tbl_driver SET verification_stat = 'Registered', renew_stat = 'Active', driver_registered = '$current_datetime', fk_admin_id = '$admin_id' WHERE formatted_id = '$formatted_id'";
            $update_driver_result = mysqli_query($connections, $update_driver_query);

            if ($update_driver_result) {
                // Retrieve the driver_id from tbl_driver using formatted_id
                $driver_query = "SELECT driver_id FROM tbl_driver WHERE formatted_id = '$formatted_id'";
                $driver_result = mysqli_query($connections, $driver_query);

                if ($driver_result && mysqli_num_rows($driver_result) > 0) {
                    $driver_row = mysqli_fetch_assoc($driver_result);
                    $driver_id = $driver_row['driver_id'];

                    // Update vehicle_registered in tbl_vehicle with the same datetime using fk_driver_id
                    $update_vehicle_query = "UPDATE tbl_vehicle SET vehicle_registered = '$current_datetime' WHERE fk_driver_id = '$driver_id'";
                    $update_vehicle_result = mysqli_query($connections, $update_vehicle_query);

                    if ($update_vehicle_result) {
                        echo "<p class='message'>Verification status and vehicle registration updated successfully for Driver ID: $formatted_id.</p>";
                    } else {
                        echo "<p class='message'>Verification status updated, but error updating vehicle registration: " . mysqli_error($connections) . "</p>";
                    }
                } else {
                    echo "<p class='message'>No driver found for Formatted ID: $formatted_id.</p>";
                }

                echo '<p><a href="verify.php" class="link">Go back to verification</a></p>';
            } else {
                // If update query fails, display an error message
                echo "<p class='message'>Error updating verification status: " . mysqli_error($connections) . "</p>";
                echo '<p><a href="verify.php" class="link">Go back to verification</a></p>';
            }
        } else {
            // If no driver ID is provided, display an error message
            echo "<p class='message'>Driver ID not provided.</p>";
            echo '<p><a href="verify.php" class="link">Go back to verification</a></p>';
        }

        // Close the database connection
        mysqli_close($connections);
        ?>
    </div>
</body>
</html>
