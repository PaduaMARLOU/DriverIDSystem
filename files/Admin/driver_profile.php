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
$doc_proof_path = "../../" . $driver['doc_proof'];
$vehicle_img_front_path = "../../" . $vehicle['vehicle_img_front'];
$vehicle_img_back_path = "../../" . $vehicle['vehicle_img_back'];
$logo_path = "../../img/Brgy Estefania Logo.png"; // Replace with the path to your logo image
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Driver Profile</title>
    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            font-family: "Poppins", sans-serif;
        }

        .profile-container {
            max-width: 100%;
            margin: auto;
            margin-top: -80px;
            padding: 20px;
            border-radius: 10px;
            border: 0.5in solid transparent;
            /* Border for letter size paper */
            page-break-inside: avoid;
            /* Avoid breaking inside the container */
        }

        .profile-header {
            text-align: center;
            margin-bottom: -10px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            margin: 10px auto;
            border-radius: 5%;
            cursor: pointer;
            object-fit: cover;
        }

        .profile-img, .vehicle-img {
            box-shadow: 1px 1px 4px #1A1A1B;
        }

        .doc-img,
        .vehicle-img {
            width: auto;
            height: auto;
            max-width: 229.4px;
            max-height: 229.4px;
            margin: 5px auto;
            border-radius: 2%;
            cursor: pointer;
            object-fit: cover;
        }

        .profile-section {
            margin-bottom: 20px;
        }

        .profile-section h4 {
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: #007bff;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .row-one-column {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .row-two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .row-three-columns {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }

        .row-three-columns2 {
            display: grid;
            grid-template-columns: 1fr .98fr;
            gap: 10px;
        }

        .logo-container {
            display: none;
            /* Hide by default */
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            /* Space between buttons */
            padding-top: 10px;
            margin-bottom: 20px;
            /* Adjust as needed for spacing */
        }

        .btn {
            min-width: 150px;
            /* Ensure buttons have the same minimum width */
            height: 40px;
            /* Ensure buttons have the same height */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-generate-id {
            background-color: green;
            /* Green color for the button */
            border-color: green;
            /* Ensure border matches the button color */
        }

        .btn-generate-id:hover {
            background-color: darkgreen;
            /* Darker green on hover */
            border-color: darkgreen;
            /* Ensure border matches the button color on hover */
        }

        @media print {

            .btn-print,
            .btn-generate-id {
                display: none;
            }

            @page {
                size: letter;
                /* Set to letter size */
                margin: 0.5in;
                /* Border for letter size paper */
            }

            body {
                position: relative;
                margin: 0;
            }

            .logo-container {
                display: block;
                /* Show only when printing */
            }

            .logo {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: -1;
                /* Send behind content */
                width: 700px;
                /* Enlarged size */
                height: auto;
                opacity: 0.1;
                /* Lower opacity */
            }

            .profile-container {
                page-break-after: always;
            }

            .profile-container:last-child {
                page-break-after: auto;
            }

            .profile-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="logo-container">
        <img src="<?php echo $logo_path; ?>" alt="Logo" class="logo">
    </div>

    <div class="profile-container">
        <div class="profile-header">
            <br><h2>Driver Profile</h2>
            <img src="<?php echo $pic_2x2_path; ?>" alt="Driver Photo" class="profile-img" data-toggle="modal" data-target="#imageModal" data-image="<?php echo $pic_2x2_path; ?>">
        </div>

        <!-- Personal Information -->
        <div class="profile-section">
            <h4>Personal Information</h4>
            <div class="row-three-columns">
                <div>
                    <p><span class="info-label">Driver Category:</span> <?php echo $driver['driver_category']; ?></p>
                    <p><span class="info-label">Name:</span> <?php echo "{$driver['first_name']} {$driver['middle_name']} {$driver['last_name']} {$driver['suffix_name']}"; ?></p>
                    <p><span class="info-label">Birth Date:</span> <?php echo $driver['birth_date']; ?></p>

                </div>
                <div>
                    <p><span class="info-label">ID:</span> <?php echo $driver['formatted_id']; ?></p>
                    <p><span class="info-label">Nickname:</span> <?php echo $driver['nickname']; ?></p>
                    <p><span class="info-label">Birth Place:</span> <?php echo $driver['birth_place']; ?></p>
                </div>
                <div>
                    <p><span class="info-label">Mobile Number:</span> <?php echo $driver['mobile_number']; ?></p>
                    <p><span class="info-label">Sex:</span> <?php echo $driver['sex']; ?></p>
                    <p><span class="info-label">Age:</span> <?php echo $driver['age']; ?></p>
                </div>
            </div>
            <div class="row-one-column">
                <div>
                    <p><span class="info-label">Address:</span> <?php echo $driver['address']; ?></p>
                </div>
            </div>
        </div>

        <!-- Emergency Contact Information -->
        <div class="profile-section">
            <h4>Emergency Contact Information</h4>
            <div class="row-three-columns">
                <div>
                    <p><span class="info-label">Name to Notify:</span> <?php echo $driver['name_to_notify']; ?></p>
                </div>
                <div>
                    <p><span class="info-label">Relationship:</span> <?php echo $driver['relationship']; ?></p>
                </div>
                <div>
                    <p><span class="info-label">Number to Notify:</span> <?php echo $driver['num_to_notify']; ?></p>
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="profile-section">
            <h4>Vehicle Information</h4>
            <div class="row-two-columns">
                <div>
                    <p><span class="info-label">Vehicle Category:</span> <?php echo $vehicle['vehicle_category']; ?></p>
                    <p><span class="info-label">Vehicle Front Image:</span><br><img src="<?php echo $vehicle_img_front_path; ?>" alt="Vehicle Front Image" class="vehicle-img" data-toggle="modal" data-target="#imageModal" data-image="<?php echo $vehicle_img_front_path; ?>"></p>
                </div>
                <div>
                    <p><span class="info-label">Vehicle Registered:</span> <?php echo $vehicle['vehicle_registered']; ?></p>
                    <p><span class="info-label">Vehicle Back Image:</span><br><img src="<?php echo $vehicle_img_back_path; ?>" alt="Vehicle Back Image" class="vehicle-img" data-toggle="modal" data-target="#imageModal" data-image="<?php echo $vehicle_img_back_path; ?>"></p>
                </div>
            </div>
            <div class="row-three-columns2">
                <div>
                    <p><span class="info-label">Brand:</span> <?php echo $vehicle['brand']; ?></p>
                    <p><span class="info-label">Name of Owner:</span> <?php echo $vehicle['name_of_owner']; ?></p>
                    <p><span class="info-label">Address of Owner:</span> <?php echo $vehicle['addr_of_owner']; ?></p>
                </div>
                <div>
                    <p><span class="info-label">Plate Number:</span> <?php echo $vehicle['plate_num']; ?></p>
                    <p><span class="info-label">Vehicle Color:</span> <?php echo $vehicle['vehicle_color']; ?></p>
                    <p><span class="info-label">Owner Phone Number:</span> <?php echo $vehicle['owner_phone_num']; ?></p>
                </div>
            </div>
            <div>
            </div>

            <!-- Association Information -->
            <div class="profile-section"><br>
                <h4>Association Information</h4>
                <div class="row-two-columns">
                    <div>
                        <p><span class="info-label">Association Category:</span> <?php echo $association['association_category']; ?></p>
                        <p><span class="info-label">Association President:</span> <?php echo $association['association_president']; ?></p>
                    </div>
                    <div>
                        <p><span class="info-label">Association Name:</span> <?php echo $association['association_name']; ?></p>
                        <p><span class="info-label">Association Color:</span> <?php echo $association['association_color_name']; ?></p>
                    </div>
                </div>
                <div class="row-one=column">
                    <div>
                        <p><span class="info-label">Association Area:</span> <?php echo $association['association_area']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="button-container text-center">
            <button class="btn btn-primary btn-print" onclick="window.print()">Print Profile</button>
            <a href="ID_generation.php?id=<?php echo $driver['formatted_id']; ?>" class="btn btn-success btn-generate-id btn-sm btn-icon icon-left">
                <i class="entypo-vcard"></i>
                Generate Driver ID
            </a>
        </div>

        <!-- Modal for Image Enlargement -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="" id="modalImage" class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            $('#imageModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget)
                let imageSrc = button.data('image')
                let modal = $(this)
                modal.find('#modalImage').attr('src', imageSrc)
            })
        </script>
</body>
</html>