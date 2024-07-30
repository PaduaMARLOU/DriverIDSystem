<?php
include('../../connections.php'); // Include your database connection

// Get the driver ID from the URL
$driver_id = $_GET['id'];

// Fetch driver details from the database
$driver_query = "SELECT * FROM tbl_driver WHERE formatted_id = '$driver_id'";
$driver_result = mysqli_query($connections, $driver_query);
$driver = mysqli_fetch_assoc($driver_result);

// Fetch vehicle details based on fk_vehicle_id
$vehicle_query = "SELECT * FROM tbl_vehicle WHERE vehicle_id = '{$driver['fk_vehicle_id']}'";
$vehicle_result = mysqli_query($connections, $vehicle_query);
$vehicle = mysqli_fetch_assoc($vehicle_result);

// Fetch association details based on fk_association_id
$association_query = "SELECT * FROM tbl_association WHERE association_id = '{$driver['fk_association_id']}'";
$association_result = mysqli_query($connections, $association_query);
$association = mysqli_fetch_assoc($association_result);

// Paths to the images
$pic_2x2_path = "../../" . $driver['pic_2x2'];
$vehicle_img_front_path = "../../" . $vehicle['vehicle_img_front'];
$vehicle_img_back_path = "../../" . $vehicle['vehicle_img_back'];
$logo_path = "../../img/Brgy Estefania Logo.png"; // Replace with the path to your logo image
$edges_path = "../../img/Edges.png"; // Path to the edges image for QR code background

