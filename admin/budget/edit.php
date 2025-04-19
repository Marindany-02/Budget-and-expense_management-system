<?php
include_once(__DIR__ . '/../../config.php');
if (!isset($base_url)) {
    $base_url = 'http://localhost/expense_budget/'; // fallback base_url
}
// Redirect if no ID is provided
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$budget_id = $_GET['id'];

// Fetch the budget entry
$query = $conn->prepare("SELECT r.*, c.category FROM `running_balance` r 
    INNER JOIN `categories` c ON r.category_id = c.id 
    WHERE r.id = ? LIMIT 1");
$query->bind_param('i', $budget_id);
$query->execute();
$result = $query->get_result();
$budget = $result->fetch_assoc();

if (!$budget) {
    header('Location: index.php');
    exit;
}

// Fetch all categories for dropdown
$categories_query = $conn->query("SELECT * FROM categories 
    WHERE status = 1 AND user_id = '{$_SESSION['id']}' ORDER BY category ASC");

// Handle update on POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $remarks = $_POST['remarks'];

    if (empty($category_id) || empty($amount)) {
        $_settings->set_flashdata('error', 'Category and amount are required.');
    } else {
        // Fetch the original running_balance record
        $old = $conn->query("SELECT category_id, amount FROM running_balance WHERE id = '$budget_id'");
        if ($old && $old->num_rows > 0) {
            $old_data = $old->fetch_assoc();
            $old_cat_id = $old_data['category_id'];
            $old_amount = $old_data['amount'];

            // Revert the old amount from the old category
            $conn->query("UPDATE categories SET balance = balance - $old_amount WHERE id = '$old_cat_id'");

            // Apply the new amount to the new category
            $conn->query("UPDATE categories SET balance = balance + $amount WHERE id = '$category_id'");

            // Update the running_balance record
            $update = $conn->prepare("UPDATE running_balance SET category_id = ?, amount = ?, remarks = ? WHERE id = ?");
            $update->bind_param('iisi', $category_id, $amount, $remarks, $budget_id);

            if ($update->execute()) {
                $_settings->set_flashdata('success', 'Budget updated successfully.');
                header('Location: ?page=budget');
                exit;
            } else {
                $_settings->set_flashdata('error', 'Error updating budget.');
            }
        } else {
            $_settings->set_flashdata('error', 'Budget entry not found.');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Budget</title>
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/bootstrap.min.css">
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h3 class="fw-bold mb-4 text-center text-warning">Edit Budget</h3>

            <!-- Flash Message -->
            <?php if ($_settings->chk_flashdata('success')): ?>
                <div class="alert alert-success"><?= $_settings->flashdata('success') ?></div>
            <?php elseif ($_settings->chk_flashdata('error')): ?>
                <div class="alert alert-danger"><?= $_settings->flashdata('error') ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <form method="POST">
                        <input type="hidden" name="budget_id" value="<?= $budget['id'] ?>">

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <?php while ($cat = $categories_query->fetch_assoc()): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $budget['category_id']) ? 'selected' : '' ?>>
                                        <?= $cat['category'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label fw-semibold">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control text-end" value="<?= $budget['amount'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label fw-semibold">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="4"><?= $budget['remarks'] ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= $base_url ?>admin/?page=budget" class="btn btn-secondary">
                                ← Back to Budget List
                            </a>
                            <button type="submit" class="btn btn-primary">Save Budget Changes</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= $base_url ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
