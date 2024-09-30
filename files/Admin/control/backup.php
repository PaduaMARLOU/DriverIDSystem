<?php
// Include the database connection
include '../../../connections.php'; // This should include your database connection

// Define the path where the backup will be saved
$backupDir = __DIR__ . '/backup/'; // Directory to save the backup file
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true); // Create directory if it doesn't exist
}

// Function to create the backup
function createBackup($backupDir) {
    $database = "driver_id_system2"; // Your database name
    $mysqldumpPath = 'C:/xampp/mysql/bin/mysqldump.exe'; // Full path to mysqldump
    $backupFile = $backupDir . 'backup-' . date('Y-m-d_H-i-s') . '.sql'; // Full path with filename
    $command = "$mysqldumpPath --opt -h localhost -u root $database > $backupFile";
    
    exec($command . ' 2>&1', $output, $return_var);
    if ($return_var !== 0) {
        echo "Database backup failed. Return value: $return_var<br>";
    } else {
        echo "Database backup succeeded.<br>";
    }
    return $backupFile;
}

// Function to handle file download
function downloadBackup($backupFile) {
    if (file_exists($backupFile)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backupFile));
        flush(); // Flush system output buffer
        readfile($backupFile); // Read the file and output it to the browser
        exit;
    } else {
        echo "Backup file does not exist.<br>";
    }
}

// Handle the form submission for uploading or downloading the backup
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['download'])) {
        // Download the latest backup
        $backupFile = createBackup($backupDir);
        downloadBackup($backupFile);
    } elseif (isset($_POST['upload'])) {
        // Handle upload (can be uploaded to a server or cloud storage)
        // For now, we just simulate an upload by printing a message
        echo "Simulate backup upload.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Manager</title>
</head>
<body>
    <h1>Backup Manager</h1>
    <form method="POST">
        <button type="submit" name="download">Download Backup</button>
    </form>
    <form action="upload.php" method="GET">
        <button type="submit" name="upload">Upload Backup</button>
    </form>
</body>
</html>
