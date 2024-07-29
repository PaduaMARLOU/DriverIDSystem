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
    $query = "SELECT d.formatted_id, d.first_name, d.middle_name, d.last_name, d.nickname, d.address, d.mobile_number, d.pic_2x2, d.driver_category,
              d.name_to_notify, d.num_to_notify, d.vehicle_ownership, 
              v.vehicle_img_front, v.vehicle_img_back,
              CONCAT(a.association_name, ' - ', a.association_area) AS association 
              FROM tbl_driver d 
              LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
              LEFT JOIN tbl_vehicle v ON d.fk_vehicle_id = v.vehicle_id
              WHERE d.formatted_id = '$driver_id'";
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
            $nickname = $row['nickname'];
            $address = $row['address'];
            $mobile_number = $row['mobile_number'];
            $pic_2x2 = $row['pic_2x2'];
            $vehicle_type = $row['driver_category'];
            $association = !empty($row['association']) ? $row['association'] : 'N/A';
            $name_to_notify = $row['name_to_notify'];
            $num_to_notify = $row['num_to_notify'];
            $vehicle_ownership = $row['vehicle_ownership'];
            $vehicle_img_front = $row['vehicle_img_front'];
            $vehicle_img_back = $row['vehicle_img_back'];

            // Path to the images
            $pic_2x2_path = "../../" . $pic_2x2;
            $vehicle_img_front_path = "../../" . $vehicle_img_front;
            $vehicle_img_back_path = "../../" . $vehicle_img_back;

            // Generate driver data string for QR code
            $driver_data = "Driver ID: $driver_id\nDriver Name: $driver_name\nNickname: $nickname\nAddress: $address\nMobile Number: $mobile_number\nVehicle Type: $vehicle_type\nAssociation: $association";

            // URL encode the driver data
            $encoded_driver_data = urlencode($driver_data);

            // Generate QR code using an online QR code generation API
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$encoded_driver_data";

            // Generate printable IDs with QR code
            $printable_id = "
                <div id='printable' style='display: flex;'>
                    <!-- Front of the ID -->
                    <div style='border: 1px solid black; padding: 10px; width: 4.5in; height: 6.5in; margin-right: 0; box-sizing: border-box;'>
                        <h1 style='font-size: 2.5em; margin: 0;'>$driver_id</h1>
                        <p>Driver Name: $driver_name</p>
                        <p>Nickname: $nickname</p>
                        <p>Address: $address</p>
                        <p>Mobile Number: $mobile_number</p>
                        <p>Vehicle Type: $vehicle_type</p>
                        <p>Association: $association</p>
                        <img src='$pic_2x2_path' alt='2x2 Picture' style='width: 100px; height: 100px;'>
                        <br><br>
                        <img src='$qrCodeUrl' alt='QR Code' style='max-width: 100%;'>
                    </div>
                    <!-- Back of the ID -->
                    <div style='border: 1px solid black; padding: 10px; width: 4.5in; height: 6.5in; margin-left: 0; box-sizing: border-box;'>
                        <h2 style='font-size: 2.5em; margin: 0;'>$driver_id</h2>
                        <h3>Notification Details</h3>
                        <p><strong>Name to Notify:</strong> $name_to_notify</p>
                        <p><strong>Number to Notify:</strong> $num_to_notify</p>
                        <p><strong>Vehicle Ownership:</strong> $vehicle_ownership</p>
                        <h3>Vehicle Images</h3>
                        <img src='$vehicle_img_front_path' alt='Vehicle Front' style='width: 100px; height: 100px;'>
                        <img src='$vehicle_img_back_path' alt='Vehicle Back' style='width: 100px; height: 100px;'>
                    </div>
                </div>
                <button onclick='printDiv()'>Print</button>
            ";

            // Output printable IDs
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

<script>
function printDiv() {
    var printWindow = window.open('', '', 'height=800,width=1200');
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>body{font-family: Arial, sans-serif; margin: 0; padding: 0;}');
    printWindow.document.write('#printable{display: flex;}');
    printWindow.document.write('#printable > div{border: 1px solid black; padding: 10px; width: 4.5in; height: 6.5in; margin: 0; box-sizing: border-box;}');
    printWindow.document.write('#printable h1, #printable h2{font-size: 2.5em; margin: 0;}');
    printWindow.document.write('</style></head><body >');
    printWindow.document.write(document.getElementById('printable').outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>
