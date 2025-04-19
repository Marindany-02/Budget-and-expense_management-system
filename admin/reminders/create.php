<?php
include_once(__DIR__ . '/../../config.php');
?>

<?php
$id = $_GET['id'] ?? null;

$reminder = [
  'title' => '',
  'description' => '',
  'reminder_date' => '',
  'status' => 'Pending',
];

if ($id) {
  $result = $conn->query("SELECT * FROM reminders WHERE id = $id");
  $reminder = $result->fetch_assoc();
}
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title"><?= $id ? 'Edit' : 'Add' ?> Reminder</h3>
  </div>
  <form action="reminders/save.php" method="POST">
    <div class="card-body">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($reminder['title']) ?>" required>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control"><?= htmlspecialchars($reminder['description']) ?></textarea>
      </div>

      <div class="form-group">
        <label>Reminder Date</label>
        <input type="date" name="reminder_date" class="form-control" value="<?= $reminder['reminder_date'] ?>" required>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
          <option value="Pending" <?= $reminder['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
          <option value="Completed" <?= $reminder['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>
      </div>
    </div>

    <div class="card-footer">
      <button type="submit" class="btn btn-success"><?= $id ? 'Update' : 'Save' ?></button>
      <a href="?page=reminders/list" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
