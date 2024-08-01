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
$vehicle_side_path = "../../img/vehicle side design.png";

// Function to generate QR code URL with specified size
function generateQRCodeURL($data, $size = '150x150') {
    return 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($data) . '&size=' . $size;
}

// Concatenate details for the QR code
$qr_code_data = "Driver ID: {$driver['formatted_id']}\n";
$qr_code_data .= "Name: {$driver['first_name']} {$driver['middle_name']} {$driver['last_name']} {$driver['suffix_name']}\n";
//$qr_code_data .= "Birth Date: {$driver['birth_date']}\n";
$qr_code_data .= "Nickname: {$driver['nickname']}\n";
//$qr_code_data .= "Birth Place: {$driver['birth_place']}\n";
$qr_code_data .= "Mobile Number: {$driver['mobile_number']}\n";
$qr_code_data .= "Sex: {$driver['sex']}\n";
//$qr_code_data .= "Age: {$driver['age']}\n";
//$qr_code_data .= "Address: {$driver['address']}\n";
$qr_code_data .= "Emergency Contact - Name: {$driver['name_to_notify']}\n";
//$qr_code_data .= "Emergency Contact - Relationship: {$driver['relationship']}\n";
$qr_code_data .= "Emergency Contact - Number: {$driver['num_to_notify']}\n";
$qr_code_data .= "Vehicle Ownership: {$driver['vehicle_ownership']}\n";
$qr_code_data .= "Vehicle Category: {$vehicle['vehicle_category']}\n";
//$qr_code_data .= "Vehicle Registered: {$vehicle['vehicle_registered']}\n";
//$qr_code_data .= "Brand: {$vehicle['brand']}\n";
//$qr_code_data .= "Plate Number: {$vehicle['plate_num']}\n";
//$qr_code_data .= "Vehicle Color: {$vehicle['vehicle_color']}\n";
//$qr_code_data .= "Name of Owner: {$vehicle['name_of_owner']}\n";
//$qr_code_data .= "Owner Phone Number: {$vehicle['owner_phone_num']}\n";
//$qr_code_data .= "Address of Owner: {$vehicle['addr_of_owner']}\n";
//$qr_code_data .= "Association Category: {$association['association_category']}\n";
//$qr_code_data .= "Association President: {$association['association_president']}\n";
$qr_code_data .= "Association Name: {$association['association_name']}\n";
//$qr_code_data .= "Association Color: {$association['association_color_name']}\n";
$qr_code_data .= "Association Area: {$association['association_area']}\n";

