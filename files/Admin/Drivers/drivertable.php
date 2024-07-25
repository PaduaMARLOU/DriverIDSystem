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
    
    <link rel="icon" href="../../../img/Brgy Estefania Logo.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>
<body>

<style>
    <?php include("d_style.css"); ?>
</style>

<div class="container">
    <center class="logo-driver"><img src="../../../img/Brgy Estefania Logo.png" alt="Brgy. Estefania Logo" class="logo">&nbsp;&nbsp;<h1 class="title">Driver's Table</h1></center>
    <div class="table-container">
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <?php
                    // Output table header with field names
                    while ($fieldinfo = $result->fetch_field()) {
                        echo "<th>" . $fieldinfo->name . "</th>";
                    }
                    ?>
                    <th>Action</th> <!-- Add action column at the end -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Rewind the result set pointer to start
                $result->data_seek(0);
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    // Data columns
                    foreach ($row as $value) {
                        echo "<td>".$value."</td>";
                    }
                    // Action buttons column
                    echo "<td class='action-buttons'>
                            <center><button class='edit-btn' id='btns' data-driver-id='".$row['driver_id']."'><ion-icon name='pencil' class='edit-icon'></ion-icon></button>
                            <button class='delete-btn' id='btns' data-driver-id='".$row['driver_id']."'><ion-icon name='trash' class='del-icon'></ion-icon></button></center>
                          </td>";
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
    // Initialize DataTable with custom dom
    let table = $('#myTable').DataTable({
        "dom": '<"top"lf>rt<"bottom"ip><"clear">' // Custom placement of elements
    });

    // Handle edit button click using event delegation
    $('#myTable').on('click', '.edit-btn', function() {
        // Get the driver ID of the record to be edited
        let driverId = $(this).data('driver-id');

        // Redirect to edit page with the driver ID
        window.location.href = 'edit_record.php?driver_id=' + driverId;
    });

    // Handle delete button click using event delegation
    $('#myTable').on('click', '.delete-btn', function() {
        // Get the driver ID of the record to be deleted
        let driverId = $(this).data('driver-id');

        // Confirm deletion
        if(confirm("Are you sure you want to delete this record? 😟")) {
            // Redirect to delete script with the driver ID
            window.location.href = 'delete_record.php?driver_id=' + driverId;
        }
    });
});

</script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>
