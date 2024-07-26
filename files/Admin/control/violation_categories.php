<?php
include '../../../connections.php';

// Function to get the ENUM values from the column
function getEnumValues($connection, $table, $column) {
    $query = "SHOW COLUMNS FROM $table LIKE '$column'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $type = $row['Type'];
    preg_match("/^enum\((.*)\)$/", $type, $matches);
    $enum = str_getcsv($matches[1], ',', "'");
    return $enum;
}

// Function to alter the ENUM values
function alterEnumValues($connection, $table, $column, $newValues) {
    $enumString = implode("','", $newValues);
    $query = "ALTER TABLE $table MODIFY $column ENUM('$enumString')";
    return mysqli_query($connection, $query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_value'])) {
        $newValue = $_POST['new_value'];
        $referenceValue = $_POST['reference_value'];
        $position = $_POST['position'];
        
        $enumValues = getEnumValues($connections, 'tbl_violation', 'violation_category');
        
        if (!in_array($newValue, $enumValues)) {
            $referenceIndex = array_search($referenceValue, $enumValues);
            if ($position == 'before') {
                array_splice($enumValues, $referenceIndex, 0, $newValue);
            } else {
                array_splice($enumValues, $referenceIndex + 1, 0, $newValue);
            }
            alterEnumValues($connections, 'tbl_violation', 'violation_category', $enumValues);
        }
    } elseif (isset($_POST['delete_value'])) {
        $deleteValue = $_POST['delete_value'];
        $enumValues = getEnumValues($connections, 'tbl_violation', 'violation_category');
        if (in_array($deleteValue, $enumValues)) {
            $enumValues = array_diff($enumValues, [$deleteValue]);
            alterEnumValues($connections, 'tbl_violation', 'violation_category', $enumValues);
        }
    } elseif (isset($_POST['edit_value'])) {
        $originalValue = $_POST['original_value'];
        $newValue = $_POST['new_value_edit'];
        $enumValues = getEnumValues($connections, 'tbl_violation', 'violation_category');
        if (in_array($originalValue, $enumValues) && !in_array($newValue, $enumValues)) {
            $enumValues = array_map(function($value) use ($originalValue, $newValue) {
                return $value === $originalValue ? $newValue : $value;
            }, $enumValues);
            alterEnumValues($connections, 'tbl_violation', 'violation_category', $enumValues);
        }
    }
}

$enumValues = getEnumValues($connections, 'tbl_violation', 'violation_category');
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="../../../img/Brgy Estefania Logo.png" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Violation Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('../../../img/Barangay Estefania Hall.jpeg');
            background-size: cover;
            background-position: center;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 10px;
            background: #f9f9f9;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        form {
            display: inline;
        }
        input[type="text"], select {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 3px;
            width: 200px;
        }
        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .radio-group {
            display: flex;
            gap: 10px;
            margin: 5px 0;
        }
        .form-inline {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <button type="button" class="btn btn-secondary mt-3" onclick="window.close()">Back to Control Panel</button>
        <br><hr>
        <h1>Violation Categories</h1>
        <ul>
            <?php foreach ($enumValues as $value): ?>
                <li><?php echo htmlspecialchars($value); ?> 
                    <div>
                        <form method="POST" onsubmit="return confirmDelete()" style="display:inline;">
                            <input type="hidden" name="delete_value" value="<?php echo htmlspecialchars($value); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <form method="POST" onsubmit="return confirmEdit()" style="display:inline;" class="form-inline">
                            <input type="hidden" name="original_value" value="<?php echo htmlspecialchars($value); ?>">
                            <input type="text" name="new_value_edit" required>
                            <button type="submit" name="edit_value" class="btn btn-primary btn-sm">Edit</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCategoryModal">Add New Category</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" onsubmit="return confirmAdd()">
                        <div class="form-group">
                            <label for="new_value">New Category:</label>
                            <input type="text" class="form-control" name="new_value" required>
                        </div>
                        <div class="form-group">
                            <label for="reference_value">Reference Category:</label>
                            <select class="form-control" name="reference_value" required>
                                <?php foreach ($enumValues as $value): ?>
                                    <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <label>Position:</label>
                        <div class="radio-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="position" value="before" required> 
                                <label class="form-check-label">Before</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="position" value="after" required> 
                                <label class="form-check-label">After</label>
                            </div>
                        </div>
                        <button type="submit" name="add_value" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this category?');
        }

        function confirmEdit() {
            return confirm('Are you sure you want to edit this category?');
        }

        function confirmAdd() {
            return confirm('Are you sure you want to add this category?');
        }
    </script>
</body>
</html>
