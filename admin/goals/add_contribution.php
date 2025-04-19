<?php
include_once('../../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $goal_id = $_POST['goal_id'];
    $transaction_code = $_POST['transaction_code'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO goal_contributions (goal_id, transaction_code, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $goal_id, $transaction_code, $amount);
    
    if ($stmt->execute()) {
        header("Location: ../index.php?page=goals/view&id=$goal_id&success=1");
        } else {
        echo "Error: " . $conn->error;
    }
}
?>
