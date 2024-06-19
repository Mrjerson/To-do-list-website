<?php
session_start();
require 'config.php';
require 'phpmailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$email = $_SESSION['email'];
$auth_code = rand(100000, 999999);
$stmt = $GLOBALS['conn']->prepare("UPDATE users SET auth_code = $auth_code WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->close();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
     $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['EMAIL'];
    $mail->Password = $_ENV['EMAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($_ENV['EMAIL'], $_ENV['EMAIL_NAME']);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your Authentication Code';
    $mail->Body = 'Your authentication code is: ' . $auth_code;

    $mail->send();
        
    header('Location: verify.php');
    exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

?>