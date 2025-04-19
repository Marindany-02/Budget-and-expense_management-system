<?php
require_once('../../config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM goals WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=goals/list&deleted=1");
        exit();
    } else {
        echo "Failed to delete goal: " . $conn->error;
    }
} else {
    echo "Goal ID is missing!";
}
?>
