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

// SQL query to select all fields from tbl_driver
$sql = "SELECT * FROM tbl_driver";
$result = $connections->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filterable Table</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto; /* Enable horizontal scrolling */
            margin-bottom: 20px; /* Add some space at the bottom */
        }
        .action-buttons {
            white-space: nowrap; /* Prevent button wrapping */
        }
        .action-buttons button {
            margin-right: 5px; /* Add some space between buttons */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="table-container">
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>Action</th> <!-- Add action column -->
                    <?php
                    // Output table header with field names
                    while ($fieldinfo = $result->fetch_field()) {
                        echo "<th>" . $fieldinfo->name . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Rewind the result set pointer to start
                $result->data_seek(0);
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    // Action buttons column
                    echo "<td class='action-buttons'>
                            <button class='edit-btn' data-driver-id='".$row['driver_id']."'>Edit</button>
                            <button class='delete-btn' data-driver-id='".$row['driver_id']."'>Delete</button>
                          </td>";
                    // Data columns
                    foreach ($row as $value) {
                        echo "<td>".$value."</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<a href="../driver.php" class="btn" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Back</a>


<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#myTable').DataTable();

        // Handle edit button click using event delegation
        $('#myTable').on('click', '.edit-btn', function() {
            // Get the driver ID of the record to be edited
            var driverId = $(this).data('driver-id');

            // Redirect to edit page with the driver ID
            window.location.href = 'edit_record.php?driver_id=' + driverId;
        });

        // Handle delete button click using event delegation
        $('#myTable').on('click', '.delete-btn', function() {
            // Get the driver ID of the record to be deleted
            var driverId = $(this).data('driver-id');

            // Confirm deletion
            if(confirm("Are you sure you want to delete this record?")) {
                // Redirect to delete script with the driver ID
                window.location.href = 'delete_record.php?driver_id=' + driverId;
            }
        });
    });
</script>


</body>
</html>
