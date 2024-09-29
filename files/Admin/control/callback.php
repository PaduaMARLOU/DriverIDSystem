<?php
require 'vendor/vendor/autoload.php'; // Include the Google API client library

session_start();

// Load the Google client
$client = new Google_Client();
$client->setApplicationName('Estefania Driver ID System');
$client->setScopes(Google_Service_Drive::DRIVE_FILE);
$client->setAuthConfig('vendor/vendor/client_secret_703739710418-768o2m7vtri2kb4vpe87srm7nv6q8dl1.apps.googleusercontent.com.json');
$client->setRedirectUri('http://localhost/IDSystemLessen/files/Admin/control/callback.php');

if (isset($_GET['code'])) {
    try {
        // Exchange the authorization code for an access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        // Store the token in the session
        $_SESSION['access_token'] = $token;

        // Redirect to the upload page after successful authentication
        header('Location: http://localhost/IDSystemLessen/files/Admin/control/upload.php');
        exit();
    } catch (Exception $e) {
        // Log the error
        error_log('Error fetching access token: ' . $e->getMessage());
        echo "Error fetching access token: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "No authorization code found.<br>";
}
?>
