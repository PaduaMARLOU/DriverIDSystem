<?php
session_start();

include("../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $admin_id = $fetch["admin_id"];
    $account_type = $fetch["account_type"];

    if ($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

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
    $name_to_notify = $_POST['name_to_notify'];
    $relationship = $_POST['relationship'];
    $num_to_notify = $_POST['num_to_notify'];
    $association = $_POST['association'];
    $vehicle_ownership = $_POST['vehicle_ownership'];
    $name_of_owner = $_POST['name_of_owner'];
    $addr_of_owner = $_POST['addr_of_owner'];
    $owner_phone_num = $_POST['owner_phone_num'];

    // Calculate age based on birth date
    $birth_date_obj = new DateTime($birth_date);
    $current_date_obj = new DateTime();
    $age_interval = $current_date_obj->diff($birth_date_obj);
    $age = $age_interval->y;

    // Function to handle file upload and return path
    function handleFileUpload($file, $targetDir, $formatted_id, $suffix = '')
    {
        if ($file['size'] > 0) {
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . $formatted_id . '_' . $suffix . '.' . $fileExtension;
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

    // Retrieve current file paths from database
    $current_pic_2x2_path = $current_vehicle_img_front_path = "";

    $select_paths_sql = "SELECT pic_2x2, 
                                (SELECT vehicle_img_front FROM tbl_vehicle WHERE fk_driver_id = tbl_driver.driver_id) AS vehicle_img_front
                        FROM tbl_driver 
                        WHERE formatted_id = ?";
    $select_paths_stmt = $connections->prepare($select_paths_sql);
    if ($select_paths_stmt === false) {
        die('Error preparing select statement: ' . $connections->error);
    }
    $select_paths_stmt->bind_param("s", $formatted_id);
    $select_paths_stmt->execute();
    $select_paths_stmt->bind_result($current_pic_2x2_path, $current_vehicle_img_front_path);
    $select_paths_stmt->fetch();
    $select_paths_stmt->close();

    // Handle file uploads with checks
    $pic_2x2_path = handleFileUpload($_FILES['pic_2x2'], $driversDir, $formatted_id) ?: $current_pic_2x2_path;
    $vehicle_img_front_path = handleFileUpload($_FILES['vehicle_img_front'], $vehiclesDir, $formatted_id, 'front') ?: $current_vehicle_img_front_path;

    // Adjust paths for database storage
    $pic_2x2_path_db = str_replace('../../', '', $pic_2x2_path);
    $vehicle_img_front_path_db = str_replace('../../', '', $vehicle_img_front_path);

    // Initialize the variables for vehicle category and formatted category
    $vehicle_category = "";
    $formatted_category = "";

    // Update the vehicle category and formatted category dynamically
    if (array_key_exists($driver_category, $vehicleOptions)) {
        $vehicle_category = $driver_category; // Match found in database
        $formatted_category = $vehicleOptions[$driver_category];
    } else {
        $vehicle_category = $driver_category; // Default to the input driver category
        $formatted_category = strtoupper(substr($driver_category, 0, 4)); // Generate fallback formatted category
    }

    // Modify formatted_id based on the new category
    if (strpos($formatted_id, '-') !== false) {
        $formatted_id_parts = explode('-', $formatted_id);
        $new_formatted_id = $formatted_category . '-' . $formatted_id_parts[1];
    } else {
        $new_formatted_id = $formatted_id;
    }

    // Rename files if formatted_id changes
    if ($formatted_id !== $new_formatted_id) {
        function renameFile($oldPath, $newDir, $newFormattedId, $suffix = '')
        {
            if ($oldPath && file_exists($oldPath)) {
                $fileExtension = pathinfo($oldPath, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '_' . $newFormattedId . '_' . $suffix . '.' . $fileExtension;
                $newPath = $newDir . $newFileName;
                if (rename($oldPath, $newPath)) {
                    return $newPath;
                } else {
                    return $oldPath; // Keep the old path if renaming fails
                }
            }
            return $oldPath;
        }

        $pic_2x2_path_db = renameFile($pic_2x2_path_db, $driversDir, $new_formatted_id);
        /*$doc_proof_path_db = renameFile($doc_proof_path_db, $documentsDir, $new_formatted_id); */
        $vehicle_img_front_path_db = renameFile($vehicle_img_front_path_db, $vehiclesDir, $new_formatted_id, 'front');
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
        name_to_notify = ?, 
        relationship = ?, 
        num_to_notify = ?,
        vehicle_ownership = ?, 
        fk_association_id = ?, 
        pic_2x2 = ?, 
        formatted_id = ? 
        WHERE formatted_id = ?";

    $vehicle_update_sql = "UPDATE tbl_vehicle SET 
        vehicle_category = ?, 
        name_of_owner = ?, 
        addr_of_owner = ?, 
        owner_phone_num = ?, 
        vehicle_img_front = ? 
        WHERE fk_driver_id = (SELECT driver_id FROM tbl_driver WHERE formatted_id = ?)";

    // Update driver information
    $driver_stmt = $connections->prepare($driver_update_sql);
    if ($driver_stmt === false) {
        die('Error preparing driver update statement: ' . $connections->error);
    }
    $driver_stmt->bind_param(
        "sssssssssssssssssssss",
        $driver_category,
        $first_name,
        $middle_name,
        $last_name,
        $suffix_name,
        $nickname,
        $age,
        $birth_date,
        $birth_place,
        $sex,
        $address,
        $mobile_number,
        $civil_status,
        $name_to_notify,
        $relationship,
        $num_to_notify,
        $vehicle_ownership,
        $association,
        $pic_2x2_path_db,
        $new_formatted_id,
        $formatted_id
    );

    // Update vehicle information
    $vehicle_stmt = $connections->prepare($vehicle_update_sql);
    if ($vehicle_stmt === false) {
        die('Error preparing vehicle update statement: ' . $connections->error);
    }
    $vehicle_stmt->bind_param(
        "ssssss",
        $vehicle_category,
        $name_of_owner,
        $addr_of_owner,
        $owner_phone_num,
        $vehicle_img_front_path_db,
        $new_formatted_id
    );

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
                <link rel='icon' href='../../img/Brgy Estefania Logo.png' type='image/png'>
                <title>Update Record</title>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

                    * {
                        padding: 0;
                        margin: 0;
                        box-sizing: border-box;
                        font-family: 'Poppins', sans-serif;
                    }

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
                        text-align: center;
                    }
                    .btn {
                        display: inline-block;
                        padding: 10px 20px;
                        background: #007bff;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 5px;
                        transition: .2s;
                    }
                    .btn:hover {
                        background: #0056b3;
                    }

                    .btn:active {
                        transform: scale(.9);
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
                <div class='container'>
                    <div class='success-box'>
                        Records updated successfully!<br>
                    </div>";

    // Get the current date and time
    date_default_timezone_set('Asia/Manila');
    $current_datetime = date('Y-m-d H:i:s');

    // Retrieve updated data from tbl_driver
    $select_driver_sql = "SELECT * FROM tbl_driver WHERE formatted_id = ?";
    $select_driver_stmt = $connections->prepare($select_driver_sql);
    if ($select_driver_stmt === false) {
        die('Error preparing select statement: ' . $connections->error);
    }
    $select_driver_stmt->bind_param("s", $new_formatted_id);
    $select_driver_stmt->execute();
    $driver_result = $select_driver_stmt->get_result();
    $driver_row = $driver_result->fetch_assoc();
    $driver_id = $driver_row['driver_id'];

    // Retrieve updated data from tbl_vehicle
    $select_vehicle_sql = "SELECT * FROM tbl_vehicle WHERE fk_driver_id = (SELECT driver_id FROM tbl_driver WHERE formatted_id = ?)";
    $select_vehicle_stmt = $connections->prepare($select_vehicle_sql);
    if ($select_vehicle_stmt === false) {
        die('Error preparing select statement: ' . $connections->error);
    }
    $select_vehicle_stmt->bind_param("s", $new_formatted_id);
    $select_vehicle_stmt->execute();
    $vehicle_result = $select_vehicle_stmt->get_result();
    $vehicle_row = $vehicle_result->fetch_assoc();

    // Log the edit action
    $action_details = "Edited Driver ID: $new_formatted_id"; // Change as necessary to fit your needs
    $log_query = "INSERT INTO tbl_log (fk_admin_id, fk_driver_id, action_details, action_date) VALUES ('$admin_id', '$driver_id', '$action_details', '$current_datetime')";
    mysqli_query($connections, $log_query); // Ignore errors for logging

    // Display all fields
    echo "<div class='updated-details'>
            <h2>Updated Driver Record Details:</h2>
            <ul>";
    foreach ($driver_row as $key => $value) {
        echo "<li><strong>" . ucfirst(str_replace("_", " ", $key)) . ":</strong> " . $value . "</li>";
    }
    echo "</ul>
        <h2>Updated Vehicle Record Details:</h2>
        <ul>";
    foreach ($vehicle_row as $key => $value) {
        echo "<li><strong>" . ucfirst(str_replace("_", " ", $key)) . ":</strong> " . $value . "</li>";
    }
    echo "</ul>
        </div>";

    // Button to redirect to driver.php
    echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='driver.php' class='btn'>Go to Driver Table</a>&nbsp;&nbsp;";
    echo "or&nbsp;&nbsp;";
    echo "<a href='verify.php' class='btn'>Go to Verification Table</a>";

    echo "<br><br><div id='countdown-message'>
    You will vanish in 30 seconds...
        </div>";

    echo "<script>
    let countdown = 30;
    let countdownElement = document.getElementById('countdown-message');

    let interval = setInterval(function() {
        countdown--;
        countdownElement.innerHTML = 'You will vanish in ' + countdown + ' seconds...';

        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = 'driver.php';
        }
    }, 1000); // 1 second interval
    </script>";
} else {
    echo "Error updating records: " . $connections->error;
}


    // Close prepared statements
    $driver_stmt->close();
    $vehicle_stmt->close();
}

// Close database connection
$connections->close();
