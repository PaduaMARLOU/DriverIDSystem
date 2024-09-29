<?php
session_start(); // Ensure session is started

include("../../connections.php");

// Check if the user is logged in
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Fetch the account type and id from the database
    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];
    $user_id = $fetch["admin_id"]; // Store logged-in user ID
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = mysqli_real_escape_string($connections, $_POST['username']);
    $new_password = mysqli_real_escape_string($connections, $_POST['password']);
    $admin_id = mysqli_real_escape_string($connections, $_POST['admin_id']);

    // Validate password confirmation
    if (isset($_POST['confirm_password']) && $_POST['confirm_password'] === $new_password) {
        // No hashing - use the password directly
        // Update the username and password in the database
        $update_query = "UPDATE tbl_admin SET username='$new_username', password='$new_password' WHERE admin_id='$admin_id'";
        if (mysqli_query($connections, $update_query)) {
            // Debugging line
            error_log("Password updated to: '$new_password' for user ID: '$admin_id'");
            echo "<script>alert('Update password and username successfully.'); window.location.href='index.php';</script>";
            exit; // Ensure script stops executing after showing alert
        
        } else {
            echo "<script>alert('Error updating record: " . mysqli_error($connections) . "'); window.location.href='index.php';</script>";
            error_log("MySQL error: " . mysqli_error($connections)); // Log error for debugging
        }
    } else {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    }
}
?>
