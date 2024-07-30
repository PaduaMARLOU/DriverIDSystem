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

// Determine if the color is light or dark
function isDarkColor($hex) {
    // Convert hex to RGB
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Calculate brightness
    $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
    
    // Return true if the color is dark
    return $brightness < 128;
}

// Get the association color
$association_color = $association['association_color'];
$text_color = isDarkColor($association_color) ? 'white' : 'black';

$full_name = "{$driver['last_name']}, {$driver['first_name']} {$driver['middle_name']} {$driver['suffix_name']}";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Generation</title>
    <style>
        /* General styles for screen and print */
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
            width: 150px;
            height: 150px;
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
            width: 140px;
            height: 140px;
            background: white;
            border-radius: 10px;
            padding: 5px;
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
            margin-top: 20px;
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
            margin-top: 10px;
            margin-left: -40px;
            margin-right: -40px;
            margin-bottom: -10px;
        }

        .formatted-id-container h1 {
            font-size: 55pt;
            margin: 0;
        }

        /* Ensure columns are aligned side by side */
        .content-container {
            display: flex;
            justify-content: space-between;
        }

        /* Print button styles */
        .btn-print {
            display: block; /* Visible on screen */
            margin: 10px auto;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-print:hover {
            background-color: #0056b3;
        }

        /* Screen-specific styles */
        @media screen {
            .id-container, .id-front, .id-back, .vehicle-id-front, .vehicle-id-back {
                border-bottom: 5px solid black; /* Add a thick border for screen view */
                position: relative;
            }

            .id-front::after, .id-back::after, .vehicle-id-front::after, .vehicle-id-back::after {
                content: "";
                display: block;
                border-bottom: 5px solid black; /* Add a thick line after the border */
                margin: 10px 0;
                width: 100%;
            }
        }


        @media print {
            .btn-print {
                display: none; /* Keep the button visible on screen */
            }

            .page {
                box-sizing: border-box;
                padding: 0.5in;
                position: relative;
            }

            .header {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                margin-bottom: 10px;
                margin-top: -30px;
                margin-right: -40px;
                margin-left: -40px;
                font-family: 'Helvetica', Arial, sans-serif; /* Apply Helvetica font */
                font-weight: bold;
            }

            .id-container {
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
                width: 100%;
                height: 6.5in;
                box-sizing: border-box;
                font-family: 'Helvetica', Arial, sans-serif; /* Apply Helvetica font */
            }

            .id-front, .id-back {
                width: 4.5in;
                height: 6.5in;
                padding: 0.5in;
                border: 1px solid black;
                box-sizing: border-box;
                page-break-inside: avoid;
                background-color: <?php echo $association_color; ?>; /* Background color for printing */
                color: <?php echo $text_color; ?>; /* Text color based on background */
                position: relative; /* Create positioning context */
            }

            .vehicle-id-front, .vehicle-id-back {
                width: 10.5in;
                height: 7in;
                padding: 0.5in;
                border: 1px solid black;
                box-sizing: border-box;
                background-color: <?php echo $association_color; ?>; /* Background color for printing */
                color: <?php echo $text_color; ?>; /* Text color based on background */
            }

            .id-front img, .id-back img, .vehicle-id-front img, .vehicle-id-back img {
                object-fit: cover;
            }

            .id-front, .id-back, .vehicle-id-front, .vehicle-id-back {
                page-break-after: always;
            }

            .thin-line {
                border: 2px solid <?php echo $text_color; ?>;
                margin: 0;
                width: calc(100% + 1in);
                margin-left: -0.5in;
                margin-right: -0.5in;
                box-sizing: border-box;
            }

            .thick-line {
                border: 4px solid <?php echo $text_color; ?>;
                margin: 0;
                position: absolute; /* Position absolutely within the parent */
                bottom: 0.32in; /* Move it slightly above the bottom */
                left: -0.5in; /* Offset from the left */
                width: calc(100% + 1in); /* Full width of the parent plus margins */
                box-sizing: border-box;
            }


            .qr-code-container {
                width: 150px;
                height: 150px;
                margin-top: 20px;
                margin-bottom: 40px;
                margin-left: 30px;
            }

            .qr-code {
                width: 130px;
                height: 130px;
                padding: 5px;
            }

            .qr-code-background {
                background-size: contain;
            }

            .signature-line {
                border: 1px solid <?php echo $text_color; ?>;
                margin: 5px 0;
                width: 100%;
                margin-top: -5px;
                margin-left: 35px;
            }

            .signature-text {
                margin: 0;
                padding-top: 2px;
                margin-left: 35px;
            }

            .driver-info-front {
                font-family: 'Helvetica', Arial, sans-serif; /* Apply Helvetica font */
                font-weight: bold; /* Make text bold */
                font-size: 18pt; /* Maximum font size */
                color: <?php echo $text_color; ?>; /* Set text color */
                margin: 20px auto 10px auto; /* Center the element and set top/bottom margins */
                padding: 0; /* Remove default padding */
                text-align: center; /* Center text content */
                overflow: hidden; /* Hide overflow */
                box-sizing: border-box; /* Include padding and border in element's total width and height */
                width: 350px;
                /* width: 100%; Ensure element uses full width available */
                /* max-width: calc(100% - 40px); Set a maximum width, adjust as needed */
                /* white-space: nowrap; Prevent text from wrapping to the next line */
                /* text-overflow: ellipsis; /* Show an ellipsis if the text overflows */
            }


            .footer-driver {
                font-weight: bold; /* Make text bold */
                font-size: 15pt;   /* Set the font size */
                font-family: 'Helvetica', Arial, sans-serif; /* Apply Helvetica font */
                margin: 0; /* Remove default margin */
                position: absolute; /* Position absolutely within the parent */
                bottom: 0.05in; /* Move it slightly above the bottom */
                left: 0;
                width: 100%; /* Full width of the parent */
                text-align: center; /* Center text */
            }

        }


    </style>

</head>
<body>
     <!-- Button for printing -->
    <button class="btn-print" onclick="window.print()">Print ID</button>
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
                        <img src="<?php echo $pic_2x2_path; ?>" alt="Driver Photo" style="width: 2in; height: 2in; object-fit: cover;">
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
                <p class="driver-info-front">
                    <?php 
                        // Format the name with middle initial
                        $middle_initial = !empty($driver['middle_name']) ? substr($driver['middle_name'], 0, 1) . '.' : ''; 
                        echo "{$driver['last_name']}, {$driver['first_name']} {$middle_initial} {$driver['suffix_name']}";
                    ?>
                    <br>
                    <?php echo "\"<i>{$driver['nickname']}</i>\" <br> {$driver['mobile_number']}"; ?>
                </p>
                <hr class="thick-line">
                <p class="footer-driver">Official Driver - <i>Valid for Year: <?php echo date('Y', strtotime($driver['driver_registered'])); ?></i></p>
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
                    <div class="qr-code-container">
                        <div class="qr-code-background"></div> <!-- Background image -->
                        <div class="qr-code">
                            <img src="<?php echo generateQRCodeURL($driver['formatted_id']); ?>" alt="QR Code">
                        </div>
                    </div>

                    <hr class="signature-line">
                    <p class="signature-text">Signature</p>
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
    <script>
        function adjustFontSizeForPrint() {
            console.log('Adjusting font size for print...');
            const infoFront = document.querySelector('.driver-info-front');
            let currentFontSize = 18; // Start with maximum font size
            const minFontSize = 6; // Minimum font size to prevent text from being too small

            infoFront.style.fontSize = `${currentFontSize}pt`;

            // Force reflow
            infoFront.offsetHeight;

            console.log('Initial fontSize:', currentFontSize);
            console.log('Initial scrollWidth:', infoFront.scrollWidth);
            console.log('Initial clientWidth:', infoFront.clientWidth);

            while (infoFront.scrollWidth > infoFront.clientWidth && currentFontSize > minFontSize) {
                currentFontSize--;
                infoFront.style.fontSize = `${currentFontSize}pt`;

                // Force reflow
                infoFront.offsetHeight;

                console.log('Adjusted fontSize:', currentFontSize);
                console.log('scrollWidth:', infoFront.scrollWidth);
                console.log('clientWidth:', infoFront.clientWidth);
            }
        }

        window.addEventListener('beforeprint', function() {
            console.log('Before print event triggered.');
            adjustFontSizeForPrint();
        });

    </script>

</body>
</html>
