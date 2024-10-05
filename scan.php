<?php
include('connections.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        #qr-reader {
            width: 500px;
            margin: auto;
            padding: 20px;
        }
        #result, #error {
            margin-top: 20px;
            font-size: 18px;
        }
        #result {
            color: green;
        }
        #error {
            color: red;
        }
        .input-section {
            margin-top: 30px;
            text-align: center;
        }
        .input-section input[type="text"] {
            padding: 10px;
            font-size: 18px;
            width: 300px;
        }
        .input-section input[type="submit"] {
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Driver Verification via QR Code or Code Input</h2>

    <div id="qr-reader"></div>
    <div id="result"></div>
    <div id="error"></div>

    <div class="input-section">
        <h3>Or Input Driver's Code Here</h3>
        <form id="code-form" method="GET" action="scan_detected.php">
            <input type="text" name="formatted_id" placeholder="Enter Driver's Code" required>
            <input type="submit" value="Submit">
        </form>
    </div>

    <a href="index.php" class="back-btn">Back</a>

    <script>
        let scanLock = false; // To prevent multiple submissions

        function onScanSuccess(decodedText, decodedResult) {
            if (scanLock) return; // Prevent multiple scans

            scanLock = true; // Lock scanning after first success
            console.log(`Code matched = ${decodedText}`, decodedResult);
            document.getElementById("error").innerText = "";

            // Create a custom formatted date and time
            var currentDateTime = new Date();

            // Format Date as YYYY-MM-DD (for Date field in Google Forms)
            var year = currentDateTime.getFullYear();
            var month = String(currentDateTime.getMonth() + 1).padStart(2, '0'); // Month is zero-based
            var day = String(currentDateTime.getDate()).padStart(2, '0');
            var formattedDate = `${year}-${month}-${day}`; // Output: YYYY-MM-DD

            // Format Time as HH:MM (24-hour format for Time field in Google Forms)
            var hours = String(currentDateTime.getHours()).padStart(2, '0');
            var minutes = String(currentDateTime.getMinutes()).padStart(2, '0');
            var formattedTime = `${hours}:${minutes}`; // Output: HH:MM (24-hour format)

            // Log the scan to Google Forms
            var formUrl = "https://docs.google.com/forms/d/e/1FAIpQLSfWG3tIaGyqC8PQNpOX87UE4cb-StMpgwML2r6lgpu_Yg11OA/formResponse";
            var formattedIdEntry = "entry.1759240348"; // Entry ID for formatted_id
            var dateEntry = "entry.682659440"; // Entry ID for Date
            var timeEntry = "entry.1536420081"; // Entry ID for Time

            var formFullUrl = formUrl + 
                "?" + formattedIdEntry + "=" + encodeURIComponent(decodedText) +
                "&" + dateEntry + "=" + encodeURIComponent(formattedDate) +
                "&" + timeEntry + "=" + encodeURIComponent(formattedTime);

            // Send the GET request to Google Forms
            fetch(formFullUrl, { method: "GET", mode: "no-cors" })
                .then(() => {
                    console.log('Logged to Google Form successfully');
                    // Redirect to scan_detected.php with the formatted_id after logging
                    setTimeout(() => {
                        window.location.href = "scan_detected.php?formatted_id=" + encodeURIComponent(decodedText);
                    }, 500); // Delay to ensure form submission happens
                })
                .catch((error) => {
                    console.error('Error logging to Google Form:', error);
                });
        }


        function onScanFailure(error) {
            document.getElementById("error").innerText = "Please Scan a Valid Driver QR Code.";
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
