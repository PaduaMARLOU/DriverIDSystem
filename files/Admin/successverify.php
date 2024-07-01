<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("../../connections.php");

    if(isset($_SESSION["email"])) {
        $email = $_SESSION["email"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email='$email'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];

        if($account_type != 1){
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
        // Assuming you have already established a database connection
        include "../../connections.php";

        // Check if a driver ID is provided
        if (isset($_GET['id'])) {
            $driver_id = $_GET['id'];

            // Get the current date
            date_default_timezone_set('Asia/Manila');
            $current_date = date('Y-m-d');

            // Update the verification_stat and renew_stat for the driver
            $update_query = "UPDATE tbl_driver SET verification_stat = 'Registered', renew_stat = 'Active', driver_registered = '$current_date' WHERE formatted_id = '$driver_id'";
            $update_result = mysqli_query($connections, $update_query);

            if ($update_result) {
                echo "<p class='message'>Verification status updated successfully for Driver ID: $driver_id.</p>";
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
