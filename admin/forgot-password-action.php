<?php
require_once '../config.php';
session_start();

if (isset($_POST['verify_user'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE firstname = ? AND lastname = ? AND username = ?");
    $stmt->bind_param("sss", $firstname, $lastname, $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['reset_username'] = $username;
        header("Location: reset-password.php");
        exit;
    } else {
        echo "<script>alert('User not found or details do not match!'); window.location.href = 'forgot-password.php';</script>";
        exit;
    }
}
?>
