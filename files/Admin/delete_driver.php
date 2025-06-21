<?php
session_start();

include("../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];
    $admin_id = $fetch["admin_id"]; // Assuming admin_id is the primary key of tbl_admin

    if ($account_type != 1) {
        header("Location: ../../Forbidden2.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden2.php");
    exit; // Ensure script stops executing after redirection
}

// Function to redirect to driver.php after 2 seconds
function redirectToDriver() {
    echo "<script>
            setTimeout(function() {
                window.location.href = 'driver.php';
            }, 2000);
          </script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../img/wrong.png" type="image/png">
    <title>Delete Driver</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            outline: none;
        }

        *::selection {
            background-color: #F36363;
            color: white;
        }

        h1 {
            color: #404346;
        }

        /* Styles for centering the message container */
        .center-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .message-container {
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .error-message {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }

        .button-container {
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: .2s;
        }

        button:hover {
            font-size: 15px;
        }

        button.confirm {
            background-color: #5bc0de;
            color: white;
        }

        button.confirm:active {
            background-color: #5CB6D0;
            transform: scale(.9);
        }

        button.cancel {
            background-color: #d9534f;
            color: white;
        }

        button.cancel:active {
            background-color: #CE5450;
            transform: scale(.9);
        }

        form {
            margin-top: 20px;
        }

        .warning-gif {
            border: 2px solid white;
            border-radius: 5px;
            box-shadow: 1px 1px 5px gray;
        }

        hr {
            border: 1px solid white;
            box-shadow: 1px 1px 5px gray;
        }
        
    </style>
</head>
<body>

<?php
// First confirmation step
if (isset($_GET['step']) && $_GET['step'] == 'confirm_deletion' && isset($_GET['formatted_id'])) {
    $formatted_id = mysqli_real_escape_string($connections, $_GET['formatted_id']);
    
    // Check if confirmation string is provided and matches
    if (isset($_POST['confirmation_string']) && $_POST['confirmation_string'] === 'CONFIRM DELETION') {
        // Proceed to the second confirmation step
        echo "<div class='center-container'>
                <div class='message-container error-message'>
                    <img src='../../img/warning.gif' alt='Warning' class='warning-gif'>
                    <p style='font-size: 25px;'>LAST REMINDER! <hr><br> Warning: Deleting this driver ($formatted_id) will also delete all related vehicle and violation records.</p><br>
                    <p><strong>Are you sure you <i>really</i> want to delete this driver record? Type 'YES' to confirm.<strong></p>
                    <form method='post' action='delete_driver.php?step=final_confirm&formatted_id=$formatted_id'>
                        <label for='final_confirmation'>Enter confirmation:</label>
                        <input type='text' id='final_confirmation' name='final_confirmation' required>
                        <div class='button-container'>
                            <button type='submit' class='confirm'>Confirm</button>
                            <button type='button' class='cancel' onclick='window.location.href=\"driver.php\"'>Cancel</button>
                        </div>
                    </form>
                </div>
              </div>";
    } else {
        // Incorrect confirmation string
        echo "<script>
                alert('Error: Incorrect confirmation string.');
                window.location.href = 'driver.php';
            </script>";
    }
} elseif (isset($_GET['step']) && $_GET['step'] == 'final_confirm' && isset($_GET['formatted_id'])) {
    $formatted_id = mysqli_real_escape_string($connections, $_GET['formatted_id']);
    
    // Check if final confirmation string is provided and matches
    if (isset($_POST['final_confirmation']) && $_POST['final_confirmation'] === 'YES') {
        // Log the action
        $driver_id_result = mysqli_query($connections, "SELECT driver_id FROM tbl_driver WHERE formatted_id = '$formatted_id'");
        $driver_id_row = mysqli_fetch_assoc($driver_id_result);
        $driver_id = $driver_id_row['driver_id'];
        
        // Disable foreign key checks temporarily to bypass the constraint
        mysqli_query($connections, "SET FOREIGN_KEY_CHECKS = 0");

        // Prepare DELETE queries for related records
        $delete_violations_query = "DELETE FROM tbl_violation WHERE fk_driver_id = (SELECT driver_id FROM tbl_driver WHERE formatted_id = '$formatted_id')";
        $delete_vehicles_query = "DELETE FROM tbl_vehicle WHERE fk_driver_id = (SELECT driver_id FROM tbl_driver WHERE formatted_id = '$formatted_id')";
        $delete_driver_query = "DELETE FROM tbl_driver WHERE formatted_id = '$formatted_id'";

        // Execute DELETE queries
        $delete_violations_result = mysqli_query($connections, $delete_violations_query);
        $delete_vehicles_result = mysqli_query($connections, $delete_vehicles_query);
        $delete_driver_result = mysqli_query($connections, $delete_driver_query);

        // Enable foreign key checks again
        mysqli_query($connections, "SET FOREIGN_KEY_CHECKS = 1");

        // After successful deletion
if ($delete_driver_result) {

    // Prepare log details
    $action_details = "Deleted driver and related records for Driver ID: $formatted_id";
    date_default_timezone_set('Asia/Manila');
    $action_date = date('Y-m-d H:i:s'); // Current date and time in Asia/Manila timezone

    // Disable foreign key checks to allow log insertion
    mysqli_query($connections, "SET FOREIGN_KEY_CHECKS = 0");

    // Insert log entry
    $log_query = "INSERT INTO tbl_log (fk_admin_id, action_details, action_date, fk_driver_id) 
                  VALUES ('$admin_id', '$action_details', '$action_date', '$driver_id')";
    if (!mysqli_query($connections, $log_query)) {
        // Debugging information in case of error
        echo "<div class='center-container'>
                <div class='message-container error-message'>
                    <p>Log insertion failed: " . mysqli_error($connections) . "</p>
                    <p>Log Query: $log_query</p>
                </div>
              </div>";
    } else {
        // Log inserted successfully
        echo "<div class='center-container'>
                <div class='message-container success-message'>
                    <p>Driver record and all related records deleted successfully. Redirecting...</p>
                </div>
              </div>";
    }

    // Re-enable foreign key checks
    mysqli_query($connections, "SET FOREIGN_KEY_CHECKS = 1");

    // Redirect to driver.php after 2 seconds
    redirectToDriver();
        } else {
            // Deletion failed, display error message
            echo "<script>
                    alert('Error: " . mysqli_error($connections) . "');
                    window.location.href = 'driver.php';
                </script>";
        }
    } else {
        // Incorrect final confirmation string
        echo "<script>
                alert('Error: Incorrect final confirmation string.');
                window.location.href = 'driver.php';
            </script>";
    }
} elseif (isset($_GET['formatted_id'])) {
    // Display first confirmation prompt
    $formatted_id = mysqli_real_escape_string($connections, $_GET['formatted_id']);
    echo "<div class='center-container'>
            <h1>Delete Driver Confirmation</h1>
            <div class='message-container error-message'>
                <script src='https://cdn.lordicon.com/lordicon.js'></script>
                    <lord-icon
                        src='https://cdn.lordicon.com/akqsdstj.json'
                        trigger='hover'
                        style='width:170px;height:170px'>
                    </lord-icon>
                <p>Warning: Deleting this driver ($formatted_id) will also delete all related vehicle and violation records.</p><br>
                <p>Are you sure you want to delete this driver record? Type 'CONFIRM DELETION' to confirm.</p>
                <form method='post' action='delete_driver.php?step=confirm_deletion&formatted_id=$formatted_id'>
                    <label for='confirmation_string'>Enter confirmation:</label>
                    <input type='text' id='confirmation_string' name='confirmation_string' required>
                    <div class='button-container'>
                        <button type='submit' class='confirm'>Confirm</button>
                        <button type='button' class='cancel' onclick='window.location.href=\"driver.php\"'>Cancel</button>
                    </div>
                </form>
            </div>
          </div>";
}

?>
</body>
</html>
