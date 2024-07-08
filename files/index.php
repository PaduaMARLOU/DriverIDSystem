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
    } else {
        // Handle non-admin user if needed
    }
}

date_default_timezone_set("Asia/Manila");
$date_now = date("m/d/Y");
$time_now = date("h:i a");
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

            if ($account_type == 1 || $account_type == 2) {
                if ($db_relog_time <= $new_time) {
                    if ($db_password == $password) {
                        $_SESSION["username"] = $username;
                        $login_time = date("Y-m-d H:i:s");
                        mysqli_query($connections, "UPDATE tbl_admin SET attempt=0, relog_time=NULL, login_time='$login_time' WHERE username='$username'");
                        echo "<script>window.location.href='Admin';</script>";
                    } else {
                        $attempt = (int)$db_attempt + 1;

                        if ($attempt >= 3) {
                            $attempt = 3;
                            mysqli_query($connections, "UPDATE tbl_admin SET attempt='$attempt', relog_time='$end_time' WHERE username='$username'");
                            $notify = "You have reached the maximum of three (3) login attempts. Please try again after 15 minutes: <b>$end_time_display</b>";
                        } else {
                            mysqli_query($connections, "UPDATE tbl_admin SET attempt='$attempt' WHERE username='$username'");
                            $passwordErr = "Password is incorrect";
                            $notify = "Login Attempt: <b>$attempt</b>";
                        }
                    }
                } else {
                    $notify = "You must wait until: <b>$my_relog_time</b> before you can login again.";
                }
            } elseif ($account_type == 3) {
                $notify = "Please wait for the confirmation of the Super Admin for your Admin privileges.";
            } else {
                $passwordErr = "Your account type is not recognized.";
            }
        } else {
            $usernameErr = "Username is not registered!";
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
	<title>Log-In</title>
	<link rel="icon" type="image/jpg" href="../img/Brgy Estefania Logo.png">
</head>

<body>

	<br>

	<center class="mains">
		<a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
			<img src="../img/Brgy Estefania Logo.png" alt="Barangay Estefania Logo" class="logo">
		</a>
		<h1 class="brgy">Barangay Estefania Driver's ID System<br>Admin Login Portal</h1>

		<form method="POST" class="log-in-form">
			<br>
			<h2 class="log-in">Log-in</h2>
			<hr class="hr">
			<br>

			<ion-icon name="person" class="icon"></ion-icon><input type="text" class="t-box" name="username" placeholder="Username" value="<?php echo $username; ?>"> <br>
			<span class="error"><?php echo $usernameErr; ?></span>

			<br>

			<ion-icon name="lock-closed" class="icon"></ion-icon><input type="password" class="t-box" name="password" placeholder="Password" value=""> <br>
			<span class="error"><?php echo $passwordErr; ?></span>

			<br>

			<span class="error"><?php echo $notify; ?></span>


			<input class="btn-log-in" type="submit" name="btnLogin" value="Login" onclick="playSound();">

			<br>
			
			<br>

			<a href="?forget=<?php echo md5(rand(1, 9)); ?>" class="forgot-pass">Forgot Password?</a>

		</form>
	</center>
	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
	<script src="../js/script.js"></script>
	<script type="text/javascript">
    function playSound() {
        console.log("playSound function called");
        const audio = new Audio();
        audio.src = "sound effect/switch-sound.mp3";
        audio.play();
    }
	</script>
</body>
</html>