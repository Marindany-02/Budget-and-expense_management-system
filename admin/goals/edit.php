<?php
include_once(__DIR__ . '/../../config.php');

// Check if an ID is provided via the GET request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Goal ID is missing!";
    exit();
}

// Get the goal ID
$goal_id = $_GET['id'];

// Fetch the goal data from the database
$query = "SELECT * FROM goals WHERE id = $goal_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Goal not found!";
    exit();
}

$goal = $result->fetch_assoc();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form data
    $goal_name = $_POST['goal_name'];
    $goal_description = $_POST['goal_description'];
    $target_amount = $_POST['target_amount'];
    $expected_completion_date = $_POST['expected_completion_date'];
    $starting_capital = $_POST['starting_capital'];

    // Update the goal in the database
    $update_query = "UPDATE goals SET goal_name = '$goal_name', goal_description = '$goal_description', target_amount = '$target_amount', expected_completion_date = '$expected_completion_date', starting_capital = '$starting_capital' WHERE id = $goal_id";

    if ($conn->query($update_query)) {
        echo "Goal updated successfully!";
        header("Location: index.php?page=goals/list&updated=1");
        exit();
    } else {
        echo "Error updating goal: " . $conn->error;
    }
}
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Goal</h3>
  </div>
  <div class="card-body">
    <form action="" method="POST">
      <div class="form-group">
        <label for="goal_name">Goal Name</label>
        <input type="text" class="form-control" id="goal_name" name="goal_name" value="<?= htmlspecialchars($goal['goal_name']) ?>" required>
      </div>

      <div class="form-group">
        <label for="goal_description">Description</label>
        <textarea class="form-control" id="goal_description" name="goal_description" required><?= htmlspecialchars($goal['goal_description']) ?></textarea>
      </div>

      <div class="form-group">
        <label for="target_amount">Target Amount</label>
        <input type="number" class="form-control" id="target_amount" name="target_amount" value="<?= htmlspecialchars($goal['target_amount']) ?>" required>
      </div>

      <div class="form-group">
        <label for="expected_completion_date">Expected Completion Date</label>
        <input type="date" class="form-control" id="expected_completion_date" name="expected_completion_date" value="<?= htmlspecialchars($goal['expected_completion_date']) ?>" required>
      </div>

      <div class="form-group">
        <label for="starting_capital">Starting Capital</label>
        <input type="number" class="form-control" id="starting_capital" name="starting_capital" value="<?= htmlspecialchars($goal['starting_capital']) ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Update Goal</button>
    </form>
  </div>
</div>
