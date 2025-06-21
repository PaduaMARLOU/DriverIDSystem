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
    global $database_host, $database_user, $database_pass, $database_name;
    
    // Path to mysqldump (ensure this is correctly set if the script is on a remote or shared hosting server)
    $mysqldumpPath = 'mysqldump'; // Assuming `mysqldump` is in the PATH
    $backupFile = $backupDir . 'backup-' . date('Y-m-d_H-i-s') . '.sql'; // Full path with filename
    
    // Construct the command
    $command = "$mysqldumpPath --opt -h $database_host -u $database_user -p$database_pass $database_name > $backupFile";
    
    // Execute the command
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

<?php
/*
    // Enable error reporting for troubleshooting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection variables
    $database_host = "sql213.byetcluster.com";
    $database_user = "if0_37589390";
    $database_pass = "EFtjNSt3THp55";
    $database_name = "if0_37589390_driver_id_system";

    // Define the path where the backup will be saved
    $backupDir = __DIR__ . '/backup/'; // Directory to save the backup file
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0777, true); // Create directory if it doesn't exist
    }

    // Function to create the backup without using mysqldump
    function createBackup($backupDir, $database_host, $database_user, $database_pass, $database_name) {
        $backupFile = $backupDir . 'backup-' . date('Y-m-d_H-i-s') . '.sql';

        $db = new mysqli($database_host, $database_user, $database_pass, $database_name);

        if ($db->connect_error) {
            die("Connection failed during backup creation: " . $db->connect_error . "<br>");
        }

        $tables = $db->query("SHOW TABLES");
        $sql = "";

        while ($row = $tables->fetch_array()) {
            $table = $row[0];
            $createTable = $db->query("SHOW CREATE TABLE `$table`")->fetch_array()[1];
            $sql .= "$createTable;\n\n";
            
            $data = $db->query("SELECT * FROM `$table`");
            while ($dataRow = $data->fetch_assoc()) {
                $values = array_map([$db, 'real_escape_string'], array_values($dataRow));
                $sql .= "INSERT INTO `$table` VALUES ('" . implode("','", $values) . "');\n";
            }
            $sql .= "\n\n";
        }

        file_put_contents($backupFile, $sql);
        $db->close();

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

    // Handle the form submission for downloading the backup
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['download'])) {
        // Create a backup and initiate download
        $backupFile = createBackup($backupDir, $database_host, $database_user, $database_pass, $database_name);
        downloadBackup($backupFile);
    }
*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Manager</title>
    <link rel="icon" href="../../../img/database.png" type="image/png">
    <link rel="stylesheet" href="../backup style/backup.css">
</head>
<body>
    <div class="centered-content">
        <h1>Backup Manager</h1>
        <form method="POST">
            <button type="submit" name="download">Click this to Download Backup</button>
        </form>

        <!-- Back Button -->
        <button class="for-back-button" onclick="window.history.back()">or Go Back</button>

        <br><br>
        <p>Please ensure to store the downloaded database on secure spaces.</p>
    </div>
    <!--
    <form action="upload.php" method="GET">
        <button type="submit" name="upload">Upload Backup</button>
    </form> -->
</body>
</html>
