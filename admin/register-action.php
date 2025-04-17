<?php
require_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $username  = trim($_POST['username']);
    $password  = trim($_POST['password']);
    $phone = $_POST['phone'];  // sanitize as needed


    // Validate input
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
        $_SESSION['flash_error'] = "All fields are required.";
        header("Location: register.php");
        exit;
    }

    // Check if username already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['flash_error'] = "Username already exists.";
        header("Location: register.php");
        exit;
    }

    // Insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $date_now = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username,phone, password, avatar, last_login, type, date_added, date_updated) 
                            VALUES (?, ?,?, ?, ?, '', NULL, 3, ?, ?)");
    $stmt->bind_param("sssssss", $firstname, $lastname, $username,$phone, $hashed_password, $date_now, $date_now);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Insert MPESA balance with an initial value of 0
        $balance = 0; // Store the balance in a variable
        $balance_stmt = $conn->prepare("INSERT INTO mpesa_balance (user_id,phone, balance) VALUES (?,?, ?)");
        $balance_stmt->bind_param("iii", $user_id, $phone, $balance);

        if ($balance_stmt->execute()) {
            $_SESSION['flash_success'] = "Account created successfully. Please login.";
header("Location: login.php");
exit;
        } else {
            echo "Error creating MPESA balance.";
        }
        $_SESSION['flash_success'] = "Account created successfully. Please login.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['flash_error'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit;
    }
} else {
    header("Location: register.php");
    exit;
}
