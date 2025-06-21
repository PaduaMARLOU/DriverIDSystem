<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../connections.php");

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if ($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden3.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden3.php");
    exit; // Ensure script stops executing after redirection
}

$f_name = $m_name = $l_name = $gender = $phone_num = $email = $u_name = $password = $cfm_password = "";
$f_nameErr = $m_nameErr = $l_nameErr = $genderErr = $phone_numErr = $emailErr = $u_nameErr = $passwordErr = $cfm_passwordErr = $imgErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['admin_image'])) {

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

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }    

    if (empty($_POST["username"])) {
        $u_nameErr = "Username is required";
    } else {
        $u_name = htmlspecialchars($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = htmlspecialchars($_POST["password"]);
    }

    if (empty($_POST["confirm_password"])) {
        $cfm_passwordErr = "Please confirm your password";
    } else {
        $cfm_password = htmlspecialchars($_POST["confirm_password"]);
        if ($password != $cfm_password) {
            $cfm_passwordErr = "Passwords do not match!";
        }
    }

    // Check if username already exists
    $stmt = $connections->prepare("SELECT * FROM tbl_admin WHERE username = ?");
    $stmt->bind_param("s", $u_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $u_nameErr = "This username is already taken.";
    }

    if (empty($f_nameErr) && empty($m_nameErr) && empty($l_nameErr) && empty($genderErr) && empty($phone_numErr) && empty($emailErr) && empty($u_nameErr) && empty($passwordErr) && empty($cfm_passwordErr) && empty($imgErr)) {

        // Determine account_type and status based on registering admin's account_type
        $acc_type = 0;
        $status = '';

        if ($account_type == 1) {
            $acc_type = 2;
            $status = 'Direct Approved';
        } elseif ($account_type == 2) {
            $acc_type = 3;
            $status = 'Pending...';
        }

        $sql = "INSERT INTO tbl_admin (first_name, middle_name, last_name, sex, mobile_number, email, username, password, attempt, relog_time, login_time, logout_time, account_type, date_registered, img, status) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', ?, NOW(), '', ?)";

        $stmt = $connections->prepare($sql);
        $stmt->bind_param("ssssssssis", $f_name, $m_name, $l_name, $gender, $phone_num, $email, $u_name, $password, $acc_type, $status);

        if ($stmt->execute()) {
            // Get the admin_id of the newly inserted record
            $admin_id = $stmt->insert_id;
            // Get current date in Manila
            $timezone = new DateTimeZone('Asia/Manila');
            $date = new DateTime('now', $timezone);
            $current_date = $date->format('Y-m-d_H-i-s');

            // Image upload logic
            $profileDir = '../../uploads/profile/';
            $img_name = $_FILES['admin_image']['name'];
            $img_tmp_name = $_FILES['admin_image']['tmp_name'];
            $img_size = $_FILES['admin_image']['size'];
            $img_error = $_FILES['admin_image']['error'];

            $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ext_lc = strtolower($img_ext);
            $allowed_exts = array("jpg", "jpeg", "png");

            if (in_array($img_ext_lc, $allowed_exts)) {
                $img_new_name = "";
                if (!empty($f_name) && !empty($l_name)) {
                    $full_name = $f_name . ($m_name ? '_' . $m_name : '') . '_' . $l_name;
                    $img_new_name = "{$admin_id}_{$full_name}_{$current_date}_{$img_name}";
                }
                $img_upload_path = $profileDir . $img_new_name;
                move_uploaded_file($img_tmp_name, $img_upload_path);

                // Update the img field with the new image name
                $update_sql = "UPDATE tbl_admin SET img = ? WHERE admin_id = ?";
                $update_stmt = $connections->prepare($update_sql);
                $update_stmt->bind_param("si", $img_new_name, $admin_id);
                $update_stmt->execute();
            } else {
                $imgErr = "Upload images of jpg, jpeg, or png type";
            }

            header("Location: successregister.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../adminportalcss/admin_register.css">
    <title>Admin Registration</title>

    <link rel="icon" href="../../img/Brgy. Estefania Logo (Old).png">

    <link rel="stylesheet" href="../adminportalcss/adminlogin.css">
</head>

<body>
    <style>
        <?php include("../adminportalcss/admin_register.css"); ?>@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            color: white;
            text-decoration: none;
        }

        .back-btn {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 30px;
            filter: drop-shadow(1px 1px 2px gray);
            color: rgb(255, 255, 255);
            transition: .2s;
        }

        .back-btn:active {
            display: inline-block;
            transform: scale(.9);
        }
    </style>

    <a href="../Admin/register.php" class="back-btn"><ion-icon name="arrow-back-outline"></ion-icon></a>
    <div class="container">
        <center><br><br>
            <a href="https://www.facebook.com/profile.php?id=100068486726755" target="_blank">
                <img src="../../img/Brgy. Estefania Logo (Old).png" alt="Barangay Estefania Logo" class="logo">
            </a>
            <div class="admin-register">
                <form id="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    <h1 class="admin">Admin Registration</h1><br>
                    <hr class="hr"><br>

                    <ion-icon name="person" class="icon"></ion-icon><input type="text" class="t-box" id="f_name" name="first_name" value="<?php echo $f_name; ?>" placeholder="First Name..."><br>
                    <span class="err"><?php echo $f_nameErr; ?></span><br>

                    <ion-icon name="person" class="icon"></ion-icon><input type="text" class="t-box" id="m_name" name="middle_name" value="<?php echo $m_name; ?>" placeholder="Middle Name..."><br>
                    <span class="err"><?php echo $m_nameErr; ?></span><br>

                    <ion-icon name="person" class="icon"></ion-icon><input type="text" class="t-box" id="l_name" name="last_name" value="<?php echo $l_name; ?>" placeholder="Last Name..."><br>
                    <span class="err"><?php echo $l_nameErr; ?></span><br>

                    <ion-icon name="male-female" class="icon"></ion-icon><select name="sex" class="t-box" id="gender">
                        <option value="" class="t-box">Select Gender</option>
                        <option class="t-box" value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
                        <option class="t-box" value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
                    </select><br>
                    <span class="err"><?php echo $genderErr; ?></span><br>

                    <ion-icon name="call" class="icon"></ion-icon><input type="text" class="t-box" id="phone_num" name="mobile_number" value="<?php echo $phone_num; ?>" placeholder="Mobile Number..." maxlength="11" pattern="[0-9]{11}" inputmode="numeric" title="Please enter your 11 digit number."><br>
                    <span class="err"><?php echo $phone_numErr; ?></span><br>

                    <ion-icon name="mail" class="icon"></ion-icon>
                    <input type="email" class="t-box" id="email" name="email" 
                        value="<?php echo htmlspecialchars($email); ?>" 
                        placeholder="Email..." 
                        required 
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                        title="Please enter a valid email address.">
                    <br>
                    <span class="err"><?php echo $emailErr; ?></span><br>

                    <ion-icon name="person" class="icon"></ion-icon><input type="text" class="t-box" id="u_name" name="username" value="<?php echo $u_name; ?>" placeholder="Username..." required><br>
                    <span class="err"><?php echo $u_nameErr; ?></span><br>

                    <ion-icon name="lock-closed" class="icon"></ion-icon><input type="password" class="t-box" id="password" name="password" placeholder="Password..." pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required><br>
                    <span class="err"><?php echo $passwordErr; ?></span><br>

                    <ion-icon name="lock-closed" class="icon"></ion-icon><input type="password" class="t-box" id="cfm_password" name="confirm_password" placeholder="Confirm Password..." pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required><br>
                    <span class="err"><?php echo $cfm_passwordErr; ?></span><br>

                    <ion-icon name="camera" class="icon"></ion-icon><input type="file" class="t-box" id="admin_image" name="admin_image" accept="image/*"><br>
                    <span class="err"><?php echo $imgErr; ?></span><br>

                    <input type="submit" class="btn-register" value="REGISTER"><br><br>
                </form>
            </div><br>
        </center>
    </div>

    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>