<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("../../../connections.php");

    if(isset($_SESSION["username"])) {
        $username = $_SESSION["username"];

        $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
        $fetch = mysqli_fetch_assoc($authentication);
        $account_type = $fetch["account_type"];
        
        if($account_type != 1){
            header("Location: ../../../Forbidden2.php");
            exit; // Ensure script stops executing after redirection
        }
    } else {
        header("Location: ../../../Forbidden2.php");
        exit; // Ensure script stops executing after redirection
    }

// Check if driver_id is provided in the URL
if(isset($_GET['driver_id'])) {
    // Retrieve the driver_id from the URL
    $driver_id = $_GET['driver_id'];

    // SQL query to retrieve the record based on driver_id
    $sql = "SELECT * FROM tbl_driver WHERE driver_id = $driver_id";
    $result = $connections->query($sql);

    // Check if the record exists
    if($result->num_rows > 0) {
        // Fetch the record as an associative array
        $row = $result->fetch_assoc();

        // Get column names from the result set
        $columnNames = array_keys($row);

        // Display a form to edit the record
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>

    <link rel="icon" href="../../../img/Brgy Estefania Logo.png" alt="Brgy. Estefania Logo">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            color: #2b333a;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .back {
            position: relative;
            color: #4967E7;
            font-size: 40px;
        }

        .back:active {
            color: #6F82D2;
        }

        h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="drivertable.php"><ion-icon name="arrow-back-circle" class="back"></ion-icon></a>
        <h2>Edit Record</h2>
        <form action="update_record.php" method="POST">
            <input type="hidden" name="driver_id" value="<?php echo $row['driver_id']; ?>">
            <?php
            // Dynamically generate form fields based on column names
            foreach ($columnNames as $columnName) {
                // Skip the primary key column
                if($columnName !== 'driver_id') {
                    echo "<label for='$columnName'>$columnName:</label>";
                    echo "<input type='text' id='$columnName' name='$columnName' value='".htmlspecialchars($row[$columnName], ENT_QUOTES)."'><br>";
                }
            }
            ?>
            <input type="submit" value="Submit">
        </form>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

<?php
    } else {
        echo "Record not found.";
    }
} else {
    echo "Driver ID not provided.";
}
?>
