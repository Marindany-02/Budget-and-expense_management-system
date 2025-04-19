<?php
include_once(__DIR__ . '/../../config.php');

// ✅ Ensure session is started and user is logged in
if (!isset($_SESSION['id'])) {
    die("Unauthorized access: User not logged in.");
}

$user_id = $_SESSION['id'];

// ✅ Fetch reminders only for the logged-in user
$query = "SELECT * FROM reminders WHERE user_id = ? ORDER BY reminder_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reminders = $stmt->get_result();

// ✅ Check if query succeeded
if (!$reminders) {
    die("Query Failed: " . $conn->error);
}
?>


<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Reminder List</h3>
    <a href="?page=reminders/create" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Add Reminder</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
  <?php $i = 1; while ($row = $reminders->fetch_assoc()): ?>
  <tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= $row['reminder_date'] ?></td>
    <td>
      <span class="badge badge-<?= $row['status'] === 'Completed' ? 'success' : 'warning' ?>">
        <?= $row['status'] ?>
      </span>
    </td>
    <td>
      <a href="?page=reminders/create&id=<?= $row['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
      <a href="reminders/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this reminder?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
    </td>
  </tr>
  <?php endwhile; ?>
</tbody>

    </table>
  </div>
</div>
