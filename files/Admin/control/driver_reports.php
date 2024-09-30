<?php
include '../../../connections.php'; // Database connection

$selectedFields = isset($_GET['fields']) ? $_GET['fields'] : [];
$numRows = isset($_GET['num_rows']) ? intval($_GET['num_rows']) : 100; // Default to 100 rows
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Search term

// Group fields by table
$fieldsByTable = [];
foreach ($selectedFields as $field) {
    list($table, $column) = explode('.', $field);
    $fieldsByTable[$table][] = $column;
}

// Check if any fields were selected
if (!empty($fieldsByTable)) {
    // Loop through each table and construct a query
    foreach ($fieldsByTable as $table => $fields) {
        // Sanitize field names
        $fieldList = implode(", ", array_map('htmlspecialchars', $fields));

        // Prepare the search condition
        $searchCondition = !empty($searchTerm) ? " WHERE " . implode(" LIKE '%$searchTerm%' OR ", $fields) . " LIKE '%$searchTerm%'" : "";

        // Sample query using selected fields with a limit on the number of rows
        $query = "SELECT $fieldList FROM $table$searchCondition LIMIT $numRows"; // Selecting from the specific table
        
        // Execute the query
        $result = $connections->query($query);

        // Check for query errors
        if ($connections->error) {
            echo "<p>Error: " . htmlspecialchars($connections->error) . "</p>";
            continue;
        }

        if ($result && $result->num_rows > 0) {
            echo "<h3>Results from $table</h3>";
            echo "<table border='1'>";
            echo "<tr>";

            // Output table headers
            foreach ($fields as $field) {
                echo "<th>" . htmlspecialchars($field) . "</th>";
            }

            echo "</tr>";

            // Output rows of data
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($fields as $field) {
                    // Check if the field exists in the result to avoid undefined index errors
                    if (isset($row[$field])) {
                        echo "<td>" . htmlspecialchars($row[$field]) . "</td>";
                    } else {
                        echo "<td>N/A</td>"; // Handle missing fields
                    }
                }
                echo "</tr>";
            }

            echo "</table><br>";
        } else {
            echo "<h3>Results from $table</h3>";
            echo "No results found.<br>";
        }
    }
} else {
    echo "<p>Please select at least one field to display.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Fields from Tables</title>
    <style>
        .fields-container {
            display: none; /* Hide by default */
            margin-left: 20px;
        }
    </style>
</head>
<body>

<h3>Select Tables and Fields</h3>

<form id="fieldsForm" method="GET" action="">
    <label><input type="checkbox" id="tbl_driver_cb" name="tables[]" value="tbl_driver" onchange="toggleFields(this)" <?php echo in_array('tbl_driver', $_GET['tables'] ?? []) ? 'checked' : ''; ?>> tbl_driver</label><br>
    <div id="tbl_driver_fields" class="fields-container" style="<?php echo in_array('tbl_driver', $_GET['tables'] ?? []) ? 'display: block;' : ''; ?>">
        <label><input type="checkbox" name="fields[]" value="tbl_driver.driver_id" <?php echo in_array('tbl_driver.driver_id', $selectedFields) ? 'checked' : ''; ?>> driver_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.driver_category" <?php echo in_array('tbl_driver.driver_category', $selectedFields) ? 'checked' : ''; ?>> driver_category</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.formatted_id" <?php echo in_array('tbl_driver.formatted_id', $selectedFields) ? 'checked' : ''; ?>> formatted_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.first_name" <?php echo in_array('tbl_driver.first_name', $selectedFields) ? 'checked' : ''; ?>> first_name</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.middle_name" <?php echo in_array('tbl_driver.middle_name', $selectedFields) ? 'checked' : ''; ?>> middle_name</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.last_name" <?php echo in_array('tbl_driver.last_name', $selectedFields) ? 'checked' : ''; ?>> last_name</label><br>
        <!-- Add more fields as needed -->
        <label><input type="checkbox" name="fields[]" value="tbl_driver.age" <?php echo in_array('tbl_driver.age', $selectedFields) ? 'checked' : ''; ?>> age</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.birth_date" <?php echo in_array('tbl_driver.birth_date', $selectedFields) ? 'checked' : ''; ?>> birth_date</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.birth_place" <?php echo in_array('tbl_driver.birth_place', $selectedFields) ? 'checked' : ''; ?>> birth_place</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.sex" <?php echo in_array('tbl_driver.sex', $selectedFields) ? 'checked' : ''; ?>> sex</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.address" <?php echo in_array('tbl_driver.address', $selectedFields) ? 'checked' : ''; ?>> address</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.mobile_number" <?php echo in_array('tbl_driver.mobile_number', $selectedFields) ? 'checked' : ''; ?>> mobile_number</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.civil_status" <?php echo in_array('tbl_driver.civil_status', $selectedFields) ? 'checked' : ''; ?>> civil_status</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.religion" <?php echo in_array('tbl_driver.religion', $selectedFields) ? 'checked' : ''; ?>> religion</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.citizenship" <?php echo in_array('tbl_driver.citizenship', $selectedFields) ? 'checked' : ''; ?>> citizenship</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.height" <?php echo in_array('tbl_driver.height', $selectedFields) ? 'checked' : ''; ?>> height</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.weight" <?php echo in_array('tbl_driver.weight', $selectedFields) ? 'checked' : ''; ?>> weight</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.name_to_notify" <?php echo in_array('tbl_driver.name_to_notify', $selectedFields) ? 'checked' : ''; ?>> name_to_notify</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.relationship" <?php echo in_array('tbl_driver.relationship', $selectedFields) ? 'checked' : ''; ?>> relationship</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.num_to_notify" <?php echo in_array('tbl_driver.num_to_notify', $selectedFields) ? 'checked' : ''; ?>> num_to_notify</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.vehicle_ownership" <?php echo in_array('tbl_driver.vehicle_ownership', $selectedFields) ? 'checked' : ''; ?>> vehicle_ownership</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.verification_stat" <?php echo in_array('tbl_driver.verification_stat', $selectedFields) ? 'checked' : ''; ?>> verification_stat</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.fk_association_id" <?php echo in_array('tbl_driver.fk_association_id', $selectedFields) ? 'checked' : ''; ?>> fk_association_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.driver_registered" <?php echo in_array('tbl_driver.driver_registered', $selectedFields) ? 'checked' : ''; ?>> driver_registered</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.renew_stat" <?php echo in_array('tbl_driver.renew_stat', $selectedFields) ? 'checked' : ''; ?>> renew_stat</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.fk_sched_id" <?php echo in_array('tbl_driver.fk_sched_id', $selectedFields) ? 'checked' : ''; ?>> fk_sched_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_driver.fk_vehicle_id" <?php echo in_array('tbl_driver.fk_vehicle_id', $selectedFields) ? 'checked' : ''; ?>> fk_vehicle_id</label><br>
    </div>

    <label><input type="checkbox" id="tbl_vehicle_cb" name="tables[]" value="tbl_vehicle" onchange="toggleFields(this)" <?php echo in_array('tbl_vehicle', $_GET['tables'] ?? []) ? 'checked' : ''; ?>> tbl_vehicle</label><br>
    <div id="tbl_vehicle_fields" class="fields-container" style="<?php echo in_array('tbl_vehicle', $_GET['tables'] ?? []) ? 'display: block;' : ''; ?>">
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.vehicle_id" <?php echo in_array('tbl_vehicle.vehicle_id', $selectedFields) ? 'checked' : ''; ?>> vehicle_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.fk_driver_id" <?php echo in_array('tbl_vehicle.fk_driver_id', $selectedFields) ? 'checked' : ''; ?>> fk_driver_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.vehicle_category" <?php echo in_array('tbl_vehicle.vehicle_category', $selectedFields) ? 'checked' : ''; ?>> vehicle_category</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.name_of_owner" <?php echo in_array('tbl_vehicle.name_of_owner', $selectedFields) ? 'checked' : ''; ?>> name_of_owner</label><br>
        <!-- Add more fields as needed -->
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.addr_of_owner" <?php echo in_array('tbl_vehicle.addr_of_owner', $selectedFields) ? 'checked' : ''; ?>> addr_of_owner</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.owner_phone_num" <?php echo in_array('tbl_vehicle.owner_phone_num', $selectedFields) ? 'checked' : ''; ?>> owner_phone_num</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.vehicle_color" <?php echo in_array('tbl_vehicle.vehicle_color', $selectedFields) ? 'checked' : ''; ?>> vehicle_color</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.brand" <?php echo in_array('tbl_vehicle.brand', $selectedFields) ? 'checked' : ''; ?>> brand</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.brand_plate_num" <?php echo in_array('tbl_vehicle.brand_plate_num', $selectedFields) ? 'checked' : ''; ?>> brand_plate_num</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_vehicle.vehicle_registered" <?php echo in_array('tbl_vehicle.vehicle_registered', $selectedFields) ? 'checked' : ''; ?>> vehicle_registered</label><br>
    </div>

    <label><input type="checkbox" id="tbl_association_cb" name="tables[]" value="tbl_association" onchange="toggleFields(this)" <?php echo in_array('tbl_association', $_GET['tables'] ?? []) ? 'checked' : ''; ?>> tbl_association</label><br>
    <div id="tbl_association_fields" class="fields-container" style="<?php echo in_array('tbl_association', $_GET['tables'] ?? []) ? 'display: block;' : ''; ?>">
        <label><input type="checkbox" name="fields[]" value="tbl_association.association_id" <?php echo in_array('tbl_association.association_id', $selectedFields) ? 'checked' : ''; ?>> association_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_association.association_category" <?php echo in_array('tbl_association.association_category', $selectedFields) ? 'checked' : ''; ?>> association_category</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_association.association_name" <?php echo in_array('tbl_association.association_name', $selectedFields) ? 'checked' : ''; ?>> association_name</label><br>
        <!-- Add more fields as needed -->
        <label><input type="checkbox" name="fields[]" value="tbl_association.association_area" <?php echo in_array('tbl_association.association_area', $selectedFields) ? 'checked' : ''; ?>> association_area</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_association.association_president" <?php echo in_array('tbl_association.association_president', $selectedFields) ? 'checked' : ''; ?>> association_president</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_association.association_color" <?php echo in_array('tbl_association.association_color', $selectedFields) ? 'checked' : ''; ?>> association_color</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_association.association_color_name" <?php echo in_array('tbl_association.association_color_name', $selectedFields) ? 'checked' : ''; ?>> association_color_name</label><br>
    </div>

    <label><input type="checkbox" id="tbl_violation_cb" name="tables[]" value="tbl_violation" onchange="toggleFields(this)" <?php echo in_array('tbl_violation', $_GET['tables'] ?? []) ? 'checked' : ''; ?>> tbl_violation</label><br>
    <div id="tbl_violation_fields" class="fields-container" style="<?php echo in_array('tbl_violation', $_GET['tables'] ?? []) ? 'display: block;' : ''; ?>">
        <label><input type="checkbox" name="fields[]" value="tbl_violation.violation_id" <?php echo in_array('tbl_violation.violation_id', $selectedFields) ? 'checked' : ''; ?>> violation_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_violation.fk_driver_id" <?php echo in_array('tbl_violation.fk_driver_id', $selectedFields) ? 'checked' : ''; ?>> fk_driver_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_violation.violation_category" <?php echo in_array('tbl_violation.violation_category', $selectedFields) ? 'checked' : ''; ?>> violation_category</label><br>
        <!-- Add more fields as needed -->
        <label><input type="checkbox" name="fields[]" value="tbl_violation.violation_description" <?php echo in_array('tbl_violation.violation_description', $selectedFields) ? 'checked' : ''; ?>> violation_description</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_violation.violation_date" <?php echo in_array('tbl_violation.violation_date', $selectedFields) ? 'checked' : ''; ?>> violation_date</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_violation.renewed_date" <?php echo in_array('tbl_violation.renewed_date', $selectedFields) ? 'checked' : ''; ?>> renewed_date</label><br>
    </div>

    <label><input type="checkbox" id="tbl_appointment_cb" name="tables[]" value="tbl_appointment" onchange="toggleFields(this)" <?php echo in_array('tbl_appointment', $_GET['tables'] ?? []) ? 'checked' : ''; ?>> tbl_appointment</label><br>
    <div id="tbl_appointment_fields" class="fields-container" style="<?php echo in_array('tbl_appointment', $_GET['tables'] ?? []) ? 'display: block;' : ''; ?>">
        <label><input type="checkbox" name="fields[]" value="tbl_appointment.sched_id" <?php echo in_array('tbl_appointment.sched_id', $selectedFields) ? 'checked' : ''; ?>> sched_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_appointment.fk_driver_id" <?php echo in_array('tbl_appointment.fk_driver_id', $selectedFields) ? 'checked' : ''; ?>> fk_driver_id</label><br>
        <label><input type="checkbox" name="fields[]" value="tbl_appointment.DATE" <?php echo in_array('tbl_appointment.DATE', $selectedFields) ? 'checked' : ''; ?>> DATE</label><br>
        <!-- Add more fields as needed -->
        <label><input type="checkbox" name="fields[]" value="tbl_appointment.booking_date" <?php echo in_array('tbl_appointment.booking_date', $selectedFields) ? 'checked' : ''; ?>> booking_date</label><br>
    </div>

    <label for="num_rows">Number of Rows to Show:</label>
    <input type="number" id="num_rows" name="num_rows" min="1" value="<?php echo htmlspecialchars($numRows); ?>">

    <label for="search">Search:</label>
    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">

    <button type="submit">Show Results</button>
</form>

<form method="GET" action="excelexport.php" onsubmit="captureSelectedFields()">
    <input type="hidden" name="num_rows" value="<?php echo htmlspecialchars($numRows); ?>">
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Export to Excel</button>
</form>

<form method="GET" action="pdfexport.php" onsubmit="event.preventDefault(); printPDF();">
    <input type="hidden" name="num_rows" value="<?php echo htmlspecialchars($numRows); ?>">
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <input type="hidden" name="fields" value="<?php echo htmlspecialchars(json_encode($selectedFields)); ?>">
    <input type="hidden" name="tables" value="<?php echo htmlspecialchars(json_encode($selectedTables)); ?>">
    <button type="submit">Export to PDF</button>
</form>


<script>
function toggleFields(checkbox) {
    const tableId = `tbl_${checkbox.value.split('_')[1]}_fields`;
    const fieldsContainer = document.getElementById(tableId);

    // If this checkbox is checked, uncheck all others and hide their fields
    if (checkbox.checked) {
        // Uncheck all other parent checkboxes
        document.querySelectorAll('input[name="tables[]"]').forEach(otherParent => {
            if (otherParent !== checkbox) {
                otherParent.checked = false; // Uncheck other parent checkboxes
                const otherFieldsContainer = document.getElementById(`tbl_${otherParent.value.split('_')[1]}_fields`);
                otherFieldsContainer.style.display = 'none'; // Hide other fields containers

                // Uncheck all child checkboxes of other parents
                const otherChildCheckboxes = otherFieldsContainer.querySelectorAll('input[type="checkbox"]');
                otherChildCheckboxes.forEach(otherChild => {
                    otherChild.checked = false; // Uncheck other child's checkboxes
                });
            }
        });
    }

    // Show/hide this parent's fields
    fieldsContainer.style.display = checkbox.checked ? 'block' : 'none';

    // Check/uncheck child checkboxes based on parent checkbox state
    const childCheckboxes = fieldsContainer.querySelectorAll('input[type="checkbox"]');
    childCheckboxes.forEach(child => {
        child.checked = checkbox.checked; // Match child's checked state with parent's
    });
}

function captureSelectedFields() {
    const selectedTables = [];
    const selectedFields = [];
    
    // Get all table checkboxes
    document.querySelectorAll('input[name="tables[]"]:checked').forEach(checkbox => {
        selectedTables.push(checkbox.value);
        
        // Get fields related to this table
        const fieldsContainer = document.getElementById(`tbl_${checkbox.value.split('_')[1]}_fields`);
        if (fieldsContainer) {
            fieldsContainer.querySelectorAll('input[name="fields[]"]:checked').forEach(fieldCheckbox => {
                selectedFields.push(fieldCheckbox.value);
            });
        }
    });

    // Update the hidden inputs in the export form
    const exportForm = document.querySelector('form[action="excelexport.php"]');
    
    // Clear previous values
    exportForm.querySelectorAll('input[name="tables[]"]').forEach(input => input.remove());
    exportForm.querySelectorAll('input[name="fields[]"]').forEach(input => input.remove());

    // Add selected tables
    selectedTables.forEach(table => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tables[]';
        input.value = table;
        exportForm.appendChild(input);
    });

    // Add selected fields
    selectedFields.forEach(field => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'fields[]';
        input.value = field;
        exportForm.appendChild(input);
    });
}

function printPDF() {
    // Open the PDF in a new window/tab
    var pdfUrl = 'pdfexport.php?' + new URLSearchParams(new FormData(document.querySelector('form'))).toString();
    var win = window.open(pdfUrl, '_blank');

    // Wait for the PDF to load, then trigger print
    win.onload = function() {
        win.print();
    };
}
</script>



</body>
</html>
