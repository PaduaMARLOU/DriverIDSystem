<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("../../../connections.php");

    if(isset($_SESSION["email"])) {
        $email = $_SESSION["email"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email='$email'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];

        if($account_type != 1){
            header("Location: ../../../Forbidden.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        header("Location: ../../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }

// Check if driver ID is set and is numeric
if(isset($_GET['driver_id']) && is_numeric($_GET['driver_id'])) {
    // Sanitize the input
    $driverId = $_GET['driver_id'];

    // SQL query to delete record from tbl_driver
    $sql = "DELETE FROM tbl_driver WHERE driver_id = ?";
    
    // Prepare and execute statement
    $stmt = $connections->prepare($sql);
    $stmt->bind_param("i", $driverId);
    $stmt->execute();

    // Check if deletion was successful
    if($stmt->affected_rows > 0) {
        // Generate JavaScript alert for success
        echo "<script>alert('Driver record deleted successfully');</script>";
        // Delay redirect for 3 seconds
        echo "<script>setTimeout(function(){ window.location.href = 'drivertable.php'; }, 500);</script>";
        exit();
    } else {
        // Redirect back to the page with error message
        header("Location: drivertable.php?error=delete_failed");
        exit();
    }
} else {
    // Redirect back to the page with error message if driver ID is not valid
    header("Location: drivertable.php?error=invalid_id");
    exit();
}
?>
