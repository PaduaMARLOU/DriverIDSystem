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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $formatted_id = $_POST['formatted_id'];
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
    $name_to_notify = $_POST['name_to_notify'];
    $relationship = $_POST['relationship'];
    $num_to_notify = $_POST['num_to_notify'];
    $association = $_POST['association'];
    $vehicle_ownership = $_POST['vehicle_ownership'];
    $name_of_owner = $_POST['name_of_owner'];
    $addr_of_owner = $_POST['addr_of_owner'];
    $owner_phone_num = $_POST['owner_phone_num'];
    $vehicle_color = $_POST['vehicle_color'];
    $brand = $_POST['brand'];
    $plate_num = $_POST['plate_num'];

    // Calculate age based on birth date
    $birth_date_obj = new DateTime($birth_date);
    $current_date_obj = new DateTime();
    $age_interval = $current_date_obj->diff($birth_date_obj);
    $age = $age_interval->y;


    // Prepare SQL statements
    $driver_insert_sql = "UPDATE tbl_driver SET 
        driver_category = ?, 
        first_name = ?, 
        middle_name = ?, 
        last_name = ?, 
        nickname = ?,
        age = ?, 
        birth_date = ?, 
        birth_place = ?, 
        sex = ?, 
        address = ?, 
        mobile_number = ?, 
        civil_status = ?, 
        religion = ?, 
        citizenship = ?, 
        height = ?, 
        weight = ?, 
        name_to_notify = ?, 
        relationship = ?, 
        num_to_notify = ?, 
        association = ? 
        WHERE formatted_id = ?";

    $vehicle_insert_sql = "UPDATE tbl_vehicle SET 
        name_of_owner = ?, 
        addr_of_owner = ?, 
        owner_phone_num = ?, 
        vehicle_color = ?, 
        brand = ?, 
        plate_num = ? 
        WHERE fk_driver_id IN (SELECT driver_id FROM tbl_driver WHERE formatted_id = ?)";

    // Prepare and bind parameters for driver update
    $driver_stmt = $connections->prepare($driver_insert_sql);
    $driver_stmt->bind_param("sssssisssssssssssssss", 
        $driver_category, $first_name, $middle_name, $last_name, 
        $nickname, $age, $birth_date, $birth_place, $sex, $address, 
        $mobile_number, $civil_status, $religion, $citizenship, 
        $height, $weight, $name_to_notify, $relationship, 
        $num_to_notify, $association, $formatted_id);

    // Prepare and bind parameters for vehicle update
    $vehicle_stmt = $connections->prepare($vehicle_insert_sql);
    $vehicle_stmt->bind_param("sssssss", 
        $name_of_owner, $addr_of_owner, $owner_phone_num, 
        $vehicle_color, $brand, $plate_num, $formatted_id);

    // Execute SQL queries
    $driver_update_result = $driver_stmt->execute();
    $vehicle_update_result = $vehicle_stmt->execute();

    // Check if both updates were successful
    if ($driver_update_result && $vehicle_update_result) {
        echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Update Record</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            max-width: 600px;
                            margin: auto;
                            background: #fff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        }
                        .btn {
                            display: inline-block;
                            padding: 10px 20px;
                            background: #007bff;
                            color: #fff;
                            text-decoration: none;
                            border-radius: 5px;
                            transition: background 0.3s ease;
                        }
                        .btn:hover {
                            background: #0056b3;
                        }
                        .updated-details {
                            margin-top: 20px;
                            padding: 20px;
                            background-color: #f9f9f9;
                            border: 1px solid #ccc;
                            border-radius: 5px;
                        }
                        .updated-details h2 {
                            margin-bottom: 10px;
                        }
                        .updated-details ul {
                            list-style-type: none;
                            padding: 0;
                        }
                        .updated-details li {
                            margin-bottom: 5px;
                        }
                        .updated-details li strong {
                            font-weight: bold;
                            margin-right: 5px;
                        }
                        .success-box {
                            background-color: #d4edda;
                            color: #155724;
                            border-color: #c3e6cb;
                            padding: 10px;
                            margin-bottom: 10px;
                            border: 1px solid transparent;
                            border-radius: .25rem;
                        }
                    </style>
                </head>
                <body>
                    <div class='container' style='text-align: center;'>
                        <div class='success-box'>
                            Records updated successfully!<br>
                        </div>";

        // Retrieve updated data
        $select_sql = "SELECT * FROM tbl_driver WHERE formatted_id = ?";
        $select_stmt = $connections->prepare($select_sql);
        $select_stmt->bind_param("s", $formatted_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        $row = $result->fetch_assoc();

        // Display all fields
        echo "<div class='updated-details'>
                <h2>Updated Record Details:</h2>
                <ul>";
        foreach ($row as $key => $value) {
            echo "<li><strong>" . ucfirst(str_replace("_", " ", $key)) . ":</strong> " . $value . "</li>";
        }
        echo "</ul>
            </div>";

        // Button to redirect to driver.php
        echo "<br><a href='driver.php' class='btn'>Go to Driver Table</a>";

        // Redirect after displaying the success message and updated record details
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'driver.php';
                }, 10000); // 10 seconds delay
              </script>
          </div>
        </body>
        </html>";

    } else {
        echo "Error updating records: " . $connections->error;
    }

    // Close prepared statements
    $driver_stmt->close();
    $vehicle_stmt->close();
}

// Close database connection
$connections->close();
?>

