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

// Check if driver ID is provided in the URL
if(isset($_GET['qr_data'])) {
    $driver_id = $_GET['qr_data'];

    // Fetch driver details from the database based on driver ID
    $query = "SELECT formatted_id FROM tbl_driver WHERE formatted_id = '$driver_id'";
    $result = mysqli_query($connections, $query);

    // Check if query was successful
    if ($result) {
        // Check if any rows were fetched
        if (mysqli_num_rows($result) > 0) {
            // Driver ID exists, redirect to ID_generation.php with the driver ID
            header("Location: ID_generation.php?id=$driver_id");
            exit; // Terminate script after redirection
        } else {
            // Driver ID not found in the database, display error message
            echo "The ID is not issued by the Barangay.";
        }
    } else {
        // Query failed
        echo "Error: " . mysqli_error($connections);
    }
} else {
    // If no QR code data is provided, display an error message
    echo "No QR code data provided.";
}

// Close the database connection
mysqli_close($connections);
?>


<!-- HTML form for QR code scanning or image upload -->
        <form action="ID_checker.php" method="post" enctype="multipart/form-data">
            <input type="file" accept="image/*" capture="camera" name="qr_image" id="qr_image">
            <input type="submit" value="Check ID">
        </form>

        <!-- Script to handle QR code scanning -->
        <script src="path/to/quagga.min.js"></script>
        <script>
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#qr_image')    // Target the file input
                },
                decoder: {
                    readers: ["code_128_reader"]    // Adjust as per your QR code format
                }
            });

            Quagga.onDetected(function(result) {
                var qr_data = result.codeResult.code;    // Extract QR code data
                window.location.href = "ID_checker.php?qr_data=" + qr_data;    // Redirect to ID_checker.php with QR data
            });

            Quagga.start();
        </script>