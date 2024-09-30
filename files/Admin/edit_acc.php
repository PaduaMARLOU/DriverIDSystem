<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin</title>

    <link rel="icon" href="../../img/Brgy Estefania Logo.png" type="image/png">
</head>

<body>
    <style>
        <?php include("admin styles/admin_update.css"); ?>.background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-image: url('../../img/update.jpg');
            background-size: 73% auto;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: -1;
            /* Ensure it stays behind other content */
        }
    </style>

    <?php
    $admin_id = $_REQUEST["admin_id"];
    include("../../connections.php");
    $get_record = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE admin_id = '$admin_id'");
    while ($row_edit = mysqli_fetch_assoc($get_record)) {
        $db_id = $row_edit["admin_id"];
        $db_f_name = $row_edit["first_name"];
        $db_m_name = $row_edit["middle_name"];
        $db_l_name = $row_edit["last_name"];
        $db_gender = $row_edit["sex"];
        $db_phone_num = $row_edit["mobile_number"];
        $db_u_name = $row_edit["username"];
        $db_password = $row_edit["password"];
        $db_acc_type = $row_edit["account_type"];
        $db_img = $row_edit["img"];
    }

    // Error handling and validation logic
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = $_POST['new_f_name'];
        $middle_name = $_POST['new_m_name']; // Optional
        $last_name = $_POST['new_l_name'];
        $gender = $_POST['new_gender'];
        $phone_num = $_POST['new_phone_num'];
        $username = $_POST['new_u_name'];
        $password = $_POST['new_password'];
        $acc_type = $_POST['new_acc_type'];
        
        // Handle image upload
        if (isset($_FILES['admin_image']) && $_FILES['admin_image']['error'] === UPLOAD_ERR_OK) {
            $img_tmp_name = $_FILES['admin_image']['tmp_name'];
            $img_name = basename($_FILES['admin_image']['name']);
            $img_dir = "../../uploads/profile/" . $img_name;

            // Move uploaded file to the target directory
            if (move_uploaded_file($img_tmp_name, $img_dir)) {
                $db_img = $img_name; // Update image path to new uploaded file
            } else {
                $errors[] = "Failed to upload the image.";
            }
        }

        // Validate first name
        if (!preg_match("/^[A-Za-z\s'-]+$/", $first_name)) {
            $errors[] = "Invalid first name. Please use alphabetic characters only.";
        }

        // Validate last name
        if (!preg_match("/^[A-Za-z\s'-]+$/", $last_name)) {
            $errors[] = "Invalid last name. Please use alphabetic characters only.";
        }

        // Optional: Validate middle name if not empty
        if (!empty($middle_name) && !preg_match("/^[A-Za-z\s'-]+$/", $middle_name)) {
            $errors[] = "Invalid middle name. Please use alphabetic characters only.";
        }

        // Validate phone number
        if (!preg_match("/^[0-9]{11}$/", $phone_num)) {
            $errors[] = "Invalid phone number. Please enter exactly 11 digits.";
        }

        // If there are no errors, proceed with updating the record
        if (empty($errors)) {
            // Update the database here
            $update_query = "UPDATE tbl_admin SET 
                                first_name = '$first_name', 
                                middle_name = '$middle_name', 
                                last_name = '$last_name',
                                sex = '$gender',
                                mobile_number = '$phone_num',
                                username = '$username',
                                password = '$password',
                                account_type = '$acc_type',
                                img = '$db_img' 
                             WHERE admin_id = '$admin_id'";

        if (mysqli_query($connections, $update_query)) {
            echo "<script>
                    alert('Admin record updated successfully.');
                    window.location.href = 'admin_records.php';
                </script>";
        } else {
            echo "<p style='color: red;'>Error updating record: " . mysqli_error($connections) . "</p>";
        }

        } else {
            // Display errors
            foreach ($errors as $error) {
                echo "<center><br><p style='color: red; font-size: 25px; text-shadow: 1px 1px 5px white;'>$error</p></center>";
            }
        }
    }
    ?>

    <br><br>
    <div class="background"></div>

    <div class="container">
        <center>
            <h1>Update Admin</h1>

            <form method="post" action="" enctype="multipart/form-data">
                <a href="admin_records.php"><ion-icon name="arrow-back-outline" class="back"></ion-icon></a>

                <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">

                <ion-icon name="person-add"></ion-icon>
                <input type="text" name="new_f_name" pattern="[A-Za-z\s'-]+" required value="<?php echo $db_f_name; ?>" placeholder="First Name">
                <br><br>

                <ion-icon name="person-add"></ion-icon>
                <input type="text" name="new_m_name" pattern="[A-Za-z\s'-]+" value="<?php echo $db_m_name; ?>" placeholder="Middle Name (Optional)">
                <br><br>

                <ion-icon name="person-add"></ion-icon>
                <input type="text" name="new_l_name" pattern="[A-Za-z\s'-]+" required value="<?php echo $db_l_name; ?>" placeholder="Last Name">
                <br><br>

                <ion-icon name="male-female"></ion-icon>
                <select name="new_gender" id="new_gender" required>
                    <option value="Male" <?php if ($db_gender == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($db_gender == 'Female') echo 'selected'; ?>>Female</option>
                </select>
                <br><br>

                <ion-icon name="call"></ion-icon>
                <input type="text" name="new_phone_num" onkeypress="validateNumInput(event)" pattern="[0-9]{11}" id="new_phone_num" value="<?php echo $db_phone_num; ?>" maxlength="11" required placeholder="Mobile Number">
                <br><br>

                <ion-icon name="person-circle"></ion-icon>
                <input type="text" name="new_u_name" value="<?php echo $db_u_name; ?>" required placeholder="Username">
                <br><br>

                <ion-icon name="lock-closed"></ion-icon>
                <input type="password" name="new_password" value="<?php echo $db_password; ?>" required placeholder="Password">
                <br><br>

                <ion-icon name="key"></ion-icon>
                <input type="text" name="new_acc_type" value="<?php echo $db_acc_type; ?>" required placeholder="Account Type">
                <br><br>
                
                <div class="new-img">
                    <ion-icon name="image"></ion-icon>
                    <input type="file" name="admin_image" class="form-control">
                    <?php if (!empty($db_img)): ?>
                        <a href="../../uploads/profile/<?php echo htmlspecialchars($db_img); ?>" target="_blank">
                            <img src="../../uploads/profile/<?php echo htmlspecialchars($db_img); ?>" alt="Admin Image" width="100" style="border-radius: 5px;">
                        </a>
                    <?php endif; ?>
                    <br><br>
                </div><br><br><br><br><br>

                <input type="submit" value="Update">
            </form>
        </center>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
        function validateNumInput(event) {
            const charCode = event.which ? event.which : event.keyCode;
            // Only allow numbers (char codes 48-57 correspond to '0'-'9')
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>