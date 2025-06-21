<?php

session_start();

include("../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if ($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}


// Initialize an associative array for vehicle categories
$vehicleOptions = [];

// Query the database for set_vehicleName under the category 'vehicle'
$query = "SELECT set_vehicleName FROM tbl_set WHERE set_category = 'vehicle'";
$result = mysqli_query($connections, $query);

// Check if the query was successful and fetch results into the $vehicleOptions array
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $vehicleOptions[] = htmlspecialchars($row['set_vehicleName']); // Only store the name
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="icon" href="../../img/writting-pencil-design.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            outline: none;
        }

        .container {
            position: relative;
            margin-right: 445px;
            width: 29%;
            border: 1.5px solid #707274;
            padding-left: 2em;
            padding-right: 2em;
            padding-bottom: 2em;
            border-radius: 5px;
            box-shadow: 1px 1px 5px #AAADB5;
        }

        ion-icon {
            position: absolute;
            font-size: 40px;
            top: 20px;
            left: 20px;
            color: #2CAEE2;
            transition: .2s;
        }

        ion-icon:hover {
            color: #31A0CC;
        }

        ion-icon:active {
            transform: scale(.9);
        }

        button {
            transition: .2s;
        }
        
        button:active {
            transform: scale(.9);
        }

    </style>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#driver_category').change(function() {
                if ($(this).val()) {
                    $('#pic_2x2').attr('required', 'required');
                    $('#doc_proof').attr('required', 'required');
                    $('#vehicle_img_front').attr('required', 'required');
                    $('#vehicle_img_back').attr('required', 'required');
                } else {
                    $('#pic_2x2').removeAttr('required');
                    $('#doc_proof').removeAttr('required');
                    $('#vehicle_img_front').removeAttr('required');
                    $('#vehicle_img_back').removeAttr('required');
                }
            });
        });
    </script>
</head>

