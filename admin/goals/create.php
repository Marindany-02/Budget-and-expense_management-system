<?php
include_once(__DIR__ . '/../../config.php');

// ✅ Make sure session has started and user is logged in
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $goal_name = $_POST['goal_name'];
    $goal_description = $_POST['goal_description'];
    $target_amount = $_POST['target_amount'];
    $expected_completion_date = $_POST['expected_completion_date'];
    $starting_capital = $_POST['starting_capital'];
    $created_at = date('Y-m-d H:i:s');

    // ✅ Insert user_id into the database
    $stmt = $conn->prepare("INSERT INTO goals (user_id, goal_name, goal_description, target_amount, expected_completion_date, starting_capital, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdsss", $user_id, $goal_name, $goal_description, $target_amount, $expected_completion_date, $starting_capital, $created_at);

    if ($stmt->execute()) {
        header("Location: ?page=goals/list&msg=Goal added successfully");
        exit();
    } else {
        die("Error: " . $stmt->error);
    }
}
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add New Goal</h3>
  </div>
  <div class="card-body">
    <form action="" method="POST">
      <div class="form-group">
        <label for="goal_name">Goal Name</label>
        <input type="text" class="form-control" id="goal_name" name="goal_name" required>
      </div>

      <div class="form-group">
        <label for="goal_description">Description</label>
        <textarea class="form-control" id="goal_description" name="goal_description" required></textarea>
      </div>

      <div class="form-group">
        <label for="target_amount">Target Amount</label>
        <input type="number" class="form-control" id="target_amount" name="target_amount" required>
      </div>

      <div class="form-group">
        <label for="expected_completion_date">Expected Completion Date</label>
        <input type="date" class="form-control" id="expected_completion_date" name="expected_completion_date" required>
      </div>

      <div class="form-group">
        <label for="starting_capital">Starting Capital</label>
        <input type="number" class="form-control" id="starting_capital" name="starting_capital" required>
      </div>

      <button type="submit" class="btn btn-primary">Add Goal</button>
    </form>
  </div>
</div>
