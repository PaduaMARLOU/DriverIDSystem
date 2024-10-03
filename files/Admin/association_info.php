<?php
// Include your database connection
include "../../connections.php";

// Check if association_id is passed in the URL
if (isset($_GET['association_id'])) {
    $association_id = intval($_GET['association_id']);

    // Query to retrieve association name and drivers' information
    $query = "SELECT a.association_name, d.formatted_id, d.last_name, d.first_name, d.suffix_name, d.middle_name, d.nickname, 
              d.address, d.mobile_number, d.renew_stat 
              FROM tbl_driver d
              INNER JOIN tbl_association a ON d.fk_association_id = a.association_id
              WHERE d.fk_association_id = ? AND d.verification_stat = 'Registered'";
    
    // Prepare and execute the query
    if ($stmt = mysqli_prepare($connections, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $association_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if any drivers are found
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $association_name = htmlspecialchars($row['association_name']);
            echo "<h2>Drivers List for Association: $association_name</h2>";

            // Start the table
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<thead>
                    <tr>
                        <th>Formatted ID</th>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>Mobile Number</th>
                        <th>Renewal Status</th>
                    </tr>
                  </thead>";
            echo "<tbody>";

            // Loop through the drivers and display the information
            do {
                $formatted_id = htmlspecialchars($row['formatted_id']);
                $full_name = htmlspecialchars($row['last_name']) . ", " . htmlspecialchars($row['first_name']) . " " . 
                             htmlspecialchars($row['suffix_name']) . " " . htmlspecialchars($row['middle_name']) . 
                             ' "' . htmlspecialchars($row['nickname']) . '"';
                $address = htmlspecialchars($row['address']);
                $mobile_number = htmlspecialchars($row['mobile_number']);
                $renew_stat = htmlspecialchars($row['renew_stat']);

                // Display the driver information in the table
                echo "<tr>
                        <td>$formatted_id</td>
                        <td>$full_name</td>
                        <td>$address</td>
                        <td>$mobile_number</td>
                        <td>$renew_stat</td>
                      </tr>";
            } while ($row = mysqli_fetch_assoc($result));

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No drivers found for this association.</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the statement.";
    }
} else {
    echo "<p>No association selected.</p>";
}

// Close the database connection
mysqli_close($connections);
?>

<!-- Add Export Buttons -->
<br>
<a href="association_export_xlsx.php?association_id=<?php echo $association_id; ?>" class="btn btn-success">Export to Excel</a>
<a href="association_export_pdf.php?association_id=<?php echo $association_id; ?>" class="btn btn-danger">Export to PDF</a>