<body>
    <br>
    <div class="container">
        <a href="driver.php"><ion-icon name="arrow-back-circle"></ion-icon></a>
        <center>
            <h2>Edit Record</h2><br>
        </center>
        <form action="update_driver_success.php" method="POST" enctype="multipart/form-data">
            <?php
            // Include database connection
            include("../../connections.php");

            // Check if formatted_id is provided in the URL
            if (isset($_GET['formatted_id'])) {
                // Retrieve the formatted_id from the URL
                $formatted_id = $_GET['formatted_id'];

                // SQL query to retrieve driver data based on formatted_id
                $driver_sql = "SELECT * FROM tbl_driver WHERE formatted_id = '$formatted_id'";
                $driver_result = $connections->query($driver_sql);

                // SQL query to retrieve vehicle data based on fk_driver_id
                $vehicle_sql = "SELECT * FROM tbl_vehicle WHERE fk_driver_id IN (SELECT driver_id FROM tbl_driver WHERE formatted_id = '$formatted_id')";
                $vehicle_result = $connections->query($vehicle_sql);

                // Check if the driver record exists
                if ($driver_result->num_rows > 0 && $vehicle_result->num_rows > 0) {
                    // Fetch the driver record as an associative array
                    $driver_row = $driver_result->fetch_assoc();
                    // Fetch the vehicle record as an associative array
                    $vehicle_row = $vehicle_result->fetch_assoc();

                    // Populate driver fields
            ?>
                    <input type="hidden" name="formatted_id" value="<?php echo $driver_row['formatted_id']; ?>">
                    <div class="form-group">
                        <label for="driver_category">Driver Category (E-Bike, Tricycle, or Trisikad):</label>
                        <select class="form-control" name="driver_category" id="driver_category" required>
                            <option value="">Select Driver Category</option>
                            <?php
                            // Populate the dropdown options and preselect the current category
                            foreach ($vehicleOptions as $vehicleName) {
                                // Check if the current option matches the driver's category
                                $selected = ($driver_row['driver_category'] === $vehicleName) ? 'selected' : '';
                                echo "<option value=\"$vehicleName\" $selected>$vehicleName</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required value="<?php echo $driver_row['first_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="middle_name">Middle Name:</label>
                        <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name" required value="<?php echo $driver_row['middle_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required value="<?php echo $driver_row['last_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="suffix_name">Suffix Name:</label>
                        <input type="text" class="form-control" name="suffix_name" id="suffix_name" placeholder="Suffix Name" value="<?php echo $driver_row['suffix_name']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="nickname">Nickname:</label>
                        <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Nickname" required value="<?php echo $driver_row['nickname']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="birth_date">Birth Date:</label>
                        <input type="date" class="form-control" name="birth_date" id="birth_date" required value="<?php echo $driver_row['birth_date']; ?>">
                    </div>


                    <div class="form-group">
                        <label for="birth_place">Birth Place (e.g., Bacolod City):</label>
                        <input type="text" class="form-control" name="birth_place" id="birth_place" placeholder="Birth Place" required value="<?php echo $driver_row['birth_place']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="sex">Gender:</label>
                        <select class="form-control" name="sex" id="sex" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php if ($driver_row['sex'] === 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($driver_row['sex'] === 'Female') echo 'selected'; ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address">Full Address:</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="Address" required value="<?php echo $driver_row['address']; ?>">
                    </div>


                    <div class="form-group">
                        <label for="mobile_number">Mobile Number (e.g., 09123456789):</label>
                        <input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Mobile Number" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" required value="<?php echo $driver_row['mobile_number']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="civil_status">Civil Status (e.g., Single, Married, etc.):</label>
                        <select class="form-control" name="civil_status" id="civil_status" required>
                            <option value="">Select Civil Status</option>
                            <option value="Single" <?php if ($driver_row['civil_status'] === 'Single') echo 'selected'; ?>>Single</option>
                            <option value="Married" <?php if ($driver_row['civil_status'] === 'Married') echo 'selected'; ?>>Married</option>
                            <option value="Live-In" <?php if ($driver_row['civil_status'] === 'Live-In') echo 'selected'; ?>>Live-In</option>
                            <option value="Widowed" <?php if ($driver_row['civil_status'] === 'Widowed') echo 'selected'; ?>>Widowed</option>
                            <option value="Separated" <?php if ($driver_row['civil_status'] === 'Separated') echo 'selected'; ?>>Separated</option>
                            <option value="Divorced" <?php if ($driver_row['civil_status'] === 'Divorced') echo 'selected'; ?>>Divorced</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pic_2x2">Upload 2x2 Picture:</label>
                        <input type="file" class="form-control" name="pic_2x2" id="pic_2x2" accept="image/*">
                        <?php if (!empty($driver_row['pic_2x2'])) : ?>
                            <p>Current File: <?php echo $driver_row['pic_2x2']; ?></p>
                            <input type="hidden" name="current_pic_2x2" value="<?php echo $driver_row['pic_2x2']; ?>">
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="name_to_notify">Name of Person to Notify in case of Emergency:</label>
                        <input type="text" class="form-control" name="name_to_notify" id="name_to_notify" placeholder="Name to Notify" required value="<?php echo $driver_row['name_to_notify']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="relationship">Relationship (e.g., Father, Niece, Wife, etc.):</label>
                        <input type="text" class="form-control" name="relationship" id="relationship" placeholder="Relationship" required value="<?php echo $driver_row['relationship']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="num_to_notify">Number of Person to Notify in case of emergency (e.g., 09123456789):</label>
                        <input type="text" class="form-control" name="num_to_notify" id="num_to_notify" placeholder="Number to Notify" maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number" required value="<?php echo $driver_row['num_to_notify']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="vehicle_ownership">Vehicle Ownership (Owned or Rented):</label>
                        <select class="form-control" name="vehicle_ownership" id="vehicle_ownership" required>
                            <option value="">Select Vehicle Ownership</option>
                            <option value="Owned" <?php if ($driver_row['vehicle_ownership'] === 'Owned') echo 'selected'; ?>>Owned</option>
                            <option value="Rented" <?php if ($driver_row['vehicle_ownership'] === 'Rented') echo 'selected'; ?>>Rented</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name_of_owner">Name of Owner:</label>
                        <input type="text" class="form-control" name="name_of_owner" id="name_of_owner" placeholder="Name of Owner" required value="<?php echo $vehicle_row['name_of_owner']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="addr_of_owner">Address of Owner:</label>
                        <input type="text" class="form-control" name="addr_of_owner" id="addr_of_owner" placeholder="Address of Owner" required value="<?php echo $vehicle_row['addr_of_owner']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="owner_phone_num">Owner Phone Number:</label>
                        <input type="text" class="form-control" name="owner_phone_num" id="owner_phone_num" placeholder="Owner Phone Number" required value="<?php echo $vehicle_row['owner_phone_num']; ?>" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_img_front">Front Image of Vehicle:</label>
                        <input type="file" class="form-control" name="vehicle_img_front" id="vehicle_img_front" accept="image/*">
                        <?php if (!empty($vehicle_row['vehicle_img_front'])) : ?>
                            <p>Current File: <?php echo $vehicle_row['vehicle_img_front']; ?></p>
                            <input type="hidden" name="current_vehicle_img_front" value="<?php echo $vehicle_row['vehicle_img_front']; ?>">
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="association">Select Association:</label>
                        <select class="form-control" name="association" id="association" required>
                            
                            <?php
                            $query = "SELECT association_id, association_name, association_area FROM tbl_association";
                            $result = $connections->query($query);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($row['association_id'] === $driver_row['fk_association_id']) ? 'selected' : '';
                                    echo "<option value='{$row['association_id']}' $selected>{$row['association_name']} - {$row['association_area']}</option>";
                                }
                            } else {
                                echo "<option disabled>No associations found</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <br>
                    <center><button type="submit" name="submit" class="btn btn-success" style="width: 100%; font-size: 18px;">Submit</button></center>

            <?php
                } else {
                    echo "Driver record not found.";
                }
            } else {
                echo "Driver ID not provided.";
            }

            // Close database connection
            $connections->close();
            ?>
        </form>
    </div>
    <hr>
    <!--
    <i style="position: relative; right: -170px; padding-bottom: 20px; font-size: 15px;">Notice: Changing this may require you to upload new files for 2x2 Picture, Proof of Document, and Front and Back Vehicle Image.</i></label>
    -->

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>