// Function to generate QR code URL
function generateQRCodeURL($data) {
    return 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($data) . '&size=150x150';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Generation</title>
    <style>
        /* Default styles for screen */
        .logo {
            width: 100px;
            height: auto;
            margin-right: 10px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 10px;
        }

        .header-text {
            display: flex;
            flex-direction: column;
            margin-left: 10px;
        }

        .header-text h2,
        .header-text p {
            margin: 0;
            text-align: left;
        }

        .qr-code-container {
            position: relative;
            width: 180px;
            height: 180px;
        }

        .qr-code-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('<?php echo $edges_path; ?>') no-repeat center center;
            background-size: contain;
            background-color: transparent;
            z-index: 0;
        }

        .qr-code {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 160px;
            height: 160px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .qr-code img {
            width: 100%;
            height: auto;
        }

        .signature-line {
            border-top: 1px solid black;
            margin: 5px 0;
            width: 100%;
        }

        .signature-text {
            margin: 0;
            padding-top: 2px;
        }

        .thin-line {
            border-top: 1px solid black;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .thick-line {
            border-top: 2px solid black;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .center {
            text-align: center;
        }

        .column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 45%;
            margin: 0 2.5%;
            text-align: center;
        }

        .column img {
            width: 100%;
            height: auto;
        }

        .formatted-id-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .formatted-id-container h1 {
            font-size: 4em;
            margin: 0;
        }

        /* Ensure columns are aligned side by side */
        .content-container {
            display: flex;
            justify-content: space-between;
        }

        /* Styles specific to print */
        @page {
            margin: 0.5in;
            size: A4 landscape;
        }

        @media print {
            .btn-print {
                display: none;
            }

            .header {
                margin-bottom: 10px;
                margin-top: -40px;
            }

            .page {
                page-break-after: always;
                box-sizing: border-box;
                margin: 0;
                padding: 0.5in;
                position: relative;
            }

            .page:last-child {
                page-break-after: auto;
            }

            .id-container {
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
                width: 100%;
                height: 6.5in;
                box-sizing: border-box;
            }

            .id-front, .id-back {
                width: 4.5in;
                height: 6.5in;
                padding: 0.5in;
                border: 1px solid black;
                box-sizing: border-box;
                page-break-inside: avoid;
            }

            .vehicle-id-front, .vehicle-id-back {
                width: 10.5in;
                height: 7in;
                padding: 0.5in;
                border: 1px solid black;
                box-sizing: border-box;
            }

            .id-front img, .id-back img, .vehicle-id-front img, .vehicle-id-back img {
                max-width: 100%;
                max-height: 100%;
                height: auto;
            }

            .id-front, .id-back {
                page-break-after: always;
            }

            .vehicle-id-front, .vehicle-id-back {
                page-break-after: always;
            }

            .thin-line {
                border-top: 1px solid black;
                margin: 0;
                width: calc(100% + 1in);
                margin-left: -0.5in;
                margin-right: -0.5in;
                box-sizing: border-box;
            }

            .thick-line {
                border-top: 2px solid black;
                margin: 0;
                width: calc(100% + 1in);
                margin-left: -0.5in;
                margin-right: -0.5in;
                box-sizing: border-box;
            }
            .qr-code-container {
                width: 150px;
                height: 150px;
                margin-top: -30px;
                margin-bottom: 40px;
            }

            .qr-code {
                width: 140px;
                height: 140px;
                padding: 5px;
            }

            .qr-code-background {
                background-size: contain;
            }
        }
    </style>

</head>
<body>
    <div class="page">
        <!-- Page 1: Driver ID Front and Back -->
        <div class="id-container">
            <div class="id-front">
                <!-- Front side of Driver ID -->
                <div class="header">
                    <img src="<?php echo $logo_path; ?>" alt="Logo" class="logo">
                    <div class="header-text">
                        <h2>BARANGAY ESTEFANIA</h2>
                        <p>Bacolod City, Fortune Towne</p>
                    </div>
                </div>
                <hr class="thin-line">
                <div class="formatted-id-container">
                    <h1><?php echo $driver['formatted_id']; ?></h1>
                </div>
                <div class="content-container">
                    <div class="column">
                        <img src="<?php echo $pic_2x2_path; ?>" alt="Driver Photo">
                        <p><?php echo "{$driver['last_name']}, {$driver['first_name']} {$driver['middle_name']} {$driver['suffix_name']}"; ?></p>
                        <p>Nickname: <?php echo $driver['nickname']; ?></p>
                        <p>Mobile Number: <?php echo $driver['mobile_number']; ?></p>
                    </div>
                    <div class="column">
                        <div class="qr-code-container">
                            <div class="qr-code-background"></div> <!-- Background image -->
                            <div class="qr-code">
                                <img src="<?php echo generateQRCodeURL($driver['formatted_id']); ?>" alt="QR Code">
                            </div>
                        </div>

                        <hr class="signature-line">
                        <p class="signature-text">Signature</p>
                    </div>


                </div>
                <hr class="thick-line">
                <p>Official Driver</p>
                <p>Valid Until: <?php echo date('Y', strtotime($driver['driver_registered'])); ?></p>
            </div>

            <div class="id-back">
                <!-- Back side of Driver ID -->
                <div class="center">
                    <img src="<?php echo $vehicle_img_front_path; ?>" alt="Vehicle Image" style="width: 50%; float: left; max-height: 100%;">
                    <p style="width: 50%; float: left; text-align: left; padding-left: 10px;"><?php echo $association['association_name']; ?></p>
                    <div style="clear: both;"></div>
                    <p>The owner whose picture and signature appear among each of the associations at this barangay. This driver's ID must always be worn all the time.</p>
                    <p>In case of emergency, please contact:</p>
                    <p><?php echo $driver['name_to_notify']; ?></p>
                    <p><?php echo $driver['num_to_notify']; ?></p>
                    <p>If found, kindly please return the driver's ID to:</p>
                    <p>Barangay Estefania Treasurer's Office</p>
                    <p>(034) 492-2495</p>
                    <div class="column">
                        <hr class="signature-line">
                        <p>Kagawad Eduardo Sayson</p>
                    </div>
                    <div class="column">
                        <hr class="signature-line">
                        <p>Punong Barangay</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="page">
        <!-- Page 2: Vehicle ID Front -->
        <div class="vehicle-id-front">
            <div class="center">
                <img src="<?php echo $logo_path; ?>" alt="Logo" style="width: 100px;">
                <h2>BARANGAY ESTEFANIA</h2>
                <p>Bacolod CITY, Fortune Towne</p>
                <h1><?php echo $driver['formatted_id']; ?></h1>
                <p>The owner whose picture and signature appear among each of the associations at this barangay. This Vehicle's ID must always be affixed all the time.</p>
                <p><?php echo $association['association_name']; ?></p>
                <div class="column">
                    <div class="qr-code">
                        <img src="<?php echo generateQRCodeURL($driver['formatted_id']); ?>" alt="QR Code">
                    </div>
                    <hr class="signature-line">
                        <p>Signature</p>
                </div>
                <div class="column">
                    <img src="<?php echo $pic_2x2_path; ?>" alt="Driver Photo">
                    <p><?php echo "{$driver['last_name']}, {$driver['first_name']} {$driver['middle_name']} {$driver['suffix_name']}"; ?></p>
                    <p>Nickname: <?php echo $driver['nickname']; ?></p>
                    <p>Mobile Number: <?php echo $driver['mobile_number']; ?></p>
                </div>
                <p>Official Driver</p>
                <p>Valid Until: <?php echo date('Y', strtotime($driver['driver_registered'])); ?></p>
            </div>
        </div>
    </div>

    <div class="page">
        <!-- Page 3: Vehicle ID Back -->
        <div class="vehicle-id-back">
            <div class="center">
                <img src="<?php echo $vehicle_img_front_path; ?>" alt="Vehicle Front Image" style="width: 50%; float: left;">
                <img src="<?php echo $vehicle_img_back_path; ?>" alt="Vehicle Back Image" style="width: 50%; float: left;">
                <div style="clear: both;"></div>
                <h1><?php echo $driver['formatted_id']; ?></h1>
            </div>
        </div>
    </div>

    <!-- Button for printing -->
    <div class="print-container">
        <button class="btn-print" onclick="window.print()">Print ID</button>
    </div>
</body>
</html>
