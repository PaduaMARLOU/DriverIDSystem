<?php
error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 1); // Display errors on the screen
ob_start(); // Start output buffering

require 'vendor/autoload.php'; // Adjust the path to PhpSpreadsheet autoload.php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../../../connections.php'; // Your database connection

// Check if fields are passed
if (!isset($_GET['fields']) || empty($_GET['fields'])) {
    die("No fields selected for export.");
}

$selectedFields = $_GET['fields'];
$selectedTables = $_GET['tables'];
$numRows = isset($_GET['num_rows']) ? intval($_GET['num_rows']) : 100; // Default to 100 rows
$searchTerm = isset($_GET['search']) ? $connections->real_escape_string($_GET['search']) : ''; // Sanitize search term

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header row in the spreadsheet
$headerRow = 1;
foreach ($selectedFields as $index => $field) {
    list($table, $column) = explode('.', $field);
    $sheet->setCellValueByColumnAndRow($index + 1, $headerRow, $column);
}

// Fetch data for each selected table and field
$rowNumber = 2; // Start outputting data from the second row
foreach ($selectedTables as $table) {
    // Prepare sanitized field list
    $fieldList = implode(", ", array_map('htmlspecialchars', $selectedFields));
    
    // Prepare the search condition
    $searchCondition = !empty($searchTerm) ? " WHERE " . implode(" LIKE '%$searchTerm%' OR ", $selectedFields) . " LIKE '%$searchTerm%'" : "";

    // Query to fetch data
    $query = "SELECT $fieldList FROM $table$searchCondition LIMIT $numRows";

    // Debugging: Print the query
    // echo $query; // Uncomment this to see the query being executed
    $result = $connections->query($query);

    // Check for errors
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($selectedFields as $index => $field) {
                list($table, $column) = explode('.', $field); // Get the column name
                // Check if the column exists in the result set
                if (isset($row[$column])) {
                    $sheet->setCellValueByColumnAndRow($index + 1, $rowNumber, $row[$column]);
                } else {
                    $sheet->setCellValueByColumnAndRow($index + 1, $rowNumber, 'N/A'); // Handle missing fields
                }
            }
            $rowNumber++;
        }
    }
}

// Auto-fit column widths
foreach (range(1, count($selectedFields)) as $index) {
    $sheet->getColumnDimensionByColumn($index)->setAutoSize(true);
}

// Auto-fit row heights
foreach (range(1, $rowNumber - 1) as $row) {
    $sheet->getRowDimension($row)->setRowHeight(-1); // Setting to -1 to auto-adjust
}

// Clean the output buffer to prevent any unwanted output before headers
ob_end_clean(); // Clean the output buffer

// Set filename and headers for download
$filename = 'exported_data.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Create the writer and save to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
