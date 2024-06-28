<?php

include 'connections.php';

if(isset($_GET['date'])){
    $date = $_GET['date'];
}

if(isset($_POST['submit'])){

    $driver_category = $_POST['driver_category'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix_name = $_POST['suffix_name'];
    $nickname = $_POST['nickname'];
    $birth_date = $_POST['birth_date'];
    $birth_place = $_POST['birth_place'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $civil_status = $_POST['civil_status'];
    $religion = $_POST['religion'];
    $citizenship = $_POST['citizenship'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $pic_2x2 = $_POST['pic_2x2'];
    $doc_proof = $_POST['doc_proof'];
    $name_to_notify = $_POST['name_to_notify'];
    $relationship = $_POST['relationship'];
    $num_to_notify = $_POST['num_to_notify'];
    $vehicle_ownership = $_POST['vehicle_ownership'];
    $name_of_owner = $_POST['name_of_owner'];
    $addr_of_owner = $_POST['addr_of_owner'];
    $owner_phone_num = $_POST['owner_phone_num'];
    $vehicle_color = $_POST['vehicle_color'];
    $brand = $_POST['brand'];
    $plate_num = $_POST['plate_num'];
    $vehicle_img_front = $_POST['vehicle_img_front'];
    $vehicle_img_back = $_POST['vehicle_img_back'];
    $association = $_POST['association'];

    //dates
    date_default_timezone_set('Asia/Manila');
    $birth_date = new DateTime($birth_date);
    $current_date = new DateTime();
    $current_date_str = $current_date->format('Y-m-d H:i:s');
    $age = $current_date->diff($birth_date)->y;
    $birth_date_str = $birth_date->format('Y-m-d');


    $sql_driver = "INSERT INTO tbl_driver(driver_category, first_name, middle_name, last_name, suffix_name, nickname, age, birth_date, birth_place, sex, address, mobile_number, civil_status, religion, citizenship, height, weight, pic_2x2, doc_proof, name_to_notify, relationship, num_to_notify, vehicle_ownership, verification_stat, fk_association_id) VALUES ('$driver_category', '$first_name', '$middle_name', '$last_name', '$suffix_name', '$nickname', '$age', '$birth_date_str', '$birth_place', '$sex', '$address', '$mobile_number', '$civil_status', '$religion', '$citizenship', '$height', '$weight', '$pic_2x2', '$doc_proof', '$name_to_notify', '$relationship', '$num_to_notify', '$vehicle_ownership', 'Pending', '$association')";

    if($connections->query($sql_driver)){
        $last_inserted_id = $connections->insert_id; // Get the last inserted ID

        // Now insert into tbl_appointment
        $sql_appointment = "INSERT INTO tbl_appointment(fk_driver_id, DATE, booking_date) VALUES ('$last_inserted_id', '$date', '$current_date_str')";

        if($connections->query($sql_appointment)){
            // Appointment insertion successful
            $sched_id = $connections->insert_id; // Get the last inserted ID

            // Update tbl_driver with sched_id
            $sql_update_sched_id = "UPDATE tbl_driver SET fk_sched_id = '$sched_id' WHERE driver_id = '$last_inserted_id'";
            if($connections->query($sql_update_sched_id)){
                // Formatting the driver category abbreviation based on its value
                switch ($driver_category) {
                    case 'E-Bike':
                        $formatted_category = 'ETRK';
                        break;
                    case 'Tricycle':
                        $formatted_category = 'TRCL';
                        break;
                    case 'Trisikad':
                        $formatted_category = 'TSKD';
                        break;
                    default:
                        $formatted_category = '';
                        break;
                }

                // Generate the formatted ID
                if (!empty($formatted_category)) {
                    $formatted_id = sprintf("%s-%04d", $formatted_category, $last_inserted_id);

                    // Update formatted ID in tbl_driver table
                    $sql_update_formatted_id = "UPDATE tbl_driver SET formatted_id = '$formatted_id' WHERE driver_id = $last_inserted_id";

                    if ($connections->query($sql_update_formatted_id)) {
                        // Insert into tbl_vehicle
                        $sql_insert_vehicle = "INSERT INTO tbl_vehicle(vehicle_category, fk_driver_id, name_of_owner, addr_of_owner, owner_phone_num, vehicle_color, brand, plate_num, vehicle_img_front, vehicle_img_back) VALUES ('$driver_category', '$last_inserted_id', '$name_of_owner', '$addr_of_owner', '$owner_phone_num', '$vehicle_color', '$brand', '$plate_num', '$vehicle_img_front', '$vehicle_img_back')";
                        if ($connections->query($sql_insert_vehicle)) {
                            // Get the ID of the last inserted vehicle
                            $last_vehicle_id = $connections->insert_id;

                            // Update tbl_driver with fk_vehicle_id
                            $sql_update_driver_fk_vehicle_id = "UPDATE tbl_driver SET fk_vehicle_id = '$last_vehicle_id' WHERE driver_id = '$last_inserted_id'";

                            if ($connections->query($sql_update_driver_fk_vehicle_id)) {
                                $message = "<div class='alert alert-success'>Booking Successful</div>";
                                header("Location: success.php?date=$date");
                                exit();
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
                // Failed to update tbl_driver with sched_id
                $message = "<div class='alert alert-danger'>Failed to update driver with sched_id</div>";
            }
        } else {
            // Appointment insertion failed
            $message = "<div class='alert alert-danger'>Failed to insert appointment</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Booking was not Successful</div>";
    }

    $connections->close(); // Close the database connection
}
?>








<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="icon" href="img/Brgy Estefania Logo.png" type="image/png">
    <!-- <link rel="stylesheet" href="/css/main.css"> -->
    <title>Online Booking System</title>
    <style>
        .faded-text {
            opacity: 0.5; /* Adjust the value between 0 and 1 as needed */
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
    </style>
</head>

<body>
    <div class="container">
        <div class="text-center alert" style="background:#2ecc71; border:none; color:#fff; display: flex; align-items: center; justify-content: center; padding: 10px; position: relative;">
            <a href="javascript:history.back()" style="left: 0; top: 0; text-decoration: none; background: #fff; color: #2ecc71; padding: 5px 10px; border-radius: 5px; margin-right: 10px;">Back to Choose Date</a>
            <img src="img/Brgy Estefania Logo.png" alt="Barangay Estefania Logo" style="height: 50px; margin-right: 10px;">
            <h1 style="margin: 0;">Book for Date: <?php echo date('m/d/Y', strtotime($date)) ?></h1>
        </div>

        <div class="instruction-box">
            <h4><strong>Pahanumdom: </strong>May duwa ka pamaagi para magsabat kag nakadepende ini sa pamangkutanon. Ang isa, pinduton ang box kag magpili sang sabat. Ang isa pa gid, pinduton kag i-type ang sabat sa box. Pagkatapos masabat tanan nga pamangkot, palihog pinduton ang <strong style="color: #337ab7;">"Submit"</strong> button nga ara sa idalom. Kung nagsala pili sang adlaw, pwede maclick ang <strong style="color: #2ecc71;">"Back to Choose Date"</strong> nga ara sa ibabaw.</h4>
            <h5><i>Palihog basahon ang mga nakabutang kag sabton sang klaro kag matuod-tuod ang mga salabton. Madamo gid nga Salamat.</i></h5>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <?php echo isset($message)?$message:'';?>
                <form action="" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="driver_category">Driver Category (E-Bike, Tricycle, or Trisikad): <i class="faded-text">*Magpili kung E-Bike, Tricycle, ukon Trisikad and imo ginamaneho*</i></label>
                    <select class="form-control" name="driver_category" id="driver_category" >
                        <option value="">Select Driver Category</option>
                        <option value="E-Bike">E-Bike</option>
                        <option value="Tricycle">Tricycle</option>
                        <option value="Trisikad">Trisikad</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="first_name">First Name: <i class="faded-text">*Una nga pangalan*</i></label>
                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" >
                </div>

                <div class="form-group">
                    <label for="middle_name">Middle Name: <i class="faded-text">*Ibutang ang apelyido sang imo iloy sang single pa siya. Kung waay ka middle name, pwede lang nga indi magbutang.*</i></label>
                    <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name: <i class="faded-text">*Apelyido*</i></label>
                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" >
                </div>

                <div class="form-group">
                    <label for="suffix_name">Suffix: <i class="faded-text">*Kung may Jr.(Junior), Sr.(Senior), III(The Third), IV(The Fourth), etc. diri ibutang, Example: Jr.*</i></label>
                    <input type="text" class="form-control" name="suffix_name" id="suffix_name" placeholder="Suffix">
                </div>

                <div class="form-group">
                    <label for="nickname">Nickname: <i class="faded-text">*Hayo mo. Example: Dodong, Boy Boy, Tagok, etc.*</i></label>
                    <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Nickname" >
                </div>

                <div class="form-group">
                    <label for="birth_date">Birth Date: <i class="faded-text">*Birthday mo ukon sang San-o ka gin-bata?*</i></label>
                    <input type="date" class="form-control" name="birth_date" id="birth_date" >
                </div>


                <div class="form-group">
                    <label for="birth_place">Birth Place (e.g., Bacolod City): <i class="faded-text">*Sa diin ka ginbata nga lugar?*</i></label>
                    <input type="text" class="form-control" name="birth_place" id="birth_place" placeholder="Birth Place" >
                </div>

                <div class="form-group">
                    <label for="sex">Sex (Male or Female): <i class="faded-text">*Halin sang sugod sang ginbata ka, ano imo kasarian?*</i></label>
                    <select class="form-control" name="sex" id="sex" >
                        <option value="">Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="address">Full Address: <i class="faded-text">*Kumpleto nga address. Example: Phase 1 Block 1 Lot 1, Katigbak Street, West Homes 1, Barangay Estefania, Bacolod City, 6100*</i></label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Address" >
                </div>


                <div class="form-group">
                    <label for="mobile_number">Mobile Number (e.g., 09123456789): <i class="faded-text">*Ang onse kabilog nga numero sang imo sim card nga ginagamit kag nagasugod sa 09...*</i></label>
                    <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Mobile Number" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" >
                </div>

                <div class="form-group">
                    <label for="civil_status">Civil Status (e.g., Single, Married, etc.): <i class="faded-text">*Kung Single ka kag waay sang asawa, Married kung kasal kag may asawa, may ka Live-In, Widowed kung balo, Seperated kung kasal kag nagbulagay, kag Divorced kung ang pagbulagay may papeles nga legal.*</i></label>
                    <select class="form-control" name="civil_status" id="civil_status" >
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
                    <label for="religion">Religion (e.g., Christian, Islam, Buddhism, etc.):  <i class="faded-text">*Imo nga Relihiyon. Kung waay, pwede lang indi pagbutangan.*</i></label>
                    <input type="text" class="form-control" name="religion" id="religion" placeholder="Religion">
                </div>

                <div class="form-group">
                    <label for="citizenship">Citizenship (e.g., Filipino): <i class="faded-text">*Lahi*</i></label>
                    <input type="text" class="form-control" name="citizenship" id="citizenship" placeholder="Citizenship" >
                </div>

                <div class="form-group">
                    <label for="height">Height (feet & inches): <i class="faded-text">*Kataason. Example 5 feet 4 inches, pwede mabutang 5'4"*</i></label>
                    <input type="text" class="form-control" name="height" id="height" placeholder="Height (feet & inches)" >
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg, e.g., 70kg): <i class="faded-text">*Pila imo kilo?*</i></label>
                    <input type="text" class="form-control" name="weight" id="weight" placeholder="Weight (kg)" >
                </div>


                <div class="form-group">
                    <label for="pic_2x2">Upload 2x2 Picture: <i class="faded-text">*I-click ang "Choose  File" para ka upload sang imo nga picture*</i></label>
                    <input type="file" class="form-control" name="pic_2x2" id="pic_2x2" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="doc_proof">Upload Proof of Document: <i class="faded-text">*I-click ang "Choose  File" para ka upload sang imo nga proof of documents parehas sang mga Government Valid ID nga Philippine ID, Senior ID, SSS ID, kag ibang pa nga documento parehas sang  Barangay Residency.*</i></label>
                    <input type="file" class="form-control" name="doc_proof" id="doc_proof">
                </div>

                <div class="form-group">
                    <label for="name_to_notify">Name of Person to Notify in case of Emergency: <i class="faded-text">*Kumpleto nga pangalan sang imo kilala nga pwede macontact kung may emerhensya*</i></label>
                    <input type="text" class="form-control" name="name_to_notify" id="name_to_notify" placeholder="Name to Notify" >
                </div>

                <div class="form-group">
                    <label for="relationship">Relationship (e.g., Father, Niece, Wife, etc.): <i class="faded-text">*Ano mo siya?*</i></label>
                    <input type="text" class="form-control" name="relationship" id="relationship" placeholder="Relationship" >
                </div>

                <div class="form-group">
                    <label for="num_to_notify">Number of Person to Notify in case of emergency (e.g., 09123456789): <i class="faded-text">*Ano iya  number?*</i></label>
                    <input type="text" class="form-control" name="num_to_notify" id="num_to_notify" placeholder="Number to Notify" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" >
                </div>

                <div class="form-group">
                    <label for="vehicle_ownership">Vehicle Ownership (Owned or Rented): <i class="faded-text">*Ikaw ang tag-iya ukon nagarenta sang imo nga ginabyahe?*</i></label>
                    <select class="form-control" name="vehicle_ownership" id="vehicle_ownership" >
                        <option value="">Select Vehicle Ownership</option>
                        <option value="Owned">Owned</option>
                        <option value="Rented">Rented</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name_of_owner">Name of Owner: <i class="faded-text">*Pangalan sang tag-iya*</i></label>
                    <input type="text" class="form-control" name="name_of_owner" id="name_of_owner" placeholder="Name of Owner" >
                </div>
                <div class="form-group">
                    <label for="addr_of_owner">Address of Owner: <i class="faded-text">*Address sang tag-iya*</i></label>
                    <input type="text" class="form-control" name="addr_of_owner" id="addr_of_owner" placeholder="Address of Owner" >
                </div>
                <div class="form-group">
                    <label for="owner_phone_num">Owner Phone Number: <i class="faded-text">*Contact number sang tag-iya*</i></label>
                    <input type="text" class="form-control" name="owner_phone_num" id="owner_phone_num" placeholder="Owner Phone Number" >
                </div>
                <div class="form-group">
                    <label for="vehicle_color">Vehicle Color: <i class="faded-text">*Ano nga color sang imo ginabyahe? Example: Green, Yellow, Red with Black, etc.*</i></label>
                    <input type="text" class="form-control" name="vehicle_color" id="vehicle_color" placeholder="Vehicle Color" >
                </div>
                <div class="form-group">
                    <label for="brand">Brand: <i class="faded-text">*Brand sang imo ginabyahe. Kung waay brand nga Trisikad ukon E-bike, pwede lang indi pagbutngan.*</i></label>
                    <input type="text" class="form-control" name="brand" id="brand" placeholder="Brand">
                </div>
                <div class="form-group">
                    <label for="plate_num">Plate Number: <i class="faded-text">*Plate number sang imo Tricycle. Kung E-Bike ukon Trisikad nga wala plate number, pwede lang indi pagbutngan.*</i></label>
                    <input type="text" class="form-control" name="plate_num" id="plate_num" placeholder="Plate Number">
                </div>
                <div class="form-group">
                    <label for="vehicle_img_front">Front Image of Vehicle: <i class="faded-text">*I-click ang "Choose  File" para ka upload sang atubang nga picture sang imo ginamaneho*</i></label>
                    <input type="file" class="form-control" name="vehicle_img_front" id="vehicle_img_front" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="vehicle_img_back">Front Image of Vehicle: <i class="faded-text">*I-click ang "Choose  File" para ka upload sang atubang nga picture sang imo ginamaneho*</i></label>
                    <input type="file" class="form-control" name="vehicle_img_back" id="vehicle_img_back" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="association">Select Association: <i class="faded-text">*Sa diin ka nga asosasyon gaintra?*</i></label>
                    <select class="form-control" name="association" id="association">
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



                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                <!-- <a href="index.php" class="btn btn-success">Back</a> -->
                <hr>
                <br>
            </form>


            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#vehicle_ownership').change(function(){
                var ownership = $(this).val();
                if(ownership === 'Owned'){
                    $('#name_of_owner').val($('#first_name').val() + ' ' + $('#middle_name').val() + ' ' + $('#last_name').val());
                    $('#addr_of_owner').val($('#address').val());
                    $('#owner_phone_num').val($('#mobile_number').val());
                } else {
                    $('#name_of_owner').val('');
                    $('#addr_of_owner').val('');
                    $('#owner_phone_num').val('');
                }
            });
            // Trigger change event on page load
            $('#vehicle_ownership').trigger('change');
        });
    </script>

</body>
</html>
