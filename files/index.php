<?php

include("../connections.php");
session_start();

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $query_account_type = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $get_account_type = mysqli_fetch_assoc($query_account_type);
    $account_type = $get_account_type["account_type"];

    if ($account_type == 1 || $account_type == 2) {
        echo "<script>window.location.href='Admin';</script>";
    } elseif ($account_type == 4) {
        echo "<script>window.location.href='Responder';</script>";
    } else {
        // Handle other account types if needed
    }
}


date_default_timezone_set("Asia/Manila");
$date_now = date("Y-m-d H:i:s");
$notify = $attempt = $relog_time = "";

$end_time = date("Y-m-d H:i:s", strtotime("+15 minutes"));
$end_time_display = date("m/d/Y h:i A", strtotime($end_time));

$username = $password = "";
$usernameErr = $passwordErr = "";

if (isset($_POST["btnLogin"])) {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = $_POST["username"];
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    if ($username && $password) {
        $check_username = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
        $check_row = mysqli_num_rows($check_username);

        if ($check_row > 0) {
            $fetch = mysqli_fetch_assoc($check_username);
            $db_password = $fetch["password"];
            $db_attempt = $fetch["attempt"];
            $db_relog_time = strtotime($fetch["relog_time"]);
            $my_relog_time = date("m/d/Y h:i A", $db_relog_time);
            $new_time = strtotime("now");

            $account_type = $fetch["account_type"];
            $admin_id = $fetch["admin_id"]; // Get admin ID for logging

            if ($db_relog_time <= $new_time) {
                if ($db_password == $password) {
                    // Successful login
                    $_SESSION["username"] = $username;
                    $login_time = date("Y-m-d H:i:s");
                    mysqli_query($connections, "UPDATE tbl_admin SET attempt=0, relog_time=NULL, login_time='$login_time' WHERE username='$username'");

                    // Log successful login
                    $action_details = "Admin with ID $admin_id logged in successfully.";
                    if (!mysqli_query($connections, "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$date_now')")) {
                        error_log("Error logging successful login: " . mysqli_error($connections));
                    }

                    // Assuming $account_type is already fetched from the database
                    if ($account_type == 4) {
                        echo "<script>window.location.href = 'Responder';</script>";
                    } else {
                        echo "<script>window.location.href = 'Admin';</script>";
                    }
                } else {
                    // Incorrect password attempt
                    $attempt = (int)$db_attempt + 1;

                    if ($attempt >= 3) {
                        $attempt = 3;
                        mysqli_query($connections, "UPDATE tbl_admin SET attempt='$attempt', relog_time='$end_time' WHERE username='$username'");
                        $notify = "You have reached the maximum of three (3) login attempts. Please try again after 15 minutes: <b>$end_time_display</b>";

                        // Log maximum attempt reached
                        $action_details = "Admin with ID $admin_id reached the maximum login attempts.";
                        if (!mysqli_query($connections, "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$date_now')")) {
                            error_log("Error logging maximum attempts: " . mysqli_error($connections));
                        }
                    } else {
                        mysqli_query($connections, "UPDATE tbl_admin SET attempt='$attempt' WHERE username='$username'");
                        $passwordErr = "Password is incorrect";
                        $notify = "Login Attempt: <b>$attempt</b>";

                        // Log incorrect password attempt
                        $action_details = "Failed login attempt for Admin ID $admin_id. Attempt $attempt.";
                        if (!mysqli_query($connections, "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$date_now')")) {
                            error_log("Error logging incorrect password attempt: " . mysqli_error($connections));
                        }
                    }
                }
            } else {
                // Attempted login during lockout
                $notify = "You must wait until: <b>$my_relog_time</b> before you can login again.";

                // Log login attempt during lockout
                $action_details = "Admin ID $admin_id attempted login during lockout period.";
                if (!mysqli_query($connections, "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$date_now')")) {
                    error_log("Error logging lockout attempt: " . mysqli_error($connections));
                }
            }
        } else {
            // Username not registered
            $usernameErr = "Username is not registered!";

            // Log unregistered username attempt
            $action_details = "Login attempt with unregistered username '$username'.";
            if (!mysqli_query($connections, "INSERT INTO tbl_log (action_details, action_date) VALUES ('$action_details', '$date_now')")) {
                error_log("Error logging unregistered username attempt: " . mysqli_error($connections));
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="adminportalcss/adminlogin.css">
	<title>Admin Log-In</title>
	<link rel="icon" type="image/png" href="../img/Brgy. Estefania Logo (Old).png">
</head>

<body>
    <style>
        <?php include("adminportalcss/adminlogin.css"); ?>

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        *::selection {
            background-color: #B158EE;
            text-shadow: 1px 1px 10px gray;
        }

        .brgy {
            text-shadow: 1px 1px 8px black;
        }
        
    </style>
    
	<br>

	<center class="mains">
        <br><br><br>
		<a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
			<img src="../img/Brgy. Estefania Logo (Old).png" alt="Barangay Estefania Logo" class="logo">
		</a>
		<br><br><h1 class="brgy">Admin Login Portal</h1>

		<form method="POST" class="log-in-form">
			<br>
			<h2 class="log-in">Log-in</h2>
			<hr class="hr">
			<br>

			<ion-icon name="person" class="icon"></ion-icon><input type="text" class="t-box" name="username" placeholder="Username" value="<?php echo $username; ?>"> <br>
			<span class="error"><?php echo $usernameErr; ?></span>

			<br>

			<ion-icon name="lock-closed" class="icon"></ion-icon><input type="password" class="t-box" name="password" placeholder="Password" value="" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required> <br>
			<span class="error"><?php echo $passwordErr; ?></span>

			<br>

			<span class="error"><?php echo $notify; ?></span>


			<input class="btn-log-in" type="submit" name="btnLogin" value="Login">

			<br>
			
            <br>
            <a href="forgot_password.php" class="f-pass"">Forgot Password</a>


			<br>

		</form>

        <br>
	</center>
	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
	<script src="../js/script.js"></script></body>
</html>