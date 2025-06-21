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

// Query to fetch official sets from tbl_set
$officialsQuery = "SELECT set_id, set_officialPosition, set_officialName FROM tbl_set WHERE set_category = 'official'";
$officialsResult = mysqli_query($connections, $officialsQuery);
$officials = [];
while ($row = mysqli_fetch_assoc($officialsResult)) {
    $officials[] = $row;
}

// Handle form submission for adding an official
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addOfficial'])) {
    $position = $_POST['set_officialPosition'];
    $name = $_POST['set_officialName'];

    // Check if the position already exists in the database
    $checkQuery = "SELECT COUNT(*) AS count FROM tbl_set WHERE set_category = 'official' AND set_officialPosition = '$position'";
    $checkResult = mysqli_query($connections, $checkQuery);
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['count'] > 0) {
        // Position already exists, do not add a duplicate
        echo "<script>alert('The position \"$position\" already exists. Please choose a different position.');</script>";
    } else {
        // Insert the new official if the position is not a duplicate
        $insertQuery = "INSERT INTO tbl_set (set_category, set_officialPosition, set_officialName) VALUES ('official', '$position', '$name')";
        mysqli_query($connections, $insertQuery);
        header('Location: officials_settings.php');
        exit; // Stop further script execution after redirection
    }
}


// Handle form submission for editing an official
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editOfficial'])) {
    $id = $_POST['set_id'];
    $position = $_POST['set_officialPosition'];
    $name = $_POST['set_officialName'];

    $updateQuery = "UPDATE tbl_set SET set_officialPosition='$position', set_officialName='$name' WHERE set_id='$id' AND set_category = 'official'";
    mysqli_query($connections, $updateQuery);
    header('Location: officials_settings.php');
}

// Handle deletion of an official
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteOfficial'])) {
    $id = $_POST['set_id'];

    $deleteQuery = "DELETE FROM tbl_set WHERE set_id='$id' AND set_category = 'official'";
    mysqli_query($connections, $deleteQuery);
    header('Location: officials_settings.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Official Settings</title>
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

        lord-icon {
            position: absolute;
            top: .7rem;
            right: 50rem;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Official Settings</h2>
        <lord-icon
            src="https://cdn.lordicon.com/zfmcashd.json"
            trigger="hover"
            style="width:160px;height:160px">
        </lord-icon>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addOfficialModal">Add New Official</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        <center>ID</center>
                    </th>
                    <th>
                        <center>Position</center>
                    </th>
                    <th>
                        <center>Name</center>
                    </th>
                    <th>
                        <center>Actions</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($officials as $official): ?>
                    <tr>
                        <td><?php echo $official['set_id']; ?></td>
                        <td><?php echo $official['set_officialPosition']; ?></td>
                        <td><?php echo $official['set_officialName']; ?></td>
                        <td>
                            <center>
                                <button class="btn btn-warning" data-toggle="modal" data-target="#editOfficialModal<?php echo $official['set_id']; ?>">Edit</button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteOfficialModal<?php echo $official['set_id']; ?>">Delete</button>
                            </center>
                        </td>
                    </tr>

                    <!-- Edit Official Modal -->
                    <div class="modal fade" id="editOfficialModal<?php echo $official['set_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editOfficialModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editOfficialModalLabel">Edit Official</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="set_id" value="<?php echo $official['set_id']; ?>">
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="text" name="set_officialPosition" class="form-control" value="<?php echo $official['set_officialPosition']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="set_officialName" class="form-control" value="<?php echo $official['set_officialName']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="editOfficial" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Official Modal -->
                    <div class="modal fade" id="deleteOfficialModal<?php echo $official['set_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteOfficialModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteOfficialModalLabel">Delete Official</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="set_id" value="<?php echo $official['set_id']; ?>">
                                        <p>Are you sure you want to delete this official?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="deleteOfficial" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../control_panel.php" class="back">Back</a><br><br>
    </div>

    <!-- Add Official Modal -->
    <div class="modal fade" id="addOfficialModal" tabindex="-1" role="dialog" aria-labelledby="addOfficialModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOfficialModalLabel">Add New Official</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" name="set_officialPosition" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="set_officialName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addOfficial" class="btn btn-primary">Add Official</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>