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
            <!DOCTYPE html>
            <html>
            <head>
                <title>Print IDs</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    #printable {
                        display: flex;
                        flex-direction: column;
                    }
                    .page {
                        break-inside: avoid; /* Prevent breaking inside this element */
                    }
                    .card-container {
                        display: flex;
                    }
                    .id-card {
                        border: 1px solid black;
                        box-sizing: border-box;
                        margin: 0;
                        padding: 20px; /* Add padding inside the border */
                    }
                    .id-card.front, .id-card.back {
                        width: 4.5in;
                        height: 6.5in;
                    }
                    .id-card.large-front, .id-card.large-back {
                        width: 10.5in;
                        height: 7in;
                        position: relative;
                    }
                    .id-card img {
                        max-width: 100%;
                        height: auto;
                    }
                    .id-card img.small {
                        width: 100px;
                        height: 100px;
                    }
                    .id-card img.large {
                        width: 200px;
                        height: 200px;
                    }
                    .id-card.large-front .qr {
                        position: absolute; /* Position QR code absolutely within the container */
                        bottom: 10px; /* Distance from the bottom edge */
                        right: 10px; /* Distance from the right edge */
                        width: 100px; /* Set the width for the QR code */
                        height: 100px; /* Set the height for the QR code */
                    }
                    h1, h2, h3 {
                        margin: 0;
                        padding: 0;
                    }
                    .print-button {
                        display: block;
                        margin: 20px;
                        padding: 10px 20px;
                        font-size: 16px;
                        color: white;
                        background-color: #007bff;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <button class='print-button' onclick='printDiv()'>Print IDs</button>
                <div id='printable'>
                    <!-- Page 1 -->
                    <div class='page'>
                        <div class='card-container'>
                            <!-- Original Front of the ID -->
                            <div class='id-card front'>
                                <h1>$driver_id</h1>
                                <p>Driver Name: $driver_name</p>
                                <p>Nickname: $nickname</p>
                                <p>Address: $address</p>
                                <p>Mobile Number: $mobile_number</p>
                                <p>Vehicle Type: $vehicle_type</p>
                                <p>Association: $association</p>
                                <img src='$pic_2x2_path' alt='2x2 Picture' class='small'>
                                <br><br>
                                <img src='$qrCodeUrl' alt='QR Code' class='small'>
                            </div>
                            <!-- Original Back of the ID -->
                            <div class='id-card back'>
                                <h2>$driver_id</h2>
                                <h3>Notification Details</h3>
                                <p><strong>Name to Notify:</strong> $name_to_notify</p>
                                <p><strong>Number to Notify:</strong> $num_to_notify</p>
                                <p><strong>Vehicle Ownership:</strong> $vehicle_ownership</p>
                                <h3>Vehicle Images</h3>
                                <img src='$vehicle_img_front_path' alt='Vehicle Front' class='small'>
                                <img src='$vehicle_img_back_path' alt='Vehicle Back' class='small'>
                            </div>
                        </div>
                    </div>
                    <!-- Page Break -->
                    <div class='page'></div>
                    <!-- Page 2 -->
                    <div class='page'>
                        <!-- New Front of the ID with larger dimensions -->
                        <div class='id-card large-front'>
                            <h1>$driver_id</h1>
                            <p>Driver Name: $driver_name</p>
                            <p>Nickname: $nickname</p>
                            <p>Address: $address</p>
                            <p>Mobile Number: $mobile_number</p>
                            <p>Vehicle Type: $vehicle_type</p>
                            <p>Association: $association</p>
                            <img src='$pic_2x2_path' alt='2x2 Picture' class='large'>
                            <br><br>
                            <img src='$qrCodeUrl' alt='QR Code' class='qr'>
                        </div>
                    </div>
                    <!-- Page Break -->
                    <div class='page'></div>
                    <!-- Page 3 -->
                    <div class='page'>
                        <!-- New Back of the ID with larger dimensions -->
                        <div class='id-card large-back'>
                            <h2>$driver_id</h2>
                            <h3>Notification Details</h3>
                            <p><strong>Name to Notify:</strong> $name_to_notify</p>
                            <p><strong>Number to Notify:</strong> $num_to_notify</p>
                            <p><strong>Vehicle Ownership:</strong> $vehicle_ownership</p>
                            <h3>Vehicle Images</h3>
                            <img src='$vehicle_img_front_path' alt='Vehicle Front' class='large'>
                            <img src='$vehicle_img_back_path' alt='Vehicle Back' class='large'>
                        </div>
                    </div>
                </div>
                <script>
                    function printDiv() {
                        var printWindow = window.open('', '', 'height=800,width=1200');
                        printWindow.document.write('<html><head><title>Print</title>');
                        printWindow.document.write('<style>body{font-family: Arial, sans-serif; margin: 0; padding: 0;}');
                        printWindow.document.write('.page{break-inside: avoid;}'); // Updated to avoid breaking inside elements
                        printWindow.document.write('.card-container{display: flex;}');
                        printWindow.document.write('.id-card{border: 1px solid black; box-sizing: border-box; margin: 0; padding: 20px;}');
                        printWindow.document.write('.id-card.front, .id-card.back{width: 4.5in; height: 6.5in;}');
                        printWindow.document.write('.id-card.large-front, .id-card.large-back{width: 10.5in; height: 7in;}');
                        printWindow.document.write('.id-card img{max-width: 100%; height: auto;}');
                        printWindow.document.write('.id-card img.small{width: 100px; height: 100px;}');
                        printWindow.document.write('.id-card img.large{width: 200px; height: 200px;}');
                        printWindow.document.write('.id-card.large-front{width: 10.5in; height: 7in; position: relative;}');
                        printWindow.document.write('.id-card.large-front .qr{position: absolute; bottom: 10px; right: 10px; width: 100px; height: 100px;}');
                        printWindow.document.write('h1, h2, h3{margin: 0; padding: 0;}');
                        printWindow.document.write('</style></head><body >');
                        printWindow.document.write(document.getElementById('printable').innerHTML);
                        printWindow.document.write('</body></html>');
                        printWindow.document.close();
                        printWindow.focus();
                        printWindow.print();
                    }
                </script>

            </body>
            </html>
        ";

            // Output the printable ID content
            echo $printable_id;
        } else {
            echo "Driver not found.";
        }
    } else {
        echo "Error executing query: " . mysqli_error($connections);
    }
} else {
    echo "No driver ID provided.";
}

mysqli_close($connections);
?>
