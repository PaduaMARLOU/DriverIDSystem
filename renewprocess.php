<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="driverportalcss/renewprocessstyle.css">
    <title>Renew Status - Barangay Estefania Driver's ID System</title>
</head>
<body>
    <div class="container">
        <h1>Renew Status</h1>
        <div class="notice">
            <?php
            include 'connections.php';

            // Initialize $message variable
            $message = "";

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve the formatted ID from the form
                $formatted_id = $_POST["formatted_id"];

                // Prepare a SQL statement to fetch the renew_stat based on the formatted_id
                $sql = "SELECT renew_stat FROM tbl_driver WHERE formatted_id = ?";
                $stmt = $connections->prepare($sql);

                if (!$stmt) {
                    // Handle query preparation error
                    echo "Error: " . $connections->error;
                } else {
                    // Bind parameters
                    $stmt->bind_param("s", $formatted_id);

                    // Execute the statement
                    $stmt->execute();

                    // Bind the result variables
                    $stmt->bind_result($renew_stat);

                    // Fetch the result
                    if ($stmt->fetch()) {
                        // Check the renewal status and set $message
                        if ($renew_stat == "Active") {
                            $message = "<span class='active'>Your Status is Active!</span>";
                        } elseif ($renew_stat == "For Renewal" || empty($renew_stat)) {
                            $message = "<span class='renew'>Please Process your Renewal in the Barangay Hall.</span>";
                        } else {
                            $message = "<span class='invalid'>Invalid Driver ID Code!</span>";
                        }
                    } else {
                        $message = "<span class='invalid'>Invalid Driver ID Code!</span>";
                    }

                    // Close statement
                    $stmt->close();
                }

                // Close connection
                $connections->close();
            } else {
                // If the form is not submitted, redirect back to the renew page
                header("Location: renewpage.php");
                exit();
            }

            echo $message;
            ?>
        </div>
        <button class="back-btn" onclick="window.history.back()">Back</button>
    </div>
</body>
</html>
