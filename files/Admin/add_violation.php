<?php
session_start();

include("../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $admin_id = $fetch["admin_id"]; // Assuming admin_id is the primary key of tbl_admin

    $_SESSION["admin_id"] = $admin_id; // Store admin_id in session

    $account_type = $fetch["account_type"];

    if($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

// Initialize variables
$driver_row = null;
$success_message = "";
$error_message = "";

// Check if driver_id parameter is set and valid
if(isset($_GET['driver_id'])) {
    $driver_id = $_GET['driver_id'];

    // Fetch driver details
    $driver_query = "SELECT formatted_id, first_name, middle_name, last_name, driver_id FROM tbl_driver WHERE formatted_id = '$driver_id'";
    $driver_result = mysqli_query($connections, $driver_query);
    $driver_row = mysqli_fetch_assoc($driver_result);
    
    if ($driver_row) {
        // Check if the form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if all fields are filled
            if(!empty($_POST['violation_category']) && !empty($_POST['violation_description']) && !empty($_POST['violation_date'])) {
                // Process the form data
                // Retrieve and sanitize form inputs
                $violation_category = mysqli_real_escape_string($connections, $_POST['violation_category']);
                $violation_description = mysqli_real_escape_string($connections, $_POST['violation_description']);
                $violation_date = $_POST['violation_date'];

                // Format the date for MySQL datetime format
                $formatted_date = date('Y-m-d H:i:s', strtotime($violation_date));

                // Insert the violation into tbl_violation only if driver_id exists
                $insert_query = "INSERT INTO tbl_violation (violation_category, violation_description, violation_date, fk_driver_id, fk_admin_id) 
                                 SELECT '$violation_category', '$violation_description', '$formatted_date', driver_id, '" . $_SESSION["admin_id"] . "' 
                                 FROM tbl_driver WHERE formatted_id = '$driver_id'";
                $insert_result = mysqli_query($connections, $insert_query);

                if($insert_result) {
                    // Log the action
                    $action_details = "Added violation for driver " . $driver_row['formatted_id'];
                    $log_query = "INSERT INTO tbl_log (fk_admin_id, action_details, action_date, fk_driver_id) 
                                  VALUES ('" . $_SESSION["admin_id"] . "', '$action_details', NOW(), '" . $driver_row['driver_id'] . "')";
                    mysqli_query($connections, $log_query); // Log the action

                    $success_message = "Violation added successfully.";
                    echo "<script>alert('Violation added successfully.');</script>";
                    echo "<script>window.location.href = 'violation.php';</script>";
                    exit; // Terminate script to prevent further execution
                } else {
                    $error_message = "Error adding violation: " . mysqli_error($connections);
                }
            } else {
                $error_message = "<span style='font-size: 1.3rem;'>Please fill in all fields.</span>";
            }
        }
    } else {
        $error_message = "Driver not found.";
    }
} else {
    $error_message = "Invalid driver ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../adminportalcss/adminadd_violation.css">
    <title>Add Violation</title>
    <link rel="icon" href="../../img/stop.png" type="image/png">
</head>
<body>
    <style>
        <?php include("admin styles/violation.css"); ?>
    </style>

    <br><br>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <center><lord-icon
        src="https://cdn.lordicon.com/akqsdstj.json"
        trigger="hover"
        colors="primary:#e83a30,secondary:#ffffff"
        style="width:200px;height:200px">
    </lord-icon></center>
    <div class="container">
        <?php if ($driver_row): ?>
            <center><h1>Add Violation</h1></center><br>
            <hr width="555px"><br>
            <h2>to <?php echo $driver_row['formatted_id']; ?> - <?php echo $driver_row['last_name'] . ', ' . $driver_row['first_name'] . ' ' . $driver_row['middle_name']; ?></h2><br>
            <?php if($success_message): ?>
                <div style="color: green;"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <label for="violation_category">Violation Category:</label>
                <select id="violation_category" name="violation_category">
                <?php
                    // Fetch enum values from tbl_violation
                    $enum_query = "SHOW COLUMNS FROM tbl_violation LIKE 'violation_category'";
                    $enum_result = mysqli_query($connections, $enum_query);
                    $enum_row = mysqli_fetch_assoc($enum_result);
                    $enum_str = $enum_row['Type'];
                    preg_match_all("/'([^']+)'/", $enum_str, $matches);
                    $enum_values = $matches[1];

                    // Populate dropdown options with enum values
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
            </form>
            <br>
            <a href="violation.php">Back to Violation Page</a>
        <?php endif; ?>
    </div>
    <br><br>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connections);
?>
