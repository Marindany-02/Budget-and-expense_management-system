<?php
include_once(__DIR__ . '/../../config.php');
?>

<?php

$id = $_GET['id'] ?? 0;
$conn->query("DELETE FROM reminders WHERE id = $id");

$conn->close();
header("Location: ../?page=reminders/list");
exit;
