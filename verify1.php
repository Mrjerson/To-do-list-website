<?php
require 'config.php';
session_start(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email']; 

        $code = "";
        $code .= $_POST['code1'];
        $code .= $_POST['code2'];
        $code .= $_POST['code3'];
        $code .= $_POST['code4'];
        $code .= $_POST['code5'];
        $code .= $_POST['code6'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND auth_code = ?");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $update_stmt = $conn->prepare("UPDATE users SET is_active = 1 WHERE email = ?");
            $update_stmt->bind_param("s", $email);
            if ($update_stmt->execute()) {
                header('Location: login.html');
                exit();
            } else {
                echo 'Error updating the verification status.';
            }
            $update_stmt->close();
        } else {
            header('Location: verify.php?error=true');
            exit();
        }

        $stmt->close();
    } else {
        header('Location: verify.php');
    }

    $conn->close();
} else {
    header('Location: error.html');
}
?>
