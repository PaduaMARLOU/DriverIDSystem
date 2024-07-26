<?php
require '../../../connections.php'; // Include your database connection file

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_association'])) {
        // Insert new association
        $category = $_POST['association_category'];
        $name = $_POST['association_name'];
        $area = $_POST['association_area'];
        $president = $_POST['association_president'];
        $color = $_POST['association_color'];

        $sql = "INSERT INTO tbl_association (association_category, association_name, association_area, association_president, association_color)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $connections->prepare($sql);
        $stmt->bind_param("sssss", $category, $name, $area, $president, $color);
        $stmt->execute();
        $stmt->close();
        echo "<p>Association added successfully!</p>";
    } elseif (isset($_POST['edit_association'])) {
        // Update existing association
        $id = $_POST['association_id'];
        $category = $_POST['association_category'];
        $name = $_POST['association_name'];
        $area = $_POST['association_area'];
        $president = $_POST['association_president'];
        $color = $_POST['association_color'];

        $sql = "UPDATE tbl_association SET association_category = ?, association_name = ?, association_area = ?, association_president = ?, association_color = ? WHERE association_id = ?";
        $stmt = $connections->prepare($sql);
        $stmt->bind_param("sssssi", $category, $name, $area, $president, $color, $id);
        $stmt->execute();
        $stmt->close();
        echo "<p>Association updated successfully!</p>";
    } elseif (isset($_POST['delete_association'])) {
        // Delete association
        $id = $_POST['association_id'];

        $sql = "DELETE FROM tbl_association WHERE association_id = ?";
        $stmt = $connections->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo "<p>Association deleted successfully!</p>";
    }
}

// Fetch existing associations for editing
$associations = [];
$sql = "SELECT * FROM tbl_association";
$result = $connections->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $associations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Association Settings</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function updateColorPickerFromName() {
            const select = document.getElementById('color_name_select');
            const color = select.options[select.selectedIndex].dataset.color;
            document.getElementById('color_picker').value = color;
            document.getElementById('color_display').textContent = color;
        }

        function updateColorName() {
            const colorPicker = document.getElementById('color_picker');
            document.getElementById('color_display').textContent = colorPicker.value;
            // Optionally, update the selected option in the dropdown
            const select = document.getElementById('color_name_select');
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].dataset.color === colorPicker.value) {
                    select.selectedIndex = i;
                    break;
                }
            }
        }
    </script>
</head>
<body>
<div class="container">
    <center><h1>Association Settings</h1></center>
    <hr>

    <!-- Add New Association Button -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Add New Association</button>

    <!-- Table for Existing Associations -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Area</th>
                <th>President</th>
                <th>Color</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($associations as $assoc): ?>
                <tr>
                    <td><?php echo $assoc['association_id']; ?></td>
                    <td><?php echo $assoc['association_category']; ?></td>
                    <td><?php echo $assoc['association_name']; ?></td>
                    <td><?php echo $assoc['association_area']; ?></td>
                    <td><?php echo $assoc['association_president']; ?></td>
                    <td style="background-color: <?php echo $assoc['association_color']; ?>;"><?php echo $assoc['association_color']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" onclick="populateEditForm(<?php echo $assoc['association_id']; ?>)">Edit</button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" onclick="setDeleteId(<?php echo $assoc['association_id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Association Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Association</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add_association_category">Category:</label>
                            <select id="add_association_category" name="association_category" class="form-control" required>
                                <?php
                                // Fetch ENUM values for association_category
                                $enum_sql = "SHOW COLUMNS FROM tbl_association WHERE Field = 'association_category'";
                                $enum_result = $connections->query($enum_sql);
                                $enum_row = $enum_result->fetch_assoc();
                                $enum_values = str_replace(["enum(", "')"], '', $enum_row['Type']);
                                $enum_values = explode("','", $enum_values);
                                foreach ($enum_values as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_association_name">Name:</label>
                            <input type="text" id="add_association_name" name="association_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="add_association_area">Area:</label>
                            <input type="text" id="add_association_area" name="association_area" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="add_association_president">President:</label>
                            <input type="text" id="add_association_president" name="association_president" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="color_name_select">Color Name:</label>
                            <select id="color_name_select" class="form-control" onchange="updateColorPickerFromName()">
                                <option value="" data-color="#ffffff">Select Color</option>
                                <option value="Red" data-color="#ff0000">Red</option>
                                <option value="Green" data-color="#00ff00">Green</option>
                                <option value="Blue" data-color="#0000ff">Blue</option>
                                <!-- Add more color options as needed -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_association_color">Color Picker:</label>
                            <input type="color" id="color_picker" name="association_color" class="form-control" onchange="updateColorName()" required>
                            <span id="color_display">#ffffff</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_association" class="btn btn-primary">Add Association</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Association Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Association</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" id="edit_association_id" name="association_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_association_category">Category:</label>
                            <select id="edit_association_category" name="association_category" class="form-control" required>
                                <?php
                                // Fetch ENUM values for association_category
                                $enum_sql = "SHOW COLUMNS FROM tbl_association WHERE Field = 'association_category'";
                                $enum_result = $connections->query($enum_sql);
                                $enum_row = $enum_result->fetch_assoc();
                                $enum_values = str_replace(["enum(", "')"], '', $enum_row['Type']);
                                $enum_values = explode("','", $enum_values);
                                foreach ($enum_values as $value) {
                                    echo "<option value=\"$value\">$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_association_name">Name:</label>
                            <input type="text" id="edit_association_name" name="association_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_association_area">Area:</label>
                            <input type="text" id="edit_association_area" name="association_area" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_association_president">President:</label>
                            <input type="text" id="edit_association_president" name="association_president" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="color_name_select_edit">Color Name:</label>
                            <select id="color_name_select_edit" class="form-control" onchange="updateColorPickerFromName()">
                                <option value="" data-color="#ffffff">Select Color</option>
                                <option value="Red" data-color="#ff0000">Red</option>
                                <option value="Green" data-color="#00ff00">Green</option>
                                <option value="Blue" data-color="#0000ff">Blue</option>
                                <!-- Add more color options as needed -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_association_color">Color Picker:</label>
                            <input type="color" id="color_picker_edit" name="association_color" class="form-control" onchange="updateColorName()" required>
                            <span id="color_display_edit">#ffffff</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit_association" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Association Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Association</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" id="delete_association_id" name="association_id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this association?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_association" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for handling modal interactions -->
<script>
function populateEditForm(id) {
    // Fetch the association data and populate the edit form (this part should ideally be dynamic)
    // For demonstration purposes, we'll use static data
    const association = <?php echo json_encode($associations); ?>.find(assoc => assoc.association_id === id);
    
    if (association) {
        document.getElementById('edit_association_id').value = association.association_id;
        document.getElementById('edit_association_category').value = association.association_category;
        document.getElementById('edit_association_name').value = association.association_name;
        document.getElementById('edit_association_area').value = association.association_area;
        document.getElementById('edit_association_president').value = association.association_president;
        document.getElementById('color_picker_edit').value = association.association_color;
        document.getElementById('color_display_edit').textContent = association.association_color;
        
        // Set the dropdown to match the color picker value
        const select = document.getElementById('color_name_select_edit');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].dataset.color === association.association_color) {
                select.selectedIndex = i;
                break;
            }
        }
    }
}

function setDeleteId(id) {
    document.getElementById('delete_association_id').value = id;
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
