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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $formatted_id = $_POST['formatted_id'];
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

    // Function to handle file upload and return path
    function handleFileUpload($file, $targetDir, $formatted_id, $suffix = '') {
        if ($file['size'] > 0) {
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . $formatted_id . '_' . $suffix . '_' . $file['name'];
            $targetPath = $targetDir . $fileName;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return $targetPath;
            } else {
                return false;
            }
        } else {
            return null; // Return null if no file is uploaded
        }
    }

    // File upload directories
    $uploadDir = '../../uploads/';
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

    // Handle file uploads with checks
    $pic_2x2_path = handleFileUpload($_FILES['pic_2x2'], $driversDir, $formatted_id);
    $doc_proof_path = handleFileUpload($_FILES['doc_proof'], $documentsDir, $formatted_id);
    $vehicle_img_front_path = handleFileUpload($_FILES['vehicle_img_front'], $vehiclesDir, $formatted_id, 'front');
    $vehicle_img_back_path = handleFileUpload($_FILES['vehicle_img_back'], $vehiclesDir, $formatted_id, 'back');

    // Retrieve current file paths from database if no new files uploaded
    if ($pic_2x2_path === null) {
        $select_pic_sql = "SELECT pic_2x2 FROM tbl_driver WHERE formatted_id = ?";
        $select_pic_stmt = $connections->prepare($select_pic_sql);
        if ($select_pic_stmt === false) {
            die('Error preparing select statement: ' . $connections->error);
        }
        $select_pic_stmt->bind_param("s", $formatted_id);
        $select_pic_stmt->execute();
        $select_pic_stmt->bind_result($pic_2x2_path);
        $select_pic_stmt->fetch();
        $select_pic_stmt->close();
    }

    if ($doc_proof_path === null) {
        $select_doc_sql = "SELECT doc_proof FROM tbl_driver WHERE formatted_id = ?";
        $select_doc_stmt = $connections->prepare($select_doc_sql);
        if ($select_doc_stmt === false) {
            die('Error preparing select statement: ' . $connections->error);
        }
        $select_doc_stmt->bind_param("s", $formatted_id);
        $select_doc_stmt->execute();
        $select_doc_stmt->bind_result($doc_proof_path);
        $select_doc_stmt->fetch();
        $select_doc_stmt->close();
    }

    if ($vehicle_img_front_path === null) {
        $select_front_sql = "SELECT vehicle_img_front FROM tbl_vehicle WHERE fk_driver_id IN (SELECT driver_id FROM tbl_driver WHERE formatted_id = ?)";
        $select_front_stmt = $connections->prepare($select_front_sql);
        if ($select_front_stmt === false) {
            die('Error preparing select statement: ' . $connections->error);
        }
        $select_front_stmt->bind_param("s", $formatted_id);
        $select_front_stmt->execute();
        $select_front_stmt->bind_result($vehicle_img_front_path);
        $select_front_stmt->fetch();
        $select_front_stmt->close();
    }

    if ($vehicle_img_back_path === null) {
        $select_back_sql = "SELECT vehicle_img_back FROM tbl_vehicle WHERE fk_driver_id IN (SELECT driver_id FROM tbl_driver WHERE formatted_id = ?)";
        $select_back_stmt = $connections->prepare($select_back_sql);
        if ($select_back_stmt === false) {
            die('Error preparing select statement: ' . $connections->error);
        }
        $select_back_stmt->bind_param("s", $formatted_id);
        $select_back_stmt->execute();
        $select_back_stmt->bind_result($vehicle_img_back_path);
        $select_back_stmt->fetch();
        $select_back_stmt->close();
    }

    // Prepare SQL statements
    $driver_update_sql = "UPDATE tbl_driver SET 
        driver_category = ?, 
        first_name = ?, 
        middle_name = ?, 
        last_name = ?,
        suffix_name = ?, 
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
        vehicle_ownership =?, 
        fk_association_id = ?, 
        pic_2x2 = ?, 
        doc_proof = ? 
        WHERE formatted_id = ?";

    $vehicle_update_sql = "UPDATE tbl_vehicle SET 
        name_of_owner = ?, 
        addr_of_owner = ?, 
        owner_phone_num = ?, 
        vehicle_color = ?, 
        brand = ?, 
        plate_num = ?, 
        vehicle_img_front = ?, 
        vehicle_img_back = ? 
        WHERE fk_driver_id IN (SELECT driver_id FROM tbl_driver WHERE formatted_id = ?)";

    // Prepare and bind parameters for driver update
    $driver_stmt = $connections->prepare($driver_update_sql);
    if ($driver_stmt === false) {
        die('Error preparing driver update statement: ' . $connections->error);
    }
    $driver_stmt->bind_param("ssssssissssssssssssssssss", 
    $driver_category, $first_name, $middle_name, $last_name, $suffix_name, 
    $nickname, $age, $birth_date, $birth_place, $sex, $address, 
    $mobile_number, $civil_status, $religion, $citizenship, 
    $height, $weight, $name_to_notify, $relationship, 
    $num_to_notify, $vehicle_ownership, $association, $pic_2x2_path, $doc_proof_path, $formatted_id);

    // Prepare and bind parameters for vehicle update
    $vehicle_stmt = $connections->prepare($vehicle_update_sql);
    if ($vehicle_stmt === false) {
        die('Error preparing vehicle update statement: ' . $connections->error);
    }
    $vehicle_stmt->bind_param("sssssssss", 
    $name_of_owner, $addr_of_owner, $owner_phone_num, 
    $vehicle_color, $brand, $plate_num, $vehicle_img_front_path, $vehicle_img_back_path, $formatted_id);

    // Execute SQL queries
    $driver_update_result = $driver_stmt->execute();
    $vehicle_update_result = $vehicle_stmt->execute();

    // Check if both updates were successful
    if ($driver_update_result && $vehicle_update_result) {
        // Success message and display updated details
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
        if ($select_stmt === false) {
            die('Error preparing select statement: ' . $connections->error);
        }
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
