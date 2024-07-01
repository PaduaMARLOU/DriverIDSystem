<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("../../connections.php");

    if(isset($_SESSION["email"])) {
        $email = $_SESSION["email"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email='$email'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];

        if($account_type != 1){
            header("Location: ../../Forbidden.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Your custom CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Record</h2>
        <form action="update_driver_success.php" method="POST">
            <?php
            // Include database connection
            include("../../connections.php");

            // Check if formatted_id is provided in the URL
            if(isset($_GET['formatted_id'])) {
                // Retrieve the formatted_id from the URL
                $formatted_id = $_GET['formatted_id'];

                // SQL query to retrieve driver data based on formatted_id
                $driver_sql = "SELECT * FROM tbl_driver WHERE formatted_id = '$formatted_id'";
                $driver_result = $connections->query($driver_sql);

                // SQL query to retrieve vehicle data based on fk_driver_id
                $vehicle_sql = "SELECT * FROM tbl_vehicle WHERE fk_driver_id IN (SELECT driver_id FROM tbl_driver WHERE formatted_id = '$formatted_id')";
                $vehicle_result = $connections->query($vehicle_sql);

                // Check if the driver record exists
                if($driver_result->num_rows > 0 && $vehicle_result->num_rows > 0) {
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
                            <option value="E-Bike" <?php if($driver_row['driver_category'] === 'E-Bike') echo 'selected'; ?>>E-Bike</option>
                            <option value="Tricycle" <?php if($driver_row['driver_category'] === 'Tricycle') echo 'selected'; ?>>Tricycle</option>
                            <option value="Trisikad" <?php if($driver_row['driver_category'] === 'Trisikad') echo 'selected'; ?>>Trisikad</option>
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
                        <label for="                    sex">Sex (Male or Female):</label>
                        <select class="form-control" name="sex" id="sex" required>
                            <option value="">Select Sex</option>
                            <option value="Male" <?php if($driver_row['sex'] === 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if($driver_row['sex'] === 'Female') echo 'selected'; ?>>Female</option>
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
                            <option value="Single" <?php if($driver_row['civil_status'] === 'Single') echo 'selected'; ?>>Single</option>
                            <option value="Married" <?php if($driver_row['civil_status'] === 'Married') echo 'selected'; ?>>Married</option>
                            <option value="Live-In" <?php if($driver_row['civil_status'] === 'Live-In') echo 'selected'; ?>>Live-In</option>
                            <option value="Widowed" <?php if($driver_row['civil_status'] === 'Widowed') echo 'selected'; ?>>Widowed</option>
                            <option value="Separated" <?php if($driver_row['civil_status'] === 'Separated') echo 'selected'; ?>>Separated</option>
                            <option value="Divorced" <?php if($driver_row['civil_status'] === 'Divorced') echo 'selected'; ?>>Divorced</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="religion">Religion (e.g., Christian, Islam, Buddhism, etc.):</label>
                        <input type="text" class="form-control" name="religion" id="religion" placeholder="Religion" required value="<?php echo $driver_row['religion']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="citizenship">Citizenship (e.g., Filipino):</label>
                        <input type="text" class="form-control" name="citizenship" id="citizenship" placeholder="Citizenship" required value="<?php echo $driver_row['citizenship']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="height">Height (cm, e.g., 170cm):</label>
                        <input type="text" class="form-control" name="height" id="height" placeholder="Height (cm)" required value="<?php echo $driver_row['height']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="weight">Weight (kg, e.g., 70kg):</label>
                        <input type="text" class="form-control" name="weight" id="weight" placeholder="Weight (kg)" required value="<?php echo $driver_row['weight']; ?>">
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
                            <option value="Owned" <?php if($driver_row['vehicle_ownership'] === 'Owned') echo 'selected'; ?>>Owned</option>
                            <option value="Rented" <?php if($driver_row['vehicle_ownership'] === 'Rented') echo 'selected'; ?>>Rented</option>
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
                        <input type="text" class="form-control" name="owner_phone_num" id="owner_phone_num" placeholder="Owner Phone Number" required value="<?php echo $vehicle_row['owner_phone_num']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_color">Vehicle Color:</label>
                        <input type="text" class="form-control" name="vehicle_color" id="vehicle_color" placeholder="Vehicle Color" required value="<?php echo $vehicle_row['vehicle_color']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand:</label>
                        <input type="text" class="form-control" name="brand" id="brand" placeholder="Brand" required value="<?php echo $vehicle_row['brand']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="plate_num">Plate Number:</label>
                        <input type="text" class="form-control" name="plate_num" id="plate_num" placeholder="Plate Number" value="<?php echo $vehicle_row['plate_num']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="association">Select Association:</label>
                        <select class="form-control" name="association" id="association" required>
                            <option value="">Select Association</option>
                            <?php
                            $query = "SELECT association_name, association_area FROM tbl_association";
                            $result = $connections->query($query);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($row['association_name'] === $driver_row['association']) ? 'selected' : '';
                                    echo "<option value='{$row['association_name']}' $selected>{$row['association_name']} - {$row['association_area']}</option>";
                                }
                            } else {
                                echo "<option disabled>No associations found</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    <a href="driver.php" class="btn btn-success">Back</a>
                    <hr>
                    <br>
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
</body>
</html>


