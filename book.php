<?php
if(isset($_GET['date'])){
    $date = $_GET['date'];
}

if(isset($_POST['submit'])){

    $driver_category = $_POST['driver_category'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
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
    $association = $_POST['association'];

    $birth_date = new DateTime($birth_date);
    $current_date = new DateTime();
    $age = $current_date->diff($birth_date)->y;
    $birth_date_str = $birth_date->format('Y-m-d');



    $conn = new mysqli('localhost', 'root', '', 'driver_id_system');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_driver = "INSERT INTO tbl_driver(driver_category, first_name, middle_name, last_name, nickname, age, birth_date, birth_place, sex, address, mobile_number, civil_status, religion, citizenship, height, weight, pic_2x2, doc_proof, name_to_notify, relationship, num_to_notify, vehicle_ownership, verification_stat, association) VALUES ('$driver_category', '$first_name', '$middle_name', '$last_name', '$nickname', '$age', '$birth_date_str', '$birth_place', '$sex', '$address', '$mobile_number', '$civil_status', '$religion', '$citizenship', '$height', '$weight', '$pic_2x2', '$doc_proof', '$name_to_notify', '$relationship', '$num_to_notify', '$vehicle_ownership', 'Pending', '$association')";


    if($conn->query($sql_driver)){
        $last_inserted_id = $conn->insert_id; // Get the last inserted ID

        // Now insert into tbl_appointment
        $sql_appointment = "INSERT INTO tbl_appointment(DATE, fk_driver_id) VALUES ('$date', '$last_inserted_id')";

        if($conn->query($sql_appointment)){
            // Appointment insertion successful
            $sched_id = $conn->insert_id; // Get the last inserted ID

            // Update tbl_driver with sched_id
            $sql_update_sched_id = "UPDATE tbl_driver SET fk_sched_id = '$sched_id' WHERE driver_id = '$last_inserted_id'";
            if($conn->query($sql_update_sched_id)){
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

                    if ($conn->query($sql_update_formatted_id)) {
                        // Insert into tbl_vehicle
                        $sql_insert_vehicle = "INSERT INTO tbl_vehicle(vehicle_category, fk_driver_id, name_of_owner, addr_of_owner, owner_phone_num, vehicle_color, brand, plate_num) VALUES ('$driver_category', '$last_inserted_id', '$name_of_owner', '$addr_of_owner', '$owner_phone_num', '$vehicle_color', '$brand', '$plate_num')";
                        if ($conn->query($sql_insert_vehicle)) {
                            // Get the ID of the last inserted vehicle
                            $last_vehicle_id = $conn->insert_id;

                            // Update tbl_driver with fk_vehicle_id
                            $sql_update_driver_fk_vehicle_id = "UPDATE tbl_driver SET fk_vehicle_id = '$last_vehicle_id' WHERE driver_id = '$last_inserted_id'";

                            if ($conn->query($sql_update_driver_fk_vehicle_id)) {
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

    $conn->close(); // Close the database connection
}
?>






<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="icon" href="img/Brgy Estefania Logo.png" type="image/png">
    <link rel="stylesheet" href="/css/main.css">
    <title>Online Booking System</title>
</head>

<body>
    <div class="container">
        <div class="text-center alert" style="background:#2ecc71; border:none; color:#fff; display: flex; align-items: center; justify-content: center; padding: 10px; position: relative;">
            <a href="javascript:history.back()" style="left: 0; top: 0; text-decoration: none; background: #fff; color: #2ecc71; padding: 5px 10px; border-radius: 5px; margin-right: 10px;">Back to Choose Date</a>
            <img src="img/Brgy Estefania Logo.png" alt="Barangay Estefania Logo" style="height: 50px; margin-right: 10px;">
            <h1 style="margin: 0;">Book for Date: <?php echo date('m/d/Y', strtotime($date)) ?></h1>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <?php echo isset($message)?$message:'';?>
                <form action="" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="driver_category">Driver Category (E-Bike, Tricycle, or Trisikad):</label>
                    <select class="form-control" name="driver_category" id="driver_category" required>
                        <option value="">Select Driver Category</option>
                        <option value="E-Bike">E-Bike</option>
                        <option value="Tricycle">Tricycle</option>
                        <option value="Trisikad">Trisikad</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required>
                </div>

                <div class="form-group">
                    <label for="middle_name">Middle Name:</label>
                    <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required>
                </div>

                <div class="form-group">
                    <label for="nickname">Nickname:</label>
                    <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Nickname" required>
                </div>

                <div class="form-group">
                    <label for="birth_date">Birth Date:</label>
                    <input type="date" class="form-control" name="birth_date" id="birth_date" required>
                </div>


                <div class="form-group">
                    <label for="birth_place">Birth Place (e.g., Bacolod City):</label>
                    <input type="text" class="form-control" name="birth_place" id="birth_place" placeholder="Birth Place" required>
                </div>

                <div class="form-group">
                    <label for="sex">Sex (Male or Female):</label>
                    <select class="form-control" name="sex" id="sex" required>
                        <option value="">Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="address">Full Address:</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Address" required>
                </div>


                <div class="form-group">
                    <label for="mobile_number">Mobile Number (e.g., 09123456789):</label>
                    <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Mobile Number" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" required>
                </div>

                <div class="form-group">
                    <label for="civil_status">Civil Status (e.g., Single, Married, etc.):</label>
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
                    <label for="religion">Religion (e.g., Christian, Islam, Buddhism, etc.):</label>
                    <input type="text" class="form-control" name="religion" id="religion" placeholder="Religion" required>
                </div>

                <div class="form-group">
                    <label for="citizenship">Citizenship (e.g., Filipino):</label>
                    <input type="text" class="form-control" name="citizenship" id="citizenship" placeholder="Citizenship" required>
                </div>

                <div class="form-group">
                    <label for="height">Height (cm, e.g., 170cm):</label>
                    <input type="text" class="form-control" name="height" id="height" placeholder="Height (cm)" required>
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg, e.g., 70kg):</label>
                    <input type="text" class="form-control" name="weight" id="weight" placeholder="Weight (kg)" required>
                </div>


                <div class="form-group">
                    <label for="pic_2x2">Upload 2x2 Picture:</label>
                    <input type="file" class="form-control" name="pic_2x2" id="pic_2x2" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="doc_proof">Upload Proof of Document:</label>
                    <input type="file" class="form-control" name="doc_proof" id="doc_proof">
                </div>

                <div class="form-group">
                    <label for="name_to_notify">Name of Person to Notify in case of Emergency:</label>
                    <input type="text" class="form-control" name="name_to_notify" id="name_to_notify" placeholder="Name to Notify" required>
                </div>

                <div class="form-group">
                    <label for="relationship">Relationship (e.g., Father, Niece, Wife, etc.):</label>
                    <input type="text" class="form-control" name="relationship" id="relationship" placeholder="Relationship" required>
                </div>

                <div class="form-group">
                    <label for="num_to_notify">Number of Person to Notify in case of emergency (e.g., 09123456789):</label>
                    <input type="text" class="form-control" name="num_to_notify" id="num_to_notify" placeholder="Number to Notify" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" requiredss>
                </div>

                <div class="form-group">
                    <label for="vehicle_ownership">Vehicle Ownership (Owned or Rented):</label>
                    <select class="form-control" name="vehicle_ownership" id="vehicle_ownership" required>
                        <option value="">Select Vehicle Ownership</option>
                        <option value="Owned">Owned</option>
                        <option value="Rented">Rented</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name_of_owner">Name of Owner:</label>
                    <input type="text" class="form-control" name="name_of_owner" id="name_of_owner" placeholder="Name of Owner" required>
                </div>
                <div class="form-group">
                    <label for="addr_of_owner">Address of Owner:</label>
                    <input type="text" class="form-control" name="addr_of_owner" id="addr_of_owner" placeholder="Address of Owner" required>
                </div>
                <div class="form-group">
                    <label for="owner_phone_num">Owner Phone Number:</label>
                    <input type="text" class="form-control" name="owner_phone_num" id="owner_phone_num" placeholder="Owner Phone Number" required>
                </div>
                <div class="form-group">
                    <label for="vehicle_color">Vehicle Color:</label>
                    <input type="text" class="form-control" name="vehicle_color" id="vehicle_color" placeholder="Vehicle Color" required>
                </div>
                <div class="form-group">
                    <label for="brand">Brand:</label>
                    <input type="text" class="form-control" name="brand" id="brand" placeholder="Brand" required>
                </div>
                <div class="form-group">
                    <label for="plate_num">Plate Number:</label>
                    <input type="text" class="form-control" name="plate_num" id="plate_num" placeholder="Plate Number">
                </div>

                <div class="form-group">
                    <label for="association">Select Association:</label>
                    <select class="form-control" name="association" id="association" required>
                        <option value="">Select Association</option>
                        <?php
                        include("connections.php");

                        $query = "SELECT association_name, association_area FROM tbl_association";
                        $result = mysqli_query($connections, $query);

                        $associations = [];
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $associations[] = $row;
                            }
                            foreach ($associations as $association): ?>
                                <option value="<?= $association['association_name'] ?>">
                                    <?= $association['association_name'] ?> - <?= $association['association_area'] ?>
                                </option>
                            <?php endforeach;
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
