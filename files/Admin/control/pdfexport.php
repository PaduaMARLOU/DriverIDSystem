<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoload.php'; // Adjust the path as necessary

use Dompdf\Dompdf;
use Dompdf\Options;

include '../../../connections.php'; // Your database connection

// Check if fields are passed
if (!isset($_GET['fields']) || empty($_GET['fields'])) {
    die("No fields selected for export.");
}

$selectedFields = $_GET['fields'];
$selectedTables = $_GET['tables'];
$numRows = isset($_GET['num_rows']) ? intval($_GET['num_rows']) : 100; // Default to 100 rows
$searchTerm = isset($_GET['search']) ? $connections->real_escape_string($_GET['search']) : ''; // Sanitize search term

// Initialize Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// Prepare HTML content
$html = '
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0; /* No margin for the body */
    }
    table {
        width: 100%; /* Table takes the full width */
        border-collapse: collapse; /* Collapse borders */
        table-layout: auto; /* Auto layout for columns */
    }
    th, td {
        border: 1px solid black; /* Border for table cells */
        padding: 5px; /* Padding for cells */
        text-align: left; /* Align text to the left */
        vertical-align: top; /* Align text to the top */
        overflow-wrap: break-word; /* Allow words to break to the next line */
        word-wrap: break-word; /* For older browsers */
        max-width: 300px; /* Set a max width for cells to aid in column fitting */
    }
    tr {
        page-break-inside: avoid; /* Prevent page breaks inside table rows */
    }
    .page-break {
        page-break-after: always; /* Force a page break after the current row */
    }
</style>
<h1>Exported Data</h1>
<table>
    <tr>';

// Set header row in the table
foreach ($selectedFields as $field) {
    list($table, $column) = explode('.', $field);
    $html .= '<th>' . htmlspecialchars($column) . '</th>';
}
$html .= '</tr>';

// Fetch data for each selected table and field
foreach ($selectedTables as $table) {
    // Prepare sanitized field list
    $fieldList = implode(", ", array_map('htmlspecialchars', $selectedFields));
    
    // Prepare the search condition
    $searchCondition = !empty($searchTerm) ? " WHERE " . implode(" LIKE '%$searchTerm%' OR ", $selectedFields) . " LIKE '%$searchTerm%'" : "";

    // Query to fetch data
    $query = "SELECT $fieldList FROM $table$searchCondition LIMIT $numRows";
    $result = $connections->query($query);

    // Check for errors
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($selectedFields as $field) {
                list($table, $column) = explode('.', $field); // Get the column name
                // Check if the column exists in the result set
                if (isset($row[$column])) {
                    $html .= '<td>' . htmlspecialchars($row[$column]) . '</td>';
                } else {
                    $html .= '<td>N/A</td>'; // Handle missing fields
                }
            }
            $html .= '</tr>';
        }
    }
}

$html .= '</table>';

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A3', 'landscape'); // Set to A1 landscape

// Render the PDF
$dompdf->render();

// Output the generated PDF to a file
$output = $dompdf->output();
$filePath = 'vendor/temp/temp_exported_data.pdf'; // Path where the PDF will be saved
file_put_contents($filePath, $output);

// Redirect to the PDF file
header("Location: $filePath");
exit;