// Generate the QR code URL
$qr_code_url = generateQRCodeURL($qr_code_data, '300x300');

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
$shadow_color = $text_color === 'white' ? 'black' : 'white';

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
                text-shadow: 2px 3px 4px <?php echo $shadow_color; ?>; /* Conditional text shadow color */
            }

            .vehicle-id-front, .vehicle-id-back {
                width: 10.5in;
                height: 7in;
                padding: 0.5in;
                border: 1px solid black;
                box-sizing: border-box;
                background-color: <?php echo $association_color; ?>; /* Background color for printing */
                color: <?php echo $text_color; ?>; /* Text color based on background */
                text-shadow: 2px 3px 4px <?php echo $shadow_color; ?>; /* Conditional text shadow color */
            }

            .id-front img, .id-back img, .vehicle-id-front img, .vehicle-id-back img {
                object-fit: cover;
            }

            .id-front, .id-back, .vehicle-id-front, .vehicle-id-back {
                page-break-after: always;
            }

            .thin-line {
                border: 2px solid black;
                margin: 0;
                width: calc(100% + 1in);
                margin-left: -0.5in;
                margin-right: -0.5in;
                box-sizing: border-box;
            }

            .thick-line {
                border: 4px solid black;
                margin: 0;
                position: absolute; /* Position absolutely within the parent */
                bottom: 0.32in; /* Move it slightly above the bottom */
                left: 0in; /* Offset from the left */
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
                border: 1px solid black;
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

            .back-contact {
                text-align: center; /* Center align text, can be adjusted */
                color: <?php echo $text_color; ?>;
                text-shadow: 
                    -1px -1px 0 #000,  
                    1px -1px 0 #000,
                    -1px 1px 0 #000,
                    1px 1px 0 #000; /* Creates the stroke effect */
            }

            .header-back {
                display: flex;
                flex-direction: column; /* Stack items vertically */
                align-items: center; /* Center items horizontally */
                justify-content: flex-start; /* Align items to the top */
                margin: 0; /* Remove any margins */
                padding: 10px; /* Add some padding if needed */
                font-family: 'Helvetica', Arial, sans-serif; /* Apply Helvetica font */
                font-weight: bold;
                position: relative; /* Allows for positioning adjustments */
                top: -50px; /* Anchor to the top */
            }

            .header-back-text {
                display: flex;
                align-items: center; /* Align items vertically center */
                gap: 20px; /* Adjust this value to control the spacing between the image and heading */
                justify-content: center; /* Center the content horizontally */
                text-align: center; /* Center text alignment */
                margin: 0 auto; /* Center the div itself if needed */
            }

            .header-back-text img {
                width: 1.5in;
                height: 1.5in;
                object-fit: fill;
            }

            .header-back-text h1 {
                margin: 0; /* Remove default margin */
                font-size: 40px; /* Adjust font size as needed */
            }

            .header-back-text h3 {
                margin: 10px 0 0 0; /* Add space above the h3 element to create the gap */
                font-size: 20px; /* Adjust font size as needed */
            }


            .owner-whose {
                margin-top: 10px; /* Space above the description */
                padding: 0 10px; /* Horizontal padding for spacing */
                text-align: justify; /* Justify text alignment */
                font-size: 14px; /* Adjust font size as needed */
                line-height: 1.5; /* Line height for readability */
                margin-left: -20px;
                margin-right: -20px;
            }

            .emergency-details {
                font-family: 'Helvetica', Arial, sans-serif; /* Apply Helvetica font */
                padding: 10px; /* Add padding inside the container */
                text-align: center; /* Center align the text within the container */
                position: relative; /* Ensure the container is positioned relative to its normal flow */
                top: -70px; /* Move the container up */
            }

            .emergency-details p {
                margin: 5px 0; /* Space above and below each paragraph */
            }

            .emergency-details p:not(.back-contact) {
                font-size: 22px; /* Adjust the font size as needed */
                font-weight: bold;
            }

            .emergency-details p > strong {
                font-size: 30px; /* Adjust the font size as needed for the name */
                font-weight: bold; /* Ensure the name is bold */
            }

            .emergency-details .back-contact {
                font-weight: bold; /* Make the text bold for section headers */
                margin-bottom: 10px; /* Space below the section headers */
                margin-left: -40px;
                margin-right: -40px;
            }

            .column-back {
                display: flex; /* Use flexbox for column layout */
                font-weight: bold; /* Make text bold */
                font-size: 15pt;   /* Set the font size for the container */
                font-family: 'Helvetica', Arial, sans-serif; /* Apply Helvetica font */
                position: absolute; /* Position absolutely within the parent */
                bottom: 0.05in; /* Move it slightly above the bottom */
                left: 0;
                width: 100%; /* Full width for the container */
                box-sizing: border-box; /* Include padding and border in element's total width and height */
                padding: 0 10px; /* Optional padding for spacing */
                justify-content: space-between; /* Space out columns */
            }

            .column-back > div {
                width: 50%; /* Half width for each column */
                text-align: center; /* Center text in columns */
            }

            .column-back p {
                font-size: 12pt; /* Adjust font size as needed for smaller text */
            }

            .signature-line-back {
                border: none; /* Remove default border */
                border: 1.5px solid black; /* Add a top border */
                width: 80%; /* Adjust width as needed */
                margin: 0 auto; /* Center the line */
                margin-bottom: 10px; /* Space below the line */
            }

            .vehicle-front-header {
                display: flex;
                align-items: center;
                position: absolute;
                top: 70px;
                left: 80px;
                width: calc(100% - 120px);
                font-family: 'Helvetica', Arial, sans-serif;
                font-weight: bold;
            }

            .vehicle-front-header .logo {
                width: 150px;
                margin-right: 15px;
            }

            .vehicle-front-header .header-front-text {
                display: flex;
                flex-direction: row; /* Display text in a row */
                align-items: center; /* Align items vertically in the center */
            }

            .vehicle-front-header h1 {
                margin: 0;
                font-size: 2.2em; /* Increase font size for h1 */
                margin-right: 10px; /* Space between h1 and p */
            }

            .vehicle-front-header p {
                margin: 0;
                font-size: 1.5em; /* Font size for p */
            }

            .main-vehicle-container {
                display: flex;
                justify-content: space-between; /* Adjusts spacing between columns */
                align-items: flex-start; /* Aligns columns at the top of the container */
                margin: 10px; /* Optional: Margin around the container */
                margin-top: 80px;
                font-family: 'Helvetica', Arial, sans-serif;
                font-weight: bold;
            }

            .info-column {
                display: flex;
                flex-direction: column;
                margin: 10px;
                padding: 15px;
                width: 1000px; /* Adjust width as needed */
                margin-left: -40px;
                margin-right: -25px;
            }


            .info-column h1 {
                font-size: 75pt; /* Adjust font size as needed */
                font-weight: bold;
                margin-bottom: -30px;
                margin-top: 30px;
            }

            .info-column h3 {
                font-size: 30pt; /* Adjust font size as needed */
                font-weight: normal;
                margin-bottom: 1px;
            }

            .info-column p {
                font-size: 16pt; /* Adjust font size as needed */
                line-height: 1.5;
                text-align: justify; /* Justifies the text within the <p> element */
            }


            .column-vehicle-right {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 45%;
                margin: 0 5%;
                text-align: center;
                margin-right: 10px;
                margin-top: 100px;
            }

            .qr-code-container-vehicle {
                width: 150px;
                height: 150px;
                margin-top: 20px;
                margin-bottom: 40px;
                margin-left: 30px;
            }

            .signature-line-vehicle {
                border: 1px solid black;
                margin: 5px 0;
                width: 80%;
                margin-top: -5px;
                margin-left: 35px;
            }
            
            .column-vehicle-right-bottom {
                position: absolute;
                bottom: 125px; /* Adjust the distance from the bottom as needed */
                right: 100px;  /* Adjust the distance from the right as needed */
                width: 300px; /* Adjust the width as needed */
                text-align: center; /* Align text to the right */
                font-size: 20pt; /* Adjust font size as needed */
                font-family: 'Helvetica', Arial, sans-serif;
                font-weight: bold;
            }

            .status-vehicle {
                background-image: url('../../img/vehicle side design.png'); /* Path to the background image */
                background-size: contain; /* Ensure the entire image is visible */
                background-repeat: no-repeat; /* Prevent repeating the background image */
                background-position: center; /* Center the background image */
                padding: 20px; /* Adjust padding as needed */
                color: white; /* Adjust text color for better readability */
                text-align: center; /* Center the text */
                font-size: 25pt; /* Adjust font size as needed */
                font-weight: bold; /* Make the text bold */
                border-radius: 10px; /* Optional: rounded corners */
                width: 8in; /* Adjust width to 4 inches */
                height: 4in; /* Adjust height to 2 inches */
                position: absolute; /* Use absolute positioning */
                left: 46px; /* Position it 10 pixels from the left edge */
                bottom: -88px; /* Position it 10 pixels from the bottom edge */
                font-family: 'Helvetica', Arial, sans-serif;
            }

            .status-vehicle p {
                margin-top: 220px; /* Add space above the text if needed */
                margin-left: -260px;
            }

            .vehicle-id-back .center {
                position: relative; /* For positioning the h1 text */
                width: 100%;
                height: 100%;
                display: flex;
                overflow: hidden; /* Ensures content stays within the border */
                box-sizing: border-box; /* Includes padding in the element's total width and height */
            }

            .vehicle-id-back img {
                width: 50%; /* Each image takes up exactly half of the container width */
                height: 100%; /* Takes up the full height of the container */
                object-fit: fill; /* Stretches the image to fill the container */
                margin: 0; /* Remove default margin */
                padding: 0; /* Remove default padding */
                box-sizing: border-box; /* Includes padding in the element's total width and height */
                position: relative; /* To allow for positional adjustments */
                top: -35px;
            }

            .vehicle-id-back h1 {
                position: absolute; /* Position it over the images */
                top: 50%; /* Center vertically */
                left: 50%; /* Center horizontally */
                transform: translate(-50%, -50%); /* Center it perfectly */
                font-size: 15em; /* Increase size for a more prominent display */
                color: rgba(255, 255, 255, 1); /* Solid white text */
                font-family: 'Helvetica', Arial, sans-serif;
                font-weight: bold; /* Bold font */
                text-align: center; /* Center align the text */
                text-shadow: 
                    -5px -5px 0 rgba(0, 0, 0, 0.9),  
                    5px -5px 0 rgba(0, 0, 0, 0.9),
                    -5px  5px 0 rgba(0, 0, 0, 0.9),
                    5px  5px 0 rgba(0, 0, 0, 0.9); /* Thicker black stroke effect */
                width: 100%; /* Full width for proper centering */
                margin: 0; /* Remove default margin */
                padding: 0; /* Remove default padding */
                box-sizing: border-box; /* Includes padding in the element's total width and height */
                opacity: 0.75; /* Adjust text opacity */
                margin-top: -10px;
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
                                <img src="<?php echo $qr_code_url; ?>" alt="QR Code">
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
                    <div class="header-back">
                        <div class="header-back-text">
                            <img src="<?php echo $vehicle_img_front_path; ?>" alt="Vehicle Image">
                            <div>
                                <h1><?php echo $driver['formatted_id']; ?></h1>
                                <h3>Association:<br> <?php echo $association['association_name']; ?></h3>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                        <div class="owner-whose">
                            <p style="text-align: justify;">The owner whose picture and signature appear among each of the associations at this barangay. This driver's ID must always be worn all the time.</p>
                        </div>
                    </div>

                    <div class="emergency-details">
                        <p class="back-contact">In case of emergency, please contact:</p>
                        <p><strong><?php echo $driver['name_to_notify']; ?></strong></p>
                        <p><?php echo $driver['num_to_notify']; ?></p>
                        <p class="back-contact">If found, kindly please return the driver's ID to:</p>
                        <p>Barangay Estefania Treasurer's Office</p>
                        <p>(034) 492-2495</p>
                    </div>

                    <div class="column-back">
                        <div>
                            <hr class="signature-line-back">
                            <p style="font-size: 10pt;">Kagawad Eduardo Sayson</p>
                        </div>
                        <div>
                            <hr class="signature-line-back">
                            <p>Punong Barangay</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="page">
        <!-- Page 2: Vehicle ID Front -->
        <div class="vehicle-id-front">
            <div class="center">
                <div class="vehicle-front-header">
                    <img src="<?php echo $logo_path; ?>" alt="Logo" class="logo">
                    <div class="header-front-text">
                        <h1>BARANGAY ESTEFANIA</h1>
                        <p>Bacolod CITY, Fortune Towne</p>
                    </div>
                </div>
                
                
                <div class="main-vehicle-container">
                    <div class="info-column">
                        <h1><?php echo $driver['formatted_id']; ?></h1>
                        <h3>Association:<br> <?php echo $association['association_name']; ?></h3>
                        <p>The owner whose picture and signature appear among each of the associations at this barangay. This Vehicle's ID must always be affixed all the time.</p>
                    </div>

                    <div class="content-container">
                        <div class="column-vehicle-right">
                            <div class="qr-code-container">
                                <div class="qr-code-background"></div> <!-- Background image -->
                                <div class="qr-code">
                                    <img src="<?php echo $qr_code_url; ?>" alt="QR Code">
                                </div>
                            </div>
                            <hr class="signature-line-vehicle">
                            <p class="signature-text">Signature</p>
                        </div>
                        <div class="column-vehicle-right">
                            <img src="<?php echo $pic_2x2_path; ?>" alt="Driver Photo" style="width: 2in; height: 2in; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <div class="column-vehicle-right-bottom">
                    <?php 
                        // Format the name with middle initial
                        $middle_initial = !empty($driver['middle_name']) ? substr($driver['middle_name'], 0, 1) . '.' : ''; 
                        echo "{$driver['last_name']}, {$driver['first_name']} {$middle_initial} {$driver['suffix_name']}";
                    ?>
                    <br>
                    <?php echo "\"<i>{$driver['nickname']}</i>\" <br> {$driver['mobile_number']}"; ?>
                </div>



                <div class="status-vehicle">
                    <p>Valid Until: <?php echo date('Y', strtotime($driver['driver_registered'])); ?> as Official Driver</p>
                </div>

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
