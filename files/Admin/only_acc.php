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
    <title>Update Account</title>
    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
    <style>
        <?php include("admin styles/admin_verify.css"); ?>

        * {
            outline: none;
        }

        h2 {
            position: absolute;
            top: 2rem;
            font-size: 30px;
            color: #51565D;
        }

        .form {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #74777A;
            border-radius: 4px;
            top: 2rem;
            height: 25rem;
            width: 20rem;
            left: 23rem;
            margin: -20px;
            box-shadow: 1px 1px 10px #B1B3B5;
            backdrop-filter: blur(3px);
        }

        label, input, button {
            position: relative;
            top: 2rem;
        }

        hr {
            position: relative;
            top: 12px;
            height: 2px; 
            background-color: #74777A; 
            border: none; 
        }

        ion-icon {
            position: absolute;
            font-size: 28px;
            top: 0;
            left: 0;
            color: #51565D;
            transition: .2s;
        }

        button {
            border-radius: 4px;
            border: 1.8px solid #51565D;
            font-size: 25px;
            background-color: transparent;
            height: 42px;
            width: 275px;
            color: #51565D;
            cursor: pointer;
            transition: .25s;
        }

        button:hover {
            height: 50px;
            width: 290px;
            font-size: 27px;
            color: white;
            background-color: #2888E5;
        }

        button:active {
            background-color: #3584D0;
            transform: scale(.9);

        }

        ion-icon:active {
            transform: scale(.9);
        }
    </style>
</head>

<body>
    <div class="form">
        <h2>Update Account</h2>
        <form action="update_acc.php" method="POST" onsubmit="return validateForm() && confirmUpdate()">
            <hr>
            <center>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($current_user['username']); ?>" required><br><br>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <input type="hidden" name="admin_id" value="<?php echo $user_id; ?>"><br><br>
            
                <button type="submit">Update</button>
            </center>
            <a href='index.php'><ion-icon name="arrow-back-outline"></ion-icon></a>
        </form>  
    </div>

    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false; // Prevent form submission
            }
            return true; // Passwords match
        }

        function confirmUpdate() {
            return confirm("Are you sure you want to update this account?");
        }
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
