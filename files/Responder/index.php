<?php
session_start();

// Include the database connection
include("../../connections.php");

// Check if the user is logged in and authorized
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);

    $admin_id = $fetch["admin_id"];
    $_SESSION["admin_id"] = $admin_id; // Store admin_id in session

    $account_type = $fetch["account_type"];

    if ($account_type != 1 && $account_type != 2 && $account_type != 4) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }

    // Format the name as "last_name, first_name M."
    $last_name = $fetch["last_name"];
    $first_name = $fetch["first_name"];
    $middle_name_initial = isset($fetch["middle_name"]) && !empty($fetch["middle_name"]) ? strtoupper($fetch["middle_name"][0]) . '.' : ''; // Get middle name initial
    $formatted_name = "$last_name, $first_name $middle_name_initial";

} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}


// Initialize variables
$driver_row = null;
$success_message = "";
$error_message = "";

// Check if the form has been submitted with a driver_id input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['formatted_id'])) {
        // Sanitize input to prevent SQL injection
        $driver_id = mysqli_real_escape_string($connections, $_POST['formatted_id']);

        // Fetch driver details from database based on the formatted_id
        $driver_query = "SELECT formatted_id, first_name, middle_name, last_name, driver_id FROM tbl_driver WHERE formatted_id = '$driver_id'";
        $driver_result = mysqli_query($connections, $driver_query);
        $driver_row = mysqli_fetch_assoc($driver_result);

        if ($driver_row) {
            // If the driver is found, process the violation form submission
            if (!empty($_POST['violation_category']) && !empty($_POST['violation_description']) && !empty($_POST['violation_date'])) {
                // Sanitize violation inputs
                $violation_category = mysqli_real_escape_string($connections, $_POST['violation_category']);
                $violation_description = mysqli_real_escape_string($connections, $_POST['violation_description']);
                $violation_date = $_POST['violation_date'];

                // Format the date
                $formatted_date = date('Y-m-d H:i:s', strtotime($violation_date));

                // Insert the violation into `tbl_violation` table
                $insert_query = "INSERT INTO tbl_violation (violation_category, violation_description, violation_date, fk_driver_id, fk_admin_id) 
                                 SELECT '$violation_category', '$violation_description', '$formatted_date', driver_id, '" . $_SESSION["admin_id"] . "' 
                                 FROM tbl_driver WHERE formatted_id = '$driver_id'";
                $insert_result = mysqli_query($connections, $insert_query);

                if ($insert_result) {
                    // Log the action
                    $action_details = "Added violation for driver " . $driver_row['formatted_id'];
                    $log_query = "INSERT INTO tbl_log (fk_admin_id, action_details, action_date, fk_driver_id) 
                                  VALUES ('" . $_SESSION["admin_id"] . "', '$action_details', NOW(), '" . $driver_row['driver_id'] . "')";
                    mysqli_query($connections, $log_query);

                    // Construct success message with entered data
                    $success_message = "Violation added successfully for Driver ID: " . $driver_row['formatted_id'] . 
                    "<br>Violation Category: " . $violation_category . 
                    "<br>Description: " . $violation_description . 
                    "<br>Violation Date: " . $formatted_date;

                    // Display success message in a browser alert
                    echo "<script>
                    alert('Violation added successfully for Driver ID: " . $driver_row['formatted_id'] . 
                    "\\nViolation Category: " . $violation_category . 
                    "\\nDescription: " . $violation_description . 
                    "\\nViolation Date: " . $formatted_date . 
                    "\\n\\nNote: Please take a screenshot of this confirmation as proof for your records.');
                    </script>";

                    echo "<script>
                        window.location.href = 'index.php'; 
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000); // Refresh after 1 second
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000); // Refresh again after 2 seconds
                    </script>";

                    exit;
                } else {
                    $error_message = "Error adding violation: " . mysqli_error($connections);
                }
            } else {
                $error_message = "<span style='font-size: 1.3rem;'>Please fill in all fields.</span>";
            }
        } else {
            $error_message = "Driver not found.";
        }
    } else {
        $error_message = "Please enter a formatted ID.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="responder.css">
    <title>Responder's Add Violation Portal</title>
    <link rel="icon" href="../../img/Brgy. Estefania Logo (Old).png" type="image/png">
</head>
<body>
    <div class="container">
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <center><lord-icon
            src="https://cdn.lordicon.com/akqsdstj.json"
            trigger="hover"
            colors="primary:#e83a30,secondary:#ffffff"
            style="width:200px;height:200px">
        </lord-icon></center>
        <center><h1>Responder Portal for Adding Violation</h1></center>
        <center><h5>Current Responder Account: <?php echo htmlspecialchars($formatted_name); ?></h5></center>
        <hr><br>

        <?php if ($success_message): ?>
            <div style="color: green;"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form for entering formatted ID -->
        <form method="post" action="">
        <label for="formatted_id">Driver's ID:</label><br>
        <input type="text" id="formatted_id" name="formatted_id" 
           value="<?php if (isset($_POST['formatted_id'])) echo htmlspecialchars($_POST['formatted_id']); ?>" 
            required>
        <input type="submit" value="Enter"><br><br>

            <?php if ($driver_row): ?>
                <h2>Driver Details:</h2>
                <p><?php echo $driver_row['formatted_id']; ?> - <?php echo $driver_row['last_name'] . ', ' . $driver_row['first_name'] . ' ' . $driver_row['middle_name']; ?></p><br>

                <!-- Violation Form -->
                <label for="violation_category">Violation Category:</label>
                <select id="violation_category" name="violation_category">
                <?php
                    // Fetch enum values from `tbl_violation`
                    $enum_query = "SHOW COLUMNS FROM tbl_violation LIKE 'violation_category'";
                    $enum_result = mysqli_query($connections, $enum_query);
                    $enum_row = mysqli_fetch_assoc($enum_result);
                    $enum_str = $enum_row['Type'];
                    preg_match_all("/'([^']+)'/", $enum_str, $matches);
                    $enum_values = $matches[1];

                    // Populate dropdown with enum values
                    foreach ($enum_values as $value) {
                        echo "<option value='$value'>$value</option>";
                    }
                ?>
                </select><br><br>

                <label for="violation_description">Violation Description:</label><br>
                <textarea id="violation_description" name="violation_description"><?php if(isset($_POST['violation_description'])) echo htmlspecialchars($_POST['violation_description']); ?></textarea><br>

                <label for="violation_date">Violation Date and Time:</label><br>
                <input type="datetime-local" id="violation_date" name="violation_date" value="<?php if(isset($_POST['violation_date'])) echo htmlspecialchars($_POST['violation_date']); ?>"><br>

                <input type="submit" value="Submit">
            <?php endif; ?>
        </form>

        <br>
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connections);
?>
