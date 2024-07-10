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

    // Check if driver ID is provided in the URL
    if(isset($_GET['id'])) {
        $driver_id = $_GET['id'];

        // Fetch driver details from the database based on driver ID
        $query = "SELECT formatted_id, first_name, middle_name, last_name, driver_category, fk_association_id FROM tbl_driver WHERE formatted_id = '$driver_id'";
        $result = mysqli_query($connections, $query);

        // Check if query was successful
        if ($result) {
            // Check if any rows were fetched
            if (mysqli_num_rows($result) > 0) {
                // Fetch driver details
                $row = mysqli_fetch_assoc($result);
                $driver_name = $row['last_name'] . ', ' . $row['first_name'];
                if (!empty($row['middle_name'])) {
                    $driver_name .= ' ' . $row['middle_name'];
                }
                $vehicle_type = $row['driver_category'];
                $association = !empty($row['fk_association_id']) ? $row['fk_association_id'] : 'N/A';

                // Generate driver data string for QR code
                $driver_data = "Driver ID: $driver_id\nDriver Name: $driver_name\nVehicle Type: $vehicle_type\nAssociation: $association";

                // URL encode the driver data
                $encoded_driver_data = urlencode($driver_data);

                // Generate QR code using an online QR code generation API
                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$encoded_driver_data";

                // Generate printable ID with QR code
                $printable_id = "
                    <div style='border: 1px solid black; padding: 10px; width: 400px;'>
                        <h2>Driver ID: $driver_id</h2>
                        <p>Driver Name: $driver_name</p>
                        <p>Vehicle Type: $vehicle_type</p>
                        <p>Association: $association</p>
                        <img src='$qrCodeUrl' alt='QR Code'>
                    </div>
                ";

                // Output printable ID
                echo $printable_id;
            } else {
                echo "Driver not found in the database.";
            }
        } else {
            // Query failed
            echo "Error: " . mysqli_error($connections);
        }

        // Close the database connection
        mysqli_close($connections);
    } else {
        echo "Driver ID not provided.";
    }
?>
