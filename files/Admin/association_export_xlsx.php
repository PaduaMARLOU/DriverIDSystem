<?php
// Turn off output buffering only if there is an active buffer
if (ob_get_length()) ob_end_clean();

// Include the required library for Excel export
require 'control/vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Include database connection
include "../../connections.php"; // Make sure there's no whitespace here

// Check if association_id is passed
if (isset($_GET['association_id'])) {
    $association_id = intval($_GET['association_id']);

    // Query to retrieve association and driver data
    $query = "SELECT a.association_name, d.formatted_id, d.last_name, d.first_name, d.suffix_name, d.middle_name, 
              d.nickname, d.address, d.mobile_number, d.renew_stat
              FROM tbl_driver d
              INNER JOIN tbl_association a ON d.fk_association_id = a.association_id
              WHERE d.fk_association_id = ? AND d.verification_stat = 'Registered'";
    
    $stmt = mysqli_prepare($connections, $query);
    mysqli_stmt_bind_param($stmt, 'i', $association_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the table headers
        $sheet->setCellValue('A1', 'Formatted ID');
        $sheet->setCellValue('B1', 'Full Name');
        $sheet->setCellValue('C1', 'Address');
        $sheet->setCellValue('D1', 'Mobile Number');
        $sheet->setCellValue('E1', 'Renewal Status');

        // Fill the rows with data
        $rowCount = 2;
        while ($row = mysqli_fetch_assoc($result)) {
            $full_name = $row['last_name'] . ", " . $row['first_name'] . " " . $row['suffix_name'] . " " . $row['middle_name'] . 
                         ' "' . $row['nickname'] . '"';
            
            $sheet->setCellValue('A' . $rowCount, $row['formatted_id']);
            $sheet->setCellValue('B' . $rowCount, $full_name);
            $sheet->setCellValue('C' . $rowCount, $row['address']);
            $sheet->setCellValue('D' . $rowCount, $row['mobile_number']);
            $sheet->setCellValue('E' . $rowCount, $row['renew_stat']);
            $rowCount++;
        }

        // Clear any previous output buffers before sending headers
        if (ob_get_length()) ob_end_clean();

        // Output headers to download the Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="association_drivers.xlsx"');
        header('Cache-Control: max-age=0');

        // Write the file to output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    } else {
        echo "No data available.";
    }
} else {
    echo "Association ID not provided.";
}
?>
