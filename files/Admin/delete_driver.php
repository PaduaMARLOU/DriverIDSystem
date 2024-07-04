<?php

session_start();

include("../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
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

// Check if confirmation parameter and formatted_id parameter are set
if(isset($_GET['confirm']) && $_GET['confirm'] == 'true' && isset($_GET['formatted_id'])) {
    // Sanitize the formatted_id parameter to prevent SQL injection
    $formatted_id = mysqli_real_escape_string($connections, $_GET['formatted_id']);
    
    // Prepare DELETE query
    $query = "DELETE FROM tbl_driver WHERE formatted_id = '$formatted_id'";

    // Execute the DELETE query
    $result = mysqli_query($connections, $query);

    // Check if deletion was successful
    if ($result) {
        // Deletion successful, display success message as HTML with CSS
        echo "<div style='background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; padding: 10px; margin-bottom: 20px;'>Driver record deleted successfully. Redirecting...</div>";
        // Redirect to driver.php after 3 seconds
        redirectToDriver();
    } else {
        // Deletion failed, display error message
        echo "<script>
                alert('Error: " . mysqli_error($connections) . "');
                window.location.href = 'driver.php';
            </script>";
    }
} elseif(isset($_GET['formatted_id'])) {
    // formatted_id parameter is set but confirmation parameter is missing
    // Display confirmation prompt
    $formatted_id = mysqli_real_escape_string($connections, $_GET['formatted_id']);
    echo "<script>
            var confirmDelete = confirm('Are you sure you want to delete this driver record?');
            if(confirmDelete) {
                // User confirmed deletion, redirect to delete_driver.php with confirm=true
                window.location.href = 'delete_driver.php?confirm=true&formatted_id=$formatted_id';
            } else {
                // User cancelled deletion, redirect back to driver.php
                window.location.href = 'driver.php';
            }
          </script>";
} else {
    // If formatted_id parameter is not set, display an error message and redirect back to driver.php
    echo "<script>
            alert('Error: Driver ID not provided.');
            window.location.href = 'driver.php';
        </script>";
}

// Close the database connection
mysqli_close($connections);
?>
