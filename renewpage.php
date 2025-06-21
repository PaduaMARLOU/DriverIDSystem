<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="driverportalcss/renewpagestyle.css">
    <link rel="icon" href="img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Renew Status Page - Barangay Estefania Driver's ID System</title>
</head>
<body>
    <style>
        * {
            text-decoration: none;
            outline: none;         
        }
    </style>
    <div class="container">
        <a href="index.php">
            <img src="img/Brgy. Estefania Logo (Old).png" alt="Barangay Estefania Logo" class="logo">
        </a>
        <h1>Check Your Renew Status</h1>
        <p><i>Ibutang ang imo nga Driver's ID Code, Example: ETRK-0000</i></p>
        <div class="input-container">
            <form action="renewprocess.php" method="POST">
                <input type="text" name="formatted_id" placeholder="Enter Renew Status" required>
                <button type="submit" class="btn-check">Check Status</button>
            </form>
        </div>
        <br>
        <br>
        <a href="index.php" class="back-btn">Back</a>
    </div>
</body>
</html>
