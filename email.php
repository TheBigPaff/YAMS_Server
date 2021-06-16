<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$gmail_password = $_ENV['GMAIL_PASSWORD'];

function send_email($toEmail, $subject, $body){
    global $gmail_password;

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'pushmeup.app2@gmail.com';                 // SMTP username
    $mail->Password = $gmail_password;                         // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $mail->From = 'yetanothermobileshooter@gmail.com';
    $mail->FromName = 'YetAnotherMobileShooter';
    $mail->addAddress($toEmail);     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body = $body;

    if(!$mail->send()) {
        die('5: Email could not be sent; '.$mail->ErrorInfo);
    }
}
