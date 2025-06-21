<?php

session_start();

include("../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $admin_id = $fetch["admin_id"]; // Assuming admin_id is the primary key of tbl_admin

    $account_type = $fetch["account_type"];

    if ($account_type != 1 && $account_type != 2) {
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
    <link rel="icon" href="../../img/trash-icon.png" type="image/png">
    <title>Denial Success</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            outline: none;
        }

        *::selection {
            background-color: var(--comfort-red);
            color: white;
            text-shadow: 1px 1px 4px var(--comfort-black);
        }

        :root {
            --comfort-green: #3bc356;
            --comfort-black: rgb(50, 45, 45);
            --comfort-blue: rgb(64, 138, 242);
            --comfort-red: rgba(247, 67, 67, 0.934);
            --comfort-shadow: rgb(183, 182, 182);
        }

        body {
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: var(--comfort-black);
            font-size: 2.3rem;
            text-shadow: 1px 1px 6px var(--comfort-shadow);
        }

        .container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .message {
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .link {
            color: #007bff;
            text-decoration: none;
            border: 1.5px solid var(--comfort-blue);
            padding-top: .2rem;
            padding-left: .5rem;
            padding-right: .5rem;
            padding-bottom: .2rem;
            font-size: 1.3rem;
            border-radius: 4px;
            transition: .23s;
        }

        .link:hover {
            font-size: 1.5rem;
            background-color: var(--comfort-blue);
            color: white;
            text-shadow: 1px 1px 3px var(--comfort-black);
            box-shadow: 1px 1px 4px var(--comfort-shadow);
        }

        .link:active {
            display: inline-flex;
            transform: scale(.9);
        }
    </style>
</head>

<body>
    <center>
        <h1>Denial of Driver</h1>
        <div class="container">
            <?php
            // Check if a driver ID is provided
            if (isset($_GET['id'])) {
                $formatted_id = $_GET['id'];

                // Update the verification_stat, renew_stat, driver_registered, and vehicle_registered for the driver
                $update_driver_query = "UPDATE tbl_driver SET verification_stat = 'Denied', renew_stat = NULL, driver_registered = NULL, fk_admin_id = '$admin_id' WHERE formatted_id = '$formatted_id'";
                $update_driver_result = mysqli_query($connections, $update_driver_query);

                if ($update_driver_result) {
                    // Retrieve the driver_id from tbl_driver using formatted_id
                    $driver_query = "SELECT driver_id FROM tbl_driver WHERE formatted_id = '$formatted_id'";
                    $driver_result = mysqli_query($connections, $driver_query);

                    if ($driver_result && mysqli_num_rows($driver_result) > 0) {
                        $driver_row = mysqli_fetch_assoc($driver_result);
                        $driver_id = $driver_row['driver_id'];

                        // Update vehicle_registered in tbl_vehicle to NULL using fk_driver_id
                        $update_vehicle_query = "UPDATE tbl_vehicle SET vehicle_registered = NULL WHERE fk_driver_id = '$driver_id'";
                        $update_vehicle_result = mysqli_query($connections, $update_vehicle_query);

                        if ($update_vehicle_result) {
                            // Log the action
                            $action_details = "Denied verification for Driver ID: $formatted_id";
                            $action_date = date('Y-m-d H:i:s'); // Current date and time
                            $log_query = "INSERT INTO tbl_log (fk_admin_id, action_details, action_date, fk_driver_id) VALUES ('$admin_id', '$action_details', '$action_date', '$driver_id')";
                            mysqli_query($connections, $log_query); // Ignore result of log query

                            echo "<p class='message'>Verification '<span style='color: rgba(247, 67, 67, 0.934); font-weight: bolder;'>Denied</span>' for Driver ID: <span style='font-weight: bolder;'> $formatted_id </span></p>";
                            echo '<p><a href="verify.php" class="link">Back to Verification</a></p>';
                        } else {
                            echo "<p class='message'>Verification status updated, but error updating vehicle registration: " . mysqli_error($connections) . "</p>";
                        }
                    } else {
                        echo "<p class='message'>No driver found for Formatted ID: $formatted_id.</p>";
                    }
                } else {
                    // If update query fails, display an error message
                    echo "<p class='message'>Error updating verification status: " . mysqli_error($connections) . "</p>";
                    echo '<p><a href="verify.php" class="link">Back to Verification</a></p>';
                }
            } else {
                // If no driver ID is provided, display an error message
                echo "<p class='message'>Driver ID not provided.</p>";
                echo '<p><a href="verify.php" class="link">Back to Verification</a></p>';
            }

            // Close the database connection
            mysqli_close($connections);
            ?>
        </div>
    </center>

    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <center><lord-icon
            src="https://cdn.lordicon.com/hjbrplwk.json"
            trigger="hover"
            colors="primary:#646e78,secondary:#e83a30,tertiary:#ffffff,quaternary:#000000"
            style="width:250px;height:250px">
        </lord-icon></center>
</body>

</html>
