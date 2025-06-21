<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the Composer autoload file
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                     // Enable SMTP authentication
    $mail->Username = 'BarangayEstefania.management@gmail.com'; // Your email address
    $mail->Password = 'eexzbxmhzsxbqazj';                       // Your App Password without spaces
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                          // TCP port to connect to

    // Bypass SSL verification
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Recipients
    $mail->setFrom('BarangayEstefania.management@gmail.com', 'Barangay Estefania ID System for Drivers');
    $mail->addAddress('kkong5980@gmail.com');                   // Replace with the recipient's email

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Your OTP for Password Reset';
    $otp = rand(100000, 999999);                                // Generate a random OTP
    $mail->Body    = 'Your OTP is: ' . $otp;                    // Email body with the generated OTP

    // Attempt to send the email
    if ($mail->send()) {
        echo 'OTP has been sent to your email.';
    } else {
        echo 'Message could not be sent.';
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
