<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['reset_username'])) {
    header("Location: forgot-password.php");
    exit;
}

if (isset($_POST['reset_pass'])) {
    $username = $_SESSION['reset_username'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('Passwords do not match!'); window.location.href = 'reset-password.php';</script>";
        exit;
    }

    $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed, $username);

    if ($stmt->execute()) {
        unset($_SESSION['reset_username']);
        echo "<script>alert('Password reset successfully. You can now login.'); window.location.href = 'login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Something went wrong. Try again.'); window.location.href = 'reset-password.php';</script>";
        exit;
    }
}
?>
