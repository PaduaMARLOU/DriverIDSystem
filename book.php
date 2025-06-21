<?php
include 'connections.php';

$message = '';

// Initialize an associative array for vehicle names and their formats
$vehicleOptions = [];

// Query the database for set_vehicleName and set_vehicleFormat under the category 'vehicle'
$query = "SELECT set_vehicleName, set_vehicleFormat FROM tbl_set WHERE set_category = 'vehicle'";
$result = mysqli_query($connections, $query);

// Check if the query was successful and fetch results into the $vehicleOptions array
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $vehicleOptions[$row['set_vehicleName']] = $row['set_vehicleFormat']; // Use name as key, format as value
    }
}

if (isset($_GET['date'])) {
    $date = mysqli_real_escape_string($connections, $_GET['date']);
}

if (isset($_POST['submit'])) {
    $driver_category = mysqli_real_escape_string($connections, $_POST['driver_category']);
    $first_name = mysqli_real_escape_string($connections, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($connections, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($connections, $_POST['last_name']);
    $suffix_name = mysqli_real_escape_string($connections, $_POST['suffix_name']);
    $nickname = mysqli_real_escape_string($connections, $_POST['nickname']);
    $birth_date = mysqli_real_escape_string($connections, $_POST['birth_date']);
    $birth_place = mysqli_real_escape_string($connections, $_POST['birth_place']);
    $sex = mysqli_real_escape_string($connections, $_POST['sex']);
    $address = mysqli_real_escape_string($connections, $_POST['address']);
    $mobile_number = mysqli_real_escape_string($connections, $_POST['mobile_number']);
    $civil_status = mysqli_real_escape_string($connections, $_POST['civil_status']);
    $name_to_notify = mysqli_real_escape_string($connections, $_POST['name_to_notify']);
    $relationship = mysqli_real_escape_string($connections, $_POST['relationship']);
    $num_to_notify = mysqli_real_escape_string($connections, $_POST['num_to_notify']);
    $vehicle_ownership = mysqli_real_escape_string($connections, $_POST['vehicle_ownership']);
    $name_of_owner = mysqli_real_escape_string($connections, $_POST['name_of_owner']);
    $addr_of_owner = mysqli_real_escape_string($connections, $_POST['addr_of_owner']);
    $owner_phone_num = mysqli_real_escape_string($connections, $_POST['owner_phone_num']);
    $association = mysqli_real_escape_string($connections, $_POST['association']);

    // Handle dates
    date_default_timezone_set('Asia/Manila');
    $birth_date_obj = new DateTime($birth_date);
    $current_date = new DateTime();
    $current_date_str = $current_date->format('Y-m-d H:i:s');
    $age = $current_date->diff($birth_date_obj)->y;
    $birth_date_str = $birth_date_obj->format('Y-m-d');

    // Check if driver already exists 
    $query = "SELECT COUNT(*) AS count 
            FROM tbl_driver 
            WHERE first_name = '$first_name' 
                AND middle_name = '$middle_name' 
                AND last_name = '$last_name'";
    $result = mysqli_query($connections, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];


    if ($row['count'] > 0) {
        $message = "<center style='color: red; font-size: 2.8rem;'>This name is already taken!<br>
            If you are sure that this is a new registration, please visit the Barangay Hall for further assistance.</center><br>";

    } else {
        // Prepare and execute SQL queries
        $sql_driver = "INSERT INTO tbl_driver(driver_category, first_name, middle_name, last_name, suffix_name, nickname, age, birth_date, birth_place, 
                        sex, address, mobile_number, civil_status, name_to_notify, relationship, num_to_notify, vehicle_ownership, verification_stat, fk_association_id) VALUES ('$driver_category', '$first_name', '$middle_name', '$last_name', '$suffix_name', '$nickname', '$age', '$birth_date_str', '$birth_place', '$sex', '$address', '$mobile_number', '$civil_status', '$name_to_notify', '$relationship', '$num_to_notify', '$vehicle_ownership', 'Pending', '$association')";

        if ($connections->query($sql_driver)) {
            $last_inserted_id = $connections->insert_id;

            // Insert into tbl_appointment
            $sql_appointment = "INSERT INTO tbl_appointment(fk_driver_id, appointment_date, booking_date) VALUES ('$last_inserted_id', '$date', '$current_date_str')";

            if ($connections->query($sql_appointment)) {
                $sched_id = $connections->insert_id;

                // Update tbl_driver with sched_id
                $sql_update_sched_id = "UPDATE tbl_driver SET fk_sched_id = '$sched_id' WHERE driver_id = '$last_inserted_id'";
                if ($connections->query($sql_update_sched_id)) {

                    $formatted_category = $vehicleOptions[$driver_category] ?? ''; // Default to an empty string if not found

                    // Generate formatted ID
                    if (!empty($formatted_category)) {
                        $formatted_id = sprintf("%s-%04d", $formatted_category, $last_inserted_id);

                        // Update formatted ID in tbl_driver
                        $sql_update_formatted_id = "UPDATE tbl_driver SET formatted_id = '$formatted_id' WHERE driver_id = $last_inserted_id";

                        if ($connections->query($sql_update_formatted_id)) {

                            // Insert into tbl_vehicle
                            $sql_insert_vehicle = "INSERT INTO tbl_vehicle(vehicle_category, fk_driver_id, name_of_owner, addr_of_owner) VALUES ('$driver_category', '$last_inserted_id', '$name_of_owner', '$addr_of_owner')";

                            if ($connections->query($sql_insert_vehicle)) {
                                $last_vehicle_id = $connections->insert_id;

                                // Update tbl_driver with fk_vehicle_id
                                $sql_update_driver_fk_vehicle_id = "UPDATE tbl_driver SET fk_vehicle_id = '$last_vehicle_id' WHERE driver_id = '$last_inserted_id'";
                                if ($connections->query($sql_update_driver_fk_vehicle_id)) {

                                    // Everything else is successful, proceed with file uploads

                                    // File upload handling
                                    $uploadDir = 'uploads/';
                                    $driversDir = $uploadDir . 'drivers/';
                                    $documentsDir = $uploadDir . 'documents/';
                                    $vehiclesDir = $uploadDir . 'vehicles/';

                                    // Ensure directories exist or create them
                                    if (!is_dir($driversDir)) {
                                        mkdir($driversDir, 0755, true);
                                    }
                                    if (!is_dir($documentsDir)) {
                                        mkdir($documentsDir, 0755, true);
                                    }
                                    if (!is_dir($vehiclesDir)) {
                                        mkdir($vehiclesDir, 0755, true);
                                    }

                                    // Function to handle file upload and return path
                                    function handleFileUpload($file, $targetDir, $formatted_id) {
                                        $fileName = uniqid() . '_' . $formatted_id . '_' . basename($file['name']);
                                        $targetPath = $targetDir . $fileName;
                                        
                                        if ($file['error'] === UPLOAD_ERR_OK) {
                                            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                                                return $targetPath;
                                            } else {
                                                return false;
                                            }
                                        } else {
                                            // Log or output error message here based on $file['error']
                                            return false;
                                        }
                                    }

                                    // Upload 2x2 picture
                                    if (isset($_FILES['pic_2x2']) && $_FILES['pic_2x2']['error'] == UPLOAD_ERR_OK) {
                                        $pic_2x2_path = handleFileUpload($_FILES['pic_2x2'], $driversDir, $formatted_id);
                                    } else {
                                        $pic_2x2_path = false;
                                    }                                    

                                    // Upload vehicle front image
                                    $vehicle_img_front_path = handleFileUpload($_FILES['vehicle_img_front'], $vehiclesDir, $formatted_id);

                                    // Check if all uploads were successful
                                    if ($pic_2x2_path && $vehicle_img_front_path) {

                                        // Update tbl_driver with file paths
                                        $sql_update_driver_files = "UPDATE tbl_driver SET pic_2x2 = '$pic_2x2_path' WHERE driver_id = '$last_inserted_id'";
                                        if ($connections->query($sql_update_driver_files)) {

                                            // Insert into tbl_vehicle with vehicle images
                                            $sql_update_vehicle_images = "UPDATE tbl_vehicle SET vehicle_img_front = '$vehicle_img_front_path' WHERE vehicle_id = '$last_vehicle_id'";
                                            if ($connections->query($sql_update_vehicle_images)) {

                                                $message = "<div class='alert alert-success'>Booking Successful</div>";
                                                header("Location: success.php?date=$date");
                                                exit();
                                            } else {
                                                $message = "<div class='alert alert-danger'>Failed to update vehicle images</div>";
                                            }
                                        } else {
                                            if (!$connections->query($sql_update_driver_files)) {
                                                $message = "<div class='alert alert-danger'>Failed to update driver with file paths: " . mysqli_error($connections) . "</div>";
                                            }
                                        }
                                    } else {
                                        $message = "<div class='alert alert-danger'>Failed to upload files</div>";
                                    }
                                } else {
                                    $message = "<div class='alert alert-danger'>Failed to update driver with vehicle ID</div>";
                                }
                            } else {
                                $message = "<div class='alert alert-danger'>Failed to insert into tbl_vehicle</div>";
                            }
                        } else {
                            $message = "<div class='alert alert-danger'>Failed to update formatted ID</div>";
                        }
                    } else {
                        $message = "<div class='alert alert-danger'>Invalid driver category</div>";
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Failed to update driver with sched ID</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Failed to insert into tbl_appointment</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Failed to insert into tbl_driver</div>";
        }
    }
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="icon" href="img/Brgy. Estefania Logo (Old).png" type="image/png">
    <!-- <link rel="stylesheet" href="/css/main.css"> -->
    <title>Online Booking System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@325&display=swap');

        * {
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            outline: none;
        }

        :root {
            --comfort-green: #3bc356;
            --comfort-black: rgb(50, 45, 45);
            --comfort-blue: rgb(64, 138, 242);
            --comfort-red: rgba(247, 67, 67, 0.934);
            --comfort-shadow: rgb(183, 182, 182);
        }

        .container2 {
            width: 50%;
            /* Adjust this as needed */
            border: 1.8px solid black;
            border-radius: 5px;
            box-shadow: 1px 1px 5px #C2C1C0;
            margin: 0 auto;
            /* This centers the div horizontally */
            padding-top: 2rem;
            padding-left: 2rem;
            padding-bottom: 2.5rem;
        }

        .faded-text {
            opacity: 0.5;
            /* Adjust the value between 0 and 1 as needed */
        }

        .instruction-box {
            border: 2px solid #000;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: justify;
        }

        .form-group {
            font-size: 1.70rem;
        }

        i {
            font-size: .98em !important;
        }

        #submit {
            position: relative;
            right: -10px;
            top: 10px;
            width: 90%;
            margin: 0 auto;
            font-size: 2rem;
            transition: .25s;
            outline: none;
        }

        #submit:active {
            transform: scale(.9);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="text-center alert" style="background:#2ecc71; border:none; color:#fff; display: flex; align-items: center; justify-content: center; padding: 10px; position: relative;">
            <a href="javascript:history.back()" style="left: 0; top: 0; text-decoration: none; background: #fff; color: #2ecc71; padding: 5px 10px; border-radius: 5px; margin-right: 10px;">Back to Choose Date</a>
            <img src="img/Brgy. Estefania Logo (Old).png" alt="Barangay Estefania Logo" style="height: 75px; margin-right: 10px;">
            <h1 style="margin: 0;">Book for Date: <?php echo date('m/d/Y', strtotime($date)) ?></h1>
        </div>

        <div class="instruction-box">
            <h4><strong>Pahanumdom: </strong>May duwa ka pamaagi para magsabat kag nakadepende ini sa pamangkutanon. Ang isa, pinduton ang box kag magpili sang sabat. Ang isa pa gid, pinduton kag i-type ang sabat sa box. Pagkatapos masabat tanan nga pamangkot, palihog pinduton ang <strong style="color: #337ab7;">"Submit"</strong> button nga ara sa idalom. Kung nagsala pili sang adlaw, pwede maclick ang <strong style="color: #2ecc71;">"Back to Choose Date"</strong> nga ara sa ibabaw.</h4>
            <h5><i>Palihog basahon ang mga nakabutang kag sabton sang klaro kag matuod-tuod ang mga salabton. Madamo gid nga Salamat.</i></h5>
            <div id="warning-message">
                <p style="color: red; font-weight: bold;">
                    Pahibalo: Indi pag-i-refresh ukon i-click ang ⟳ (reload icon) sa babaw kay madula tanan mo nga napamutang.
                </p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <?php echo isset($message) ? $message : ''; ?>
                <form id="myForm" action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                    
                    <!-- First Part: Driver Category to Nickname -->
                    <div class="form-section active">
                        <div class="form-group">
                            <label for="driver_category">Driver Category (E-Bike, Tricycle, or Trisikad): 
                                <i class="faded-text">*Magpili kung E-Bike, Tricycle, ukon Trisikad and imo ginamaneho*</i>
                            </label>
                            <select class="form-control" name="driver_category" id="driver_category" required>
                                <option value="">Select Driver Category</option>
                                <?php
                                // Populate the dropdown options
                                foreach ($vehicleOptions as $vehicleName => $vehicleFormat) {
                                    echo "<option value=\"$vehicleName\">$vehicleName</option>";
                                }
                                ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="first_name">First Name: <i class="faded-text">*Una nga ngalan*</i></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required>
                        </div>

                        <div class="form-group">
                            <label for="middle_name">Middle Name: <i class="faded-text">*Ibutang ang apelyido sang imo iloy sang dalaga pa siya. Kung wala ka middle name, pwede nga indi lang magbutang.*</i></label>
                            <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name">
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name: <i class="faded-text">*Apelyido*</i></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required>
                        </div>

                        <div class="form-group">
                            <label for="suffix_name">Suffix: <i class="faded-text">*Kung may Jr.(Junior), Sr.(Senior), III(The Third), IV(The Fourth), etc. diri ibutang, Example: Jr.*</i></label>
                            <input type="text" class="form-control" name="suffix_name" id="suffix_name" placeholder="Suffix">
                        </div>

                        <div class="form-group">
                            <label for="nickname">Nickname: <i class="faded-text">*Hayo mo. Example: Dodong, Boy Boy, Pogs, etc.*</i></label>
                            <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Nickname" required>
                        </div>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                        <hr>
                    </div>

                    <!-- Second Part: Birth Date to Upload 2x2 picture -->
                    <div class="form-section">
                        <div class="form-group">
                            <label for="birth_date">Birth Date: <i class="faded-text">*Birthday mo ukon sang san-o ka ginbun-ag?*</i></label>
                            <input type="date" class="form-control" name="birth_date" id="birth_date" required>
                        </div>


                        <div class="form-group">
                            <label for="birth_place">Birth Place (e.g., Bacolod City): <i class="faded-text">*Sa diin ka ginbun-ag nga lugar?*</i></label>
                            <input type="text" class="form-control" name="birth_place" id="birth_place" placeholder="Birth Place" required>
                        </div>

                        <div class="form-group">
                            <label for="sex">Gender <i class="faded-text">*Halin sang pagkabun-ag sa imo, ano ang imo kasarian?*</i></label>
                            <select class="form-control" name="sex" id="sex" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="address">Full Address: <i class="faded-text">*Kumpleto nga address sang imo puluy-an. Example: Phase 1 Block 1 Lot 1, Katigbak Street, West Homes 1, Barangay Estefania, Bacolod City, 6100*</i></label>
                            <input type="text" class="form-control" name="address" id="address" placeholder="Address" required>
                        </div>


                        <div class="form-group">
                            <label for="mobile_number">Mobile Number (e.g., 09123456789): <i class="faded-text">*Ang onse kabilog nga numero sang imo sim card nga ginagamit kag nagasugod sa 09...*</i></label>
                            <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Mobile Number" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" required>
                        </div>

                        <div class="form-group">
                            <label for="civil_status">Civil Status (e.g., Single, Married, etc.): <i class="faded-text">*Kung Single ka kag waay sang asawa, Married kung kasal kag may asawa, may ka Live-In, Widowed kung balo, Seperated kung kasal kag nagbulagay, kag Divorced kung ang pagbulagay may papeles nga legal.*</i></label>
                            <select class="form-control" name="civil_status" id="civil_status" required>
                                <option value="">Select Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Live-In">Live-In</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Separated">Separated</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="pic_2x2">Upload 2x2 Picture: <i class="faded-text">*I-click ang "Choose File" para ka upload sang imo nga picture*</i></label>
                            <input type="file" class="form-control" name="pic_2x2" id="pic_2x2" accept="image/*" required>
                        </div>
                        <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                        <hr>
                    </div>

                    <!-- Third Part: Name to Notify to Num to Notify -->
                    <div class="form-section">
                        <div class="form-group">
                            <label for="name_to_notify">Name of Person to Notify in case of Emergency: <i class="faded-text">*Kumpleto nga pangalan sang imo kilala nga pwede makontak kung may emerhensya*</i></label>
                            <input type="text" class="form-control" name="name_to_notify" id="name_to_notify" placeholder="Name to Notify" required>
                        </div>

                        <div class="form-group">
                            <label for="relationship">Relationship (e.g., Father, Niece, Wife, etc.): <i class="faded-text">*Ano imo kaangtanan sa iya?*</i></label>
                            <input type="text" class="form-control" name="relationship" id="relationship" placeholder="Relationship" required>
                        </div>

                        <div class="form-group">
                            <label for="num_to_notify">Number of Person to Notify in case of emergency (e.g., 09123456789): <i class="faded-text">*Iya nga numero nga pwede kontakon kung may emerhensya?*</i></label>
                            <input type="text" class="form-control" name="num_to_notify" id="num_to_notify" placeholder="Number to Notify" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" required>
                        </div>
                        <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                        <button type="button" class="btn btn-primary next-btn">Next</button>
                        <hr>
                    </div>

                    <!-- Last Part: Vehicle Ownership to Association -->
                    <div class="form-section">
                        <div class="form-group">
                            <label for="vehicle_ownership">Vehicle Ownership (Owned or Rented): <i class="faded-text">*Ikaw ang tag-iya ukon nagarenta ka bala sang imo nga ginabyahe?*</i></label>
                            <select class="form-control" name="vehicle_ownership" id="vehicle_ownership" required>
                                <option value="">Select Vehicle Ownership</option>
                                <option value="Owned">Owned</option>
                                <option value="Rented">Rented</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name_of_owner">Name of Owner: <i class="faded-text">*Pangalan sang tag-iya*</i></label>
                            <input type="text" class="form-control" name="name_of_owner" id="name_of_owner" placeholder="Name of Owner" required>
                        </div>
                        <div class="form-group">
                            <label for="addr_of_owner">Address of Owner: <i class="faded-text">*Puluy-an sang tag-iya*</i></label>
                            <input type="text" class="form-control" name="addr_of_owner" id="addr_of_owner" placeholder="Address of Owner" required>
                        </div>
                        <div class="form-group">
                            <label for="owner_phone_num">Owner Phone Number: <i class="faded-text">*Contact number sang tag-iya*</i></label>
                            <input type="text" class="form-control" name="owner_phone_num" id="owner_phone_num" placeholder="Owner Phone Number" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_img_front">Front Image of Vehicle: <i class="faded-text">*I-click ang 'Choose File' agud maka-upload sang atubang nga litrato sang imo ginamaneho.*</i></label>
                            <input type="file" class="form-control" name="vehicle_img_front" id="vehicle_img_front" accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label for="association">Select Association: <i class="faded-text">*Sa diin nga asosasyon ka gaintra?*</i></label>
                            <select class="form-control" name="association" id="association" required>
                                <option value="">Select Association</option>
                                <?php
                                include("connections.php");

                                $query = "SELECT association_id, association_name, association_area FROM tbl_association";
                                $result = mysqli_query($connections, $query);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                        <option value="<?= $row['association_id'] ?>">
                                            <?= $row['association_name'] ?> - <?= $row['association_area'] ?>
                                        </option>
                                <?php
                                    }
                                } else {
                                    echo "<option disabled>No associations found</option>";
                                }

                                mysqli_close($connections);
                                ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                        <button type="submit" name="submit" class="btn btn-primary next-btn">Submit</button>
                        <hr>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            var currentSection = 0;
            var sections = $(".form-section");
            sections.hide();
            $(sections[0]).show();

            $(".next-btn").click(function () {
                // Validate all required fields in the current section
                let isValid = true;
                $(sections[currentSection]).find("input[required], select[required], textarea[required]").each(function () {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass("error"); // Add error styling if needed
                    } else {
                        $(this).removeClass("error");
                    }
                });

                // Only go to the next section if validation is successful
                if (isValid) {
                    if (currentSection < sections.length - 1) {
                        $(sections[currentSection]).hide();
                        currentSection++;
                        $(sections[currentSection]).show();
                    } else {
                        // If it's the last section, show confirmation before submitting
                        if (confirm("Are you sure about your details?")) {
                            window.onbeforeunload = null; // Disable the prompt
                            $("#myForm").submit(); // Submit the form if the user confirms
                        }
                    }
                } else {
                    alert("Please fill out all required fields before proceeding.");
                }
            });

            $(".prev-btn").click(function () {
                if (currentSection > 0) {
                    $(sections[currentSection]).hide();
                    currentSection--;
                    $(sections[currentSection]).show();
                }
            });

            // Vehicle ownership logic
            $('#vehicle_ownership').change(function () {
                let ownership = $(this).val();

                if (ownership === 'Owned') {
                    $('#name_of_owner').val($('#first_name').val() + ' ' + $('#middle_name').val() + ' ' + $('#last_name').val());
                    $('#addr_of_owner').val($('#address').val());
                    $('#owner_phone_num').val($('#mobile_number').val());
                } else {
                    $('#name_of_owner').val('');
                    $('#addr_of_owner').val('');
                    $('#owner_phone_num').val('');
                }
            });

            // Trigger vehicle ownership change event on page load
            $('#vehicle_ownership').trigger('change');

            // Prompt confirmation on page refresh or close
            window.onbeforeunload = function () {
                return "Changes you made may not be saved.";
            };
        });
    </script>

</body>
</html>
