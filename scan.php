<?php
include('connections.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <!-- Correct CDN link for html5-qrcode -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        #qr-reader {
            width: 500px;
            margin: auto;
            padding: 20px;
        }
        #result {
            margin-top: 20px;
            font-size: 18px;
            color: green;
        }
        #error {
            margin-top: 20px;
            font-size: 18px;
            color: red;
        }
    </style>
</head>
<body>
    <h2>Driver Verification via QR Code</h2>
    <div id="qr-reader"></div>
    <div id="result"></div>
    <div id="error"></div>

    <a href="index.php" class="back-btn">Back</a>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);
            document.getElementById("error").innerText = "";

            // Redirect to scan_detected.php with the formatted_id
            window.location.href = "scan_detected.php?formatted_id=" + encodeURIComponent(decodedText);
        }

        function onScanFailure(error) {
            // Show a user-friendly error message
            document.getElementById("error").innerText = "Please Scan Valid Driver QR Code.";
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", 
            { fps: 10, qrbox: { width: 300, height: 300 } }, 
            false
        );
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>
</html>
