<?php
require 'vendor/vendor/autoload.php'; // Include the Google API client library

session_start();

// Load the Google client
$client = new Google_Client();
$client->setApplicationName('Estefania Driver ID System');
$client->setScopes(Google_Service_Drive::DRIVE_FILE);
$client->setAuthConfig('vendor/vendor/client_secret_703739710418-768o2m7vtri2kb4vpe87srm7nv6q8dl1.apps.googleusercontent.com.json');
$client->setRedirectUri('http://localhost/IDSystemLessen/files/Admin/control/callback.php');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Create Google Drive service
    $service = new Google_Service_Drive($client);

    // Handle file upload when the button is clicked
    if (isset($_POST['upload'])) {
        // Path to the SQL file you want to upload
        $filePath = 'backup/backup-2024-09-29_21-49-12'; // Change this to the path of your SQL file

        // Check if the file exists
        if (!file_exists($filePath)) {
            die("File not found: $filePath");
        }

        // Create a new file in Google Drive
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => basename($filePath), // File name on Google Drive
            'parents' => ['1QT2B0Unm86_fKzYTPR1CrYMVlKpLxtmZ'] // Replace with your Google Drive folder ID
        ]);

        // Upload the file
        try {
            $content = file_get_contents($filePath);
            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/sql', // MIME type for SQL files
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);
            
            echo "File uploaded successfully. File ID: " . $file->id;
        } catch (Exception $e) {
            echo "An error occurred: " . htmlspecialchars($e->getMessage());
        }
    }
} else {
    // Redirect to the Google authorization page
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Backup</title>
</head>
<body>
    <h1>Upload Backup to Google Drive</h1>
    <form method="POST">
        <button type="submit" name="upload">Upload Backup</button>
    </form>
</body>
</html>
