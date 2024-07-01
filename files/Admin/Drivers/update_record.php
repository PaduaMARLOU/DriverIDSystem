<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("../../../connections.php");

    if(isset($_SESSION["email"])) {
        $email = $_SESSION["email"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE email='$email'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];

        if($account_type != 1){
            header("Location: ../../../Forbidden.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        header("Location: ../../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }

// Initialize message variable
$message = "";

// Check if form is submitted with driver_id and other fields
if(isset($_POST['driver_id'])) {
    // Retrieve driver_id from form submission
    $driver_id = $_POST['driver_id'];

    // Prepare an associative array to hold column names and their respective values for updating
    $updates = array();

    // Loop through POST data to extract fields to be updated
    foreach ($_POST as $key => $value) {
        // Skip driver_id field
        if($key !== 'driver_id') {
            // Escape the value to prevent SQL injection
            $value = $connections->real_escape_string($value);
            // Add column name and value to the updates array
            $updates[] = "$key = '$value'";
        }
    }

    // Construct the SQL UPDATE query
    $sql = "UPDATE tbl_driver SET " . implode(', ', $updates) . " WHERE driver_id = $driver_id";

    // Execute the update query
    if ($connections->query($sql) === TRUE) {
        // Retrieve the updated record from the database
        $updated_record_query = "SELECT * FROM tbl_driver WHERE driver_id = $driver_id";
        $updated_record_result = $connections->query($updated_record_query);

        if ($updated_record_result->num_rows > 0) {
            // Set success message
            $message = "Record Updated Successfully! <br>You will be redirected to Driver Table after 10 seconds.";
            // Fetch the updated record
            $updated_record = $updated_record_result->fetch_assoc();
        } else {
            echo "No record found after updating.";
        }
    } else {
        echo "Error updating record: " . $connections->error;
    }
} else {
    echo "Driver ID not provided or no fields to update.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <div class="container" style="text-align: center;">
        <!-- Display success message -->
        <?php if(!empty($message)): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <!-- Display updated record details -->
        <?php if(isset($updated_record)): ?>
            <div class="updated-details">
                <h2>Updated Record Details:</h2>
                <ul>
                    <?php foreach($updated_record as $key => $value): ?>
                        <li><strong><?php echo $key ?>:</strong> <?php echo $value ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Button to redirect to drivertable.php -->
        <br><a href="drivertable.php" class="btn">Go to Driver Table</a>
        
        <!-- Redirect after displaying the success message and updated record details -->
        <script>
            setTimeout(function() {
                window.location.href = "drivertable.php";
            }, 10000); // 10 seconds delay
        </script>
    </div>
</body>
</html>
