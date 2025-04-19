<?php
include_once(__DIR__ . '/../../config.php');
?>
<?php
// Ensure session is started
if (!isset($_SESSION)) {
    session_start();
}
$user_id = $_SESSION['id'] ?? null;
?>
<?php

$id = $_POST['id'] ?? null;
$title = $_POST['title'];
$description = $_POST['description'];
$date = $_POST['reminder_date'];
$status = $_POST['status'];

if ($id) {
  $stmt = $conn->prepare("UPDATE reminders SET title=?, description=?, reminder_date=?, status=? WHERE id=?");
  $stmt->bind_param("ssssi", $title, $description, $date, $status, $id);
} else {
  $stmt = $conn->prepare("INSERT INTO reminders (title, description, reminder_date, status,user_id) VALUES (?,?, ?, ?, ?)");
  $stmt->bind_param("sssss", $title, $description, $date, $status,$user_id);
}

$stmt->execute();
$stmt->close();
$conn->close();

header("Location: ../?page=reminders/list");
exit;
