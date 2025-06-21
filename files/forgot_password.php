<?php
// Start session to store OTP and user details
session_start();
require 'vendor/autoload.php';
require '../connections.php'; // Adjust the path based on your project structure

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_otp'])) {
        // Get the username from input
        $username = $_POST['username'];

        // Query the database for the email associated with the username
        $query = "SELECT admin_id, email FROM tbl_admin WHERE username = ?";
        $stmt = $connections->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $admin_id = $row['admin_id'];
            $email = $row['email'];

            // Generate OTP and store in session
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['email'] = $email;

            // Send OTP email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'BarangayEstefania.management@gmail.com';
                $mail->Password = 'eexzbxmhzsxbqazj'; // Your App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->setFrom('BarangayEstefania.management@gmail.com', 'Barangay Estefania ID System');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body = 'Your OTP is: ' . $otp . '<br><br>If you did not request a password reset, please ignore this email.';

                $mail->send();
                echo '<center><p class="success">OTP has been sent to your email.</p></center>';
            } catch (Exception $e) {
                echo "<p class='error'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
            }
        } else {
            echo '<center><p class="error">Username not found.</p></center>';
        }
    }

    // Verify OTP
    if (isset($_POST['verify_otp'])) {
        $entered_otp = $_POST['otp'];

        // Check if entered OTP matches the session OTP
        if ($entered_otp == $_SESSION['otp']) {
            $_SESSION['otp_verified'] = true;
            echo '<center><p class="success">OTP verified. You can now change your password.</p></center>';
        } else {
            echo '<center><p class="error">Invalid OTP. Please try again.</p></center>';
        }
    }

    // Change password
    if (isset($_POST['change_password'])) {
        if ($_SESSION['otp_verified']) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            $admin_id = $_SESSION['admin_id'];

            if ($new_password === $confirm_password) {
                // Update the password in the database
                $update_query = "UPDATE tbl_admin SET password = ? WHERE admin_id = ?";
                $stmt = $connections->prepare($update_query);
                $stmt->bind_param('si', $new_password, $admin_id);

                if ($stmt->execute()) {
                    // Log change password success
                    date_default_timezone_set('Asia/Manila');
                    $date_now = date('Y-m-d H:i:s');
                    $action_details = "Admin with ID $admin_id successfully changed password.";
                    if (!mysqli_query($connections, "INSERT INTO tbl_log (fk_admin_id, action_details, action_date) VALUES ('$admin_id', '$action_details', '$date_now')")) {
                        error_log("Error logging password change: " . mysqli_error($connections));
                    }

                    // Show success message and redirect
                    echo '<p class="redirect" style="color: green; font-weight: bold; font-style: italic;">Password has been changed successfully. Redirecting... Please Wait.</p>';
                    echo '<script>
                        setTimeout(function() {
                            window.location.href = "index.php"; // Redirect after 3 seconds
                        }, 3000);
                    </script>';
                    session_destroy(); // Clear session data after successful change
                    exit();
                } else {
                    echo '<p class="error">Error updating the password.</p>';
                }
            } else {
                echo '<center><p class="error">Passwords do not match. Please try again.</p></center>';
            }
        } else {
            echo '<center><p class="error">OTP verification required.</p></center>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../img/Brgy. Estefania Logo (Old).png">
    <title>Forgot Password</title>
    <link rel="icon" href="../img/forgot password.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        input[type="text"],
        input[type="password"] {
            outline: none;
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        button:hover {
            background: #4cae4c;
        }

        .error {
            color: red;
        }

        .success {
            position: relative;
            top: 35rem;
            color: green;
        }

        .error {
            position: relative;
            top: 32rem;
            color: red;
        }
    </style>
</head>

<body>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    
    <center>
    <lord-icon
        src="https://cdn.lordicon.com/pdwpcpva.json"
        trigger="hover"
        style="width:210px;height:210px">
    </lord-icon></center>

    <?php if (!isset($_SESSION['otp'])): ?>
        <form method="POST" action="">
            <label for="username">Enter your username:</label>
            <input type="text" name="username" required>
            <button type="submit" name="send_otp">Send OTP</button>
        </form>
    <?php elseif (!isset($_SESSION['otp_verified'])): ?>
        <form method="POST" action="">
            <label for="otp">Enter your OTP:</label>
            <input type="text" name="otp" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <label style="font-size: 0.9em; opacity: 0.6;"><i>Password must be at least 8 characters long and include both letters and numbers.</i></label><br><hr><br>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and include both letters and numbers" required>
            <button type="submit" name="change_password">Change Password</button>
        </form>
    <?php endif; ?>
</body>

</html>