<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if ($account_type != 1) {
        header("Location: ../../../Forbidden2.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../../Forbidden2.php");
    exit; // Ensure script stops executing after redirection
}
?>

<?php
include '../../../connections.php';

// Fetch vehicle settings from tbl_set where set_category = 'vehicle'
$vehiclesQuery = "SELECT set_id, set_vehicleName, set_vehicleFormat, set_vehicleColor, set_vehicleColorName 
                  FROM tbl_set 
                  WHERE set_category = 'vehicle'";
$vehiclesResult = mysqli_query($connections, $vehiclesQuery);

// Handle form submission for adding a vehicle setting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addVehicle'])) {
    $vehicleName = $_POST['set_vehicleName'];
    $vehicleFormat = $_POST['set_vehicleFormat'];
    $vehicleColor = $_POST['set_vehicleColor'];
    $vehicleColorName = $_POST['set_vehicleColorName'];

    $insertQuery = "INSERT INTO tbl_set (set_category, set_vehicleName, set_vehicleFormat, set_vehicleColor, set_vehicleColorName) 
                    VALUES ('vehicle', '$vehicleName', '$vehicleFormat', '$vehicleColor', '$vehicleColorName')";
    mysqli_query($connections, $insertQuery);
    header('Location: vehicle_settings.php');
    exit;
}

// Handle form submission for editing a vehicle setting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editVehicle'])) {
    $id = $_POST['set_id'];
    $vehicleName = $_POST['set_vehicleName'];
    $vehicleFormat = $_POST['set_vehicleFormat'];
    $vehicleColor = $_POST['set_vehicleColor'];
    $vehicleColorName = $_POST['set_vehicleColorName'];

    $updateQuery = "UPDATE tbl_set 
                    SET set_vehicleName='$vehicleName', set_vehicleFormat='$vehicleFormat', set_vehicleColor='$vehicleColor', set_vehicleColorName='$vehicleColorName' 
                    WHERE set_id='$id' AND set_category='vehicle'";
    mysqli_query($connections, $updateQuery);
    header('Location: vehicle_settings.php');
    exit;
}


// Handle deletion of a vehicle setting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteVehicle'])) {
    $id = $_POST['set_id'];

    $deleteQuery = "DELETE FROM tbl_set WHERE set_id='$id' AND set_category='vehicle'";
    mysqli_query($connections, $deleteQuery);
    header('Location: vehicle_settings.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Vehicle Settings</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.5/jscolor.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            outline: none;
        }

        dotlottie-player {
            position: absolute;
            top: -4rem;
            right: 40.5rem;
        }

        .color-picker {
            width: 100%;
            padding: 0;
            border: none;
        }

        .back {
            background-color: #007BFF;
            border: 1.5px solid #007BFF;
            border-radius: 3px;
            font-size: 20px;
            color: white;
            padding-left: .5rem;
            padding-right: .5rem;
            transition: .2s;
        }

        .back:hover {
            color: white;
            background-color: #0A74E7;
            text-decoration: none;
        }

        .back:active {
            display: inline-block;
            transform: scale(.9);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorInput = document.querySelectorAll('.color-picker');

            colorInput.forEach(input => {
                input.addEventListener('change', function() {
                    const colorValue = this.value;
                    const colorCell = this.closest('tr').querySelector('.color-cell');

                    // Fetch color name and adjust text color based on lightness
                    fetch(`https://www.thecolorapi.com/id?hex=${colorValue.substring(1)}`)
                        .then(response => response.json())
                        .then(data => {
                            colorCell.innerText = data.name.value;
                            colorCell.style.backgroundColor = colorValue;
                            colorCell.style.color = isColorDark(colorValue) ? 'white' : 'black';
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });

        // Function to check if a color is dark or light
        function isColorDark(hexColor) {
            hexColor = hexColor.replace("#", "");
            const r = parseInt(hexColor.substring(0, 2), 16);
            const g = parseInt(hexColor.substring(2, 4), 16);
            const b = parseInt(hexColor.substring(4, 6), 16);
            // Calculate brightness
            const brightness = (r * 299 + g * 587 + b * 114) / 1000;
            return brightness < 128;
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Vehicle Settings</h2>
        <dotlottie-player src="https://lottie.host/98e07caf-04fd-4459-b372-d6d75501fa1c/cNtRr0yMGC.lottie" background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addVehicleModal">Add New Vehicle</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        <center>ID</center>
                    </th>
                    <th>
                        <center>Vehicle Name</center>
                    </th>
                    <th>
                        <center>Vehicle Format</center>
                    </th>
                    <th>
                        <center>Vehicle Color</center>
                    </th>
                    <th>
                        <center>Actions</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($vehiclesResult)): ?>
                    <tr>
                        <td><?php echo $row['set_id']; ?></td>
                        <td><?php echo $row['set_vehicleName']; ?></td>
                        <td><?php echo $row['set_vehicleFormat']; ?></td>
                        <td class="color-cell" style="background-color: <?php echo $row['set_vehicleColor']; ?>; color: <?php echo (hexdec(substr($row['set_vehicleColor'], 1, 2)) * 0.299 + hexdec(substr($row['set_vehicleColor'], 3, 2)) * 0.587 + hexdec(substr($row['set_vehicleColor'], 5, 2)) * 0.114) > 128 ? 'black' : 'white'; ?>">
                            <?php echo $row['set_vehicleColorName']; ?>
                        </td>
                        <td>
                            <center><button class="btn btn-warning" data-toggle="modal" data-target="#editVehicleModal<?php echo $row['set_id']; ?>">Edit</button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteVehicleModal<?php echo $row['set_id']; ?>">Delete</button>
                            </center>
                        </td>
                    </tr>
                    <!-- Edit Vehicle Modal -->
                    <div class="modal fade" id="editVehicleModal<?php echo $row['set_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editVehicleModalLabel">Edit Vehicle</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="set_id" value="<?php echo $row['set_id']; ?>">
                                        <div class="form-group">
                                            <label>Vehicle Name</label>
                                            <input type="text" name="set_vehicleName" class="form-control" value="<?php echo $row['set_vehicleName']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Vehicle Format</label>
                                            <input type="text" name="set_vehicleFormat" class="form-control" value="<?php echo $row['set_vehicleFormat']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Vehicle Color</label>
                                            <input type="text" name="set_vehicleColor" class="form-control color-picker" value="<?php echo $row['set_vehicleColor']; ?>" data-jscolor="{format:'hex'}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Color Name</label>
                                            <input type="text" name="set_vehicleColorName" class="form-control" value="<?php echo $row['set_vehicleColorName']; ?>" readonly required>
                                        </div>
                                        <a href="https://www.colorhexa.com/" target="_blank" class="btn btn-link mt-2">Search Color in ColorHexa</a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="editVehicle" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Vehicle Modal -->
                    <div class="modal fade" id="deleteVehicleModal<?php echo $row['set_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteVehicleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteVehicleModalLabel">Delete Vehicle</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="set_id" value="<?php echo $row['set_id']; ?>">
                                        <p>Are you sure you want to delete this vehicle?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" name="deleteVehicle" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="../control_panel.php" class="back">Back</a><br><br>
    </div>

    <!-- Add New Vehicle Modal -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1" role="dialog" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVehicleModalLabel">Add New Vehicle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Vehicle Name</label>
                            <input type="text" name="set_vehicleName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Vehicle Format</label>
                            <input type="text" name="set_vehicleFormat" class="form-control" value="<?php echo isset($row['set_vehicleFormat']) ? $row['set_vehicleFormat'] : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Vehicle Color</label>
                            <input type="text" name="set_vehicleColor" class="form-control color-picker" value="#ffffff" data-jscolor="{format:'hex'}" required>
                        </div>
                        <div class="form-group">
                            <label>Color Name</label>
                            <input type="text" name="set_vehicleColorName" class="form-control" readonly required>
                        </div>
                        <a href="https://www.colorhexa.com/" target="_blank" class="btn btn-link mt-2">Search Color in ColorHexa</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addVehicle" class="btn btn-primary">Add Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorInputs = document.querySelectorAll('.color-picker');

            colorInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const colorValue = this.value;
                    const colorNameInput = this.closest('.form-group').nextElementSibling.querySelector('input[name="set_vehicleColorName"]');

                    fetch(`https://www.thecolorapi.com/id?hex=${colorValue.substring(1)}`)
                        .then(response => response.json())
                        .then(data => {
                            colorNameInput.value = data.name.value; // Set the color name
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
</body>

</html>