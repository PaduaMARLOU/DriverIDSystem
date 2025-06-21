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
?>

<?php
include '../../../connections.php';

// Fetch association categories from ENUM
$enumQuery = "SHOW COLUMNS FROM tbl_association LIKE 'association_category'";
$enumResult = mysqli_query($connections, $enumQuery);
$enumRow = mysqli_fetch_assoc($enumResult);
preg_match("/^enum\(\'(.*)\'\)$/", $enumRow['Type'], $matches);
$enumValues = explode("','", $matches[1]);

// Fetch associations for display
$associationsQuery = "SELECT * FROM tbl_association";
$associationsResult = mysqli_query($connections, $associationsQuery);

// Handle form submission for adding an association
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addAssociation'])) {
    $category = $_POST['association_category'];
    $name = $_POST['association_name'];
    $area = $_POST['association_area'];
    $color = $_POST['association_color'];
    $colorName = $_POST['association_color_name'];

    $insertQuery = "INSERT INTO tbl_association (association_category, association_name, association_area, association_color, association_color_name) VALUES ('$category', '$name', '$area', '$color', '$colorName')";
    mysqli_query($connections, $insertQuery);
    header('Location: association_settings.php');
}

// Handle form submission for editing an association
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editAssociation'])) {
    $id = $_POST['association_id'];
    $category = $_POST['association_category'];
    $name = $_POST['association_name'];
    $area = $_POST['association_area'];
    $color = $_POST['association_color'];
    $colorName = $_POST['association_color_name'];

    $updateQuery = "UPDATE tbl_association SET association_category='$category', association_name='$name', association_area='$area', association_color='$color', association_color_name='$colorName' WHERE association_id='$id'";
    mysqli_query($connections, $updateQuery);
    header('Location: association_settings.php');
}

// Handle deletion of an association
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteAssociation'])) {
    $id = $_POST['association_id'];

    $deleteQuery = "DELETE FROM tbl_association WHERE association_id='$id'";
    mysqli_query($connections, $deleteQuery);
    header('Location: association_settings.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Association Settings</title>
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

        .color-picker {
            width: 100%;
            padding: 0;
            border: none;
        }
        dotlottie-player {
            position: absolute;
            top: -.1rem;
            right: 43rem;
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
    <h2 class="mb-4">Association Settings</h2>
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
    <dotlottie-player src="https://lottie.host/a9bf801c-30b0-41a8-9efb-085c36577b9b/SQIdQVvlCa.json" background="transparent" speed="1" style="width: 180px; height: 180px;" loop autoplay></dotlottie-player>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addAssociationModal">Add New Association</button>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><center>ID</center></th>
            <th><center>Category</center></th>
            <th><center>Name</center></th>
            <th><center>Area</center></th>
            <th><center>Color</center></th>
            <th><center>Actions</center></th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($associationsResult)): ?>
            <tr>
                <td><?php echo $row['association_id']; ?></td>
                <td><?php echo $row['association_category']; ?></td>
                <td><?php echo $row['association_name']; ?></td>
                <td><?php echo $row['association_area']; ?></td>
                <td class="color-cell" style="background-color: <?php echo $row['association_color']; ?>; color: <?php echo (hexdec(substr($row['association_color'], 1, 2)) * 0.299 + hexdec(substr($row['association_color'], 3, 2)) * 0.587 + hexdec(substr($row['association_color'], 5, 2)) * 0.114) > 128 ? 'black' : 'white'; ?>">
                    <?php echo $row['association_color_name']; ?>
                </td>
                <td>
                    <center><button class="btn btn-warning" data-toggle="modal" data-target="#editAssociationModal<?php echo $row['association_id']; ?>">Edit</button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteAssociationModal<?php echo $row['association_id']; ?>">Delete</button></center>
                </td>
            </tr>
            <!-- Edit Association Modal -->
            <div class="modal fade" id="editAssociationModal<?php echo $row['association_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editAssociationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAssociationModalLabel">Edit Association</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="association_id" value="<?php echo $row['association_id']; ?>">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="association_category" class="form-control" required>
                                        <?php foreach ($enumValues as $value): ?>
                                            <option value="<?php echo $value; ?>" <?php echo $row['association_category'] == $value ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="association_name" class="form-control" value="<?php echo $row['association_name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Area</label>
                                    <input type="text" name="association_area" class="form-control" value="<?php echo $row['association_area']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Color</label>
                                    <input type="text" name="association_color" class="form-control color-picker" value="<?php echo $row['association_color']; ?>" data-jscolor="{format:'hex'}" required>
                                </div>
                                <div class="form-group">
                                    <label>Color Name</label>
                                    <input type="text" name="association_color_name" class="form-control" value="<?php echo $row['association_color_name']; ?>" readonly required>
                                </div>
                                <a href="https://www.colorhexa.com/" target="_blank" class="btn btn-link mt-2">Search Color in ColorHexa</a>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="editAssociation" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Association Modal -->
            <div class="modal fade" id="deleteAssociationModal<?php echo $row['association_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteAssociationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteAssociationModalLabel">Delete Association</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="association_id" value="<?php echo $row['association_id']; ?>">
                                <p>Are you sure you want to delete this association?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="deleteAssociation" class="btn btn-danger">Delete</button>
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

<!-- Add Association Modal -->
<div class="modal fade" id="addAssociationModal" tabindex="-1" role="dialog" aria-labelledby="addAssociationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAssociationModalLabel">Add New Association</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="association_category" class="form-control" required>
                            <?php foreach ($enumValues as $value): ?>
                                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="association_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" name="association_area" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="text" name="association_color" class="form-control color-picker" data-jscolor="{format:'hex'}" required>
                    </div>
                    <div class="form-group">
                        <label>Color Name</label>
                        <input type="text" name="association_color_name" class="form-control" readonly required>
                    </div>
                    <a href="https://www.colorhexa.com/" target="_blank" class="btn btn-link mt-2">Search Color in ColorHexa</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="addAssociation" class="btn btn-primary">Add Association</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.5/jscolor.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInputs = document.querySelectorAll('.color-picker');
        colorInputs.forEach(input => {
            input.addEventListener('change', function() {
                const colorValue = this.value;
                const colorNameInput = this.closest('.form-group').nextElementSibling.querySelector('input[name="association_color_name"]');

                fetch(`https://www.thecolorapi.com/id?hex=${colorValue.substring(1)}`)
                    .then(response => response.json())
                    .then(data => {
                        colorNameInput.value = data.name.value;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
</body>
</html>
