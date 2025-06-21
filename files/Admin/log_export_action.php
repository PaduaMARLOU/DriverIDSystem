<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
include("../../connections.php");

// Check if the user is logged in
if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Query to authenticate the user based on the session username
    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];
    $admin_id = $fetch["admin_id"]; // Assuming admin_id is the primary key of tbl_admin

    // If the user is not an admin, redirect to the Forbidden page
    if($account_type != 1){
        header("Location: ../../Forbidden3.php");
        exit;
    }
} else {
    header("Location: ../../Forbidden3.php");
    exit;
}

// Check if file_type is provided in the POST request
if (isset($_POST['file_type'])) {
    $file_type = mysqli_real_escape_string($connections, $_POST['file_type']);

    // Set timezone to Asia/Manila
    $timezone = new DateTimeZone('Asia/Manila');
    $current_datetime = (new DateTime('now', $timezone))->format('Y-m-d H:i:s');

    // Log the export action into tbl_log
    $action_details = "Driver Exported ($file_type)";
    $log_query = "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$current_datetime')";

    // Execute the query and check for success
    if (mysqli_query($connections, $log_query)) {
        // Return success JSON response
        echo json_encode(['status' => 'success']);
    } else {
        // Return error JSON response if query fails
        echo json_encode(['status' => 'error', 'message' => 'Failed to log export action']);
    }
} else {
    // Return error if file_type is not provided
    echo json_encode(['status' => 'error', 'message' => 'File type not provided']);
}

// Close the database connection
mysqli_close($connections);
?>
