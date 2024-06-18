<?php
require 'config.php';
require 'phpmailer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $auth_code = rand(100000, 999999);

    $stmt = $GLOBALS['conn']->prepare("INSERT INTO users (username, email, password, auth_code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $password, $auth_code);
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
        echo 'A verification code has been sent to your email address.';

        $_SESSION['email'] = $email;

        // Redirect to verify.php
        header('Location: verify.php');
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $GLOBALS['conn']->close();
} else {
    header('Location: signup.html');
    exit();
}
?>
