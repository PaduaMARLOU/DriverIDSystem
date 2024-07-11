<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];
    
    if($account_type != 1){
        header("Location: ../../Forbidden3.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden3.php");
    exit; // Ensure script stops executing after redirection
}

$f_name = $m_name = $l_name = $gender = $phone_num = $username = $password = $cfm_password = "";
$f_nameErr = $m_nameErr = $l_nameErr = $genderErr = $phone_numErr = $usernameErr = $passwordErr = $cfm_passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["first_name"])) {
        $f_nameErr = "First name is required";
    } else {
        $f_name = htmlspecialchars($_POST["first_name"]);
    }

    if (empty($_POST["middle_name"])) {
        $m_nameErr = "Middle name is required";
    } else {
        $m_name = htmlspecialchars($_POST["middle_name"]);
    }

    if (empty($_POST["last_name"])) {
        $l_nameErr = "Last name is required";
    } else {
        $l_name = htmlspecialchars($_POST["last_name"]);
    }

    if (empty($_POST["sex"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = htmlspecialchars($_POST["sex"]);
    }

    if (empty($_POST["mobile_number"])) {
        $phone_numErr = "Phone number is required";
    } else {
        $phone_num = htmlspecialchars($_POST["mobile_number"]);
    }

    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = htmlspecialchars($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = htmlspecialchars($_POST["password"]);
    }

    if (empty($_POST["confirm_password"])) {
        $cfm_passwordErr = "Please! confirm your password";
    } else {
        $cfm_password = htmlspecialchars($_POST["confirm_password"]);
        if ($password != $cfm_password) {
            $cfm_passwordErr = "Passwords do not match!";
        }
    }

    if (empty($f_nameErr) && empty($m_nameErr) && empty($l_nameErr) && empty($genderErr) && empty($phone_numErr) && empty($usernameErr) && empty($passwordErr) && empty($cfm_passwordErr)) {
        // Fetch the highest account_type from the database
        $result = mysqli_query($conns, "SELECT MAX(account_type) as max_account_type FROM tbl_admin");
        $row = mysqli_fetch_assoc($result);
        $account_type = $row['max_account_type'] + 1;

        $sql = "INSERT INTO tbl_admin (first_name, middle_name, last_name, sex, mobile_number, username, password, attempt, relog_time, login_time, logout_time, account_type, date_registered, img) 
                VALUES ('$f_name', '$m_name', '$l_name', '$gender', '$phone_num', '$username', '$password', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '$account_type', NOW(), '')";

        if (mysqli_query($conns, $sql)) {
            echo "You are now registered!";
        } else {
            echo "Error: " . mysqli_error($conns);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>

    <!--<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">-->

    <link rel="icon" href="../../img/Brgy Estefania Logo.png">

    <!--<script src="assets/js/jquery-1.11.3.min.js"></script>-->
    <link rel="stylesheet" href="../adminportalcss/adminlogin.css">
</head>

<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            color: white;
        }

        .logo {
            filter: drop-shadow(1px 1px 8px gray);
            position: relative;
            top: -1em;
            width: 250px;
            transition: .4s ease;
        }

        .container {
            position: relative;
            top: 2em;
            display: flex;
            justify-content: center;
            align-items: center; 
            height: 100vh; 
        }

        .admin {
            text-shadow: 1px 1px 10px black;
        }

        .admin-register {
            position: relative;
            border: 2px solid white;
            width: 22em;
            padding-top: 1em;
            padding-bottom: 2em;
            border-radius: 5px;
            box-shadow: 1px 1px 10px gray;
            backdrop-filter: blur(20px);
        }

        .btn-register {
            position: relative;
            color: white;
            top: .6em;
            font-size: 20px;
            height: 1.8em;
            width: 12em;
            border-radius: 4px;
            background-color: transparent;
            border: 2px solid white;
            cursor: pointer;
            transition: .3s ease;
        }

        .btn-register:hover {
            position: relative;
            height: 2em;
            width: 12.5em;
            font-size: 20.7px;
            box-shadow: 1px 1px 8px whitesmoke;
            background-color: #FCCB57;
        }

        .t-box {
            outline: none;
            color: black;
        }

        .err {
            color: red;
        }
    </style>


    <div class="container">
    <center>
        <a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
            <img src="../../img/Brgy Estefania Logo.png" alt="Barangay Estefania Logo" class="logo">
        </a>
        <div class="admin-register">
                <form id="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <h2 class="admin">Admin Registration</h2>
                    <hr><br>

                    <input type="text" class="t-box" id="f_name" name="first_name" value="<?php echo $f_name; ?>" placeholder="First Name..."><br>
                    <span class="err"><?php echo $f_nameErr; ?></span><br>

                    <input type="text" class="t-box" id="m_name" name="middle_name" value="<?php echo $m_name; ?>" placeholder="Middle Name.."><br>
                    <span class="err"><?php echo $m_nameErr; ?></span><br>

                    <input type="text" class="t-box" id="l_name" name="last_name" value="<?php echo $l_name; ?>" placeholder="Last Name"><br>
                    <span class="err"><?php echo $l_nameErr; ?></span><br>

                    <select name="sex" class="t-box" id="gender">
                        <option value="">Select Gender</option>
                        <option class="t-box" value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
                        <option class="t-box" value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
                    </select>
                    <br>
                    <span class="err"><?php echo $genderErr; ?></span><br>

                    <input type="text" class="t-box" id="phone_num" name="mobile_number" value="<?php echo $phone_num; ?>" placeholder="Phone Number..."><br>
                    <span class="err"><?php echo $phone_numErr; ?></span><br>

                    <input type="text" class="t-box" id="username" name="username" value="<?php echo $username; ?>" placeholder="Username..."><br>
                    <span class="err"><?php echo $usernameErr; ?></span><br>

                    <input type="password" class="t-box" id="password" name="password" value="<?php echo $password; ?>" placeholder="Password..."><br>
                    <span class="err"><?php echo $passwordErr; ?></span><br>

                    <input type="password" class="t-box" id="confirmPassword" name="confirm_password" value="<?php echo $cfm_password; ?>" placeholder="Confirm Password..."><br>
                    <span class="err"><?php echo $cfm_passwordErr; ?></span><br><br>

                    <input type="submit" class="btn-register" name="submit" value="Register">
                </form>
            </center>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($f_nameErr) && empty($m_nameErr) && empty($l_nameErr) && empty($genderErr) && empty($phone_numErr) && empty($usernameErr) && empty($passwordErr) && empty($cfm_passwordErr)) {
        echo "Success!";
    }
    ?>
</body>
</html>
