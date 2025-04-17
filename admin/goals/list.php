<?php

include_once(__DIR__ . '/../../config.php');
$user_id = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;
$user_id = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "🎯 User ID is missing!";
    exit;
}
?>

<!-- ✅ Success/Deleted Alert Messages -->
<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    Goal deleted successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endif; ?>

<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
    Goal updated successfully!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <script>
    setTimeout(function () {
      var successMessage = document.getElementById('successMessage');
      if (successMessage) {
        successMessage.classList.remove('show');
        successMessage.classList.add('fade');
      }
    }, 3000);
  </script>
<?php endif; ?>

<?php
// ✅ Fetch goals belonging to the logged-in user
$stmt = $conn->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY expected_completion_date ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$goals = $stmt->get_result();
?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Goal List</h3>
    <a href="?page=goals/create" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Add Goal</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
    <thead>
  <tr>
    <th>#</th>
    <th>Goal Name</th>
    <th>Description</th>
    <th>Target Amount</th>
    <th>Expected Completion Date</th>
    <th>Starting Capital</th>
    <th>Total Contributed</th> <!-- ✅ New column -->
    <th>Progress</th>
    <th>Manage</th>
    <th>Actions</th>
  </tr>
</thead>
<tbody>
<?php $i = 1; while ($row = $goals->fetch_assoc()): ?>
  <tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['goal_name']) ?></td>
    <td><?= htmlspecialchars($row['goal_description']) ?></td>
    <td><?= number_format($row['target_amount'], 2) ?></td>
    <td><?= date('F j, Y', strtotime($row['expected_completion_date'])) ?></td>
    <td><?= number_format($row['starting_capital'], 2) ?></td>

    <?php
      $goal_id = $row['id'];
      $contrib_q = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total_contrib FROM goal_contributions WHERE goal_id = $goal_id");
      $contrib_data = $contrib_q->fetch_assoc();
      $total_contrib = $row['starting_capital'] + $contrib_data['total_contrib'];
      $remaining = $row['target_amount'] - $total_contrib;
      $progress = ($total_contrib / $row['target_amount']) * 100;
      $progress = $progress > 100 ? 100 : $progress;
    ?>

    <td><?= number_format($total_contrib, 2) ?></td> <!-- ✅ Total Contributed -->
    <td><?= round($progress, 2) . "%" ?></td>
    <td>
      <a href="?page=goals/view&id=<?= $row['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
    </td>
    <td>
      <a href="?page=goals/edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
      <a href="goals/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this goal?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
    </td>
  </tr>

  <!-- 🔻 New row: Total and Balance -->
  <tr class="bg-light text-dark">
    <td colspan="10">
      <div class="d-flex justify-content-between px-3">
        <div><strong>Total Contributed:</strong> KES <?= number_format($total_contrib, 2) ?></div>
        <div><strong>Remaining Balance:</strong> KES <?= number_format(max(0, $remaining), 2) ?></div>
      </div>
    </td>
  </tr>
<?php endwhile; ?>
</tbody>
    </table>
  </div>
</div>
