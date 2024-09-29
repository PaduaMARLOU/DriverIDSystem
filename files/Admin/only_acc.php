<?php
session_start(); // Ensure session is started

include("../../connections.php");

// Check if the user is logged in
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Fetch the account type and id from the database
    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];
    $user_id = $fetch["admin_id"]; // Store logged-in user ID
}

// Fetch the current user's data
$current_user_query = mysqli_query($connections, "SELECT username, password FROM tbl_admin WHERE admin_id='$user_id'");
$current_user = mysqli_fetch_assoc($current_user_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Account</title>
    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
    <style>
        <?php include("admin styles/admin_verify.css"); ?>
    </style>
</head>

<body>
    <h2>Modify Your Account</h2>

    <form action="update_acc.php" method="POST" onsubmit="return validateForm()">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($current_user['username']); ?>" required>

        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="hidden" name="admin_id" value="<?php echo $user_id; ?>">
        
        <button type="submit">Update</button>
    </form>

    <a href='index.php' class='back'>Back</a>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
