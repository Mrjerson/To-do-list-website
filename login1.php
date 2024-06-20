<?php
session_start();
session_regenerate_id(true);

require 'config.php'; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, is_active FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password, $is_active);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            if ($is_active == 1) {
                $_SESSION['username'] = $username;
                header('Location: welcome.php');
                exit();
            } else {
                echo 'Your account is not active. Please verify your email to activate your account.';
            }
        } else {
            echo 'Invalid password.';
        }
    } else {
        echo 'No user found with that username.';
    }

    $stmt->close();
}
?>