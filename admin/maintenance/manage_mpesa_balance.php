<?php
include_once(__DIR__ . '/../../config.php');
$user_id = $_settings->userdata('id');

$message = '';
$msg_class = '';
$edit_mode = false;
$edit_data = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $type = $_POST['type'] ?? '';
    $remarks = isset($_POST['remarks']) ? $conn->real_escape_string($_POST['remarks']) : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // ADD transaction
    if ($action === 'add') {
        if ($amount <= 0 || !in_array($type, ['credit', 'debit'])) {
            $message = "Please enter a valid amount and type.";
            $msg_class = "danger";
        } else {
            $transaction = 'TXN' . date('YmdHis');
            $stmt = $conn->prepare("INSERT INTO mpesa_topups (user_id, amount, remarks, type, transaction) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("idsss", $user_id, $amount, $remarks, $type, $transaction);
            if ($stmt->execute()) {
                $bal_query = "SELECT id FROM mpesa_balance WHERE user_id = '$user_id'";
                $result = $conn->query($bal_query);
                if ($result->num_rows > 0) {
                    $adjust = ($type == 'credit') ? "+$amount" : "-$amount";
                    $conn->query("UPDATE mpesa_balance SET balance = balance $adjust WHERE user_id = '$user_id'");
                } else {
                    if ($type == 'credit') {
                        $conn->query("INSERT INTO mpesa_balance (user_id, balance) VALUES ('$user_id', '$amount')");
                    }
                }
                $message = "Transaction added successfully.";
                $msg_class = "success";
            } else {
                $message = "Failed to add transaction.";
                $msg_class = "danger";
            }
        }
    }

    // UPDATE transaction
    if ($action === 'update' && $id > 0) {
        if ($amount <= 0 || !in_array($type, ['credit', 'debit'])) {
            $message = "Please enter a valid amount and type.";
            $msg_class = "danger";
        } else {
            // STEP 1: Get the original transaction
            $stmt = $conn->prepare("SELECT amount, type FROM mpesa_topups WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result && $result->num_rows === 1) {
                $original = $result->fetch_assoc();
                $old_amount = $original['amount'];
                $old_type = $original['type'];
    
                // STEP 2: Get current balance
                $bal_stmt = $conn->prepare("SELECT balance FROM mpesa_balance WHERE user_id = ?");
                $bal_stmt->bind_param("i", $user_id);
                $bal_stmt->execute();
                $bal_result = $bal_stmt->get_result();
                $bal_data = $bal_result->fetch_assoc();
                $current_balance = (float)$bal_data['balance'];
    
                // STEP 3: Reverse old transaction
                if ($old_type === 'credit') {
                    $current_balance -= $old_amount;
                } elseif ($old_type === 'debit') {
                    $current_balance += $old_amount;
                }
    
                // STEP 4: Apply new transaction
                if ($type === 'credit') {
                    $current_balance += $amount;
                } elseif ($type === 'debit') {
                    $current_balance -= $amount;
                }
    
                // STEP 5: Update the transaction
                $stmt = $conn->prepare("UPDATE mpesa_topups SET amount = ?, remarks = ?, type = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("dssii", $amount, $remarks, $type, $id, $user_id);
    
                if ($stmt->execute()) {
                    // STEP 6: Update the balance
                    $update_bal = $conn->prepare("UPDATE mpesa_balance SET balance = ? WHERE user_id = ?");
                    $update_bal->bind_param("di", $current_balance, $user_id);
                    $update_bal->execute();
    
                    $message = "Transaction updated successfully.";
                    $msg_class = "success";
                } else {
                    $message = "Update failed.";
                    $msg_class = "danger";
                }
            } else {
                $message = "Transaction not found.";
                $msg_class = "danger";
            }
        }
    }
    

    // DELETE transaction
    if ($action === 'delete' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM mpesa_topups WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        if ($stmt->execute()) {
            $message = "Transaction deleted successfully.";
            $msg_class = "success";
        } else {
            $message = "Delete failed.";
            $msg_class = "danger";
        }
    }
}

// Check for edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM mpesa_topups WHERE id = $edit_id AND user_id = $user_id");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
        $edit_mode = true;
    }
}

// Get balance
$bal = $conn->query("SELECT balance FROM mpesa_balance WHERE user_id = '$user_id'");
$balance = $bal->num_rows ? $bal->fetch_assoc()['balance'] : 0;
$phone = $conn->query("SELECT phone FROM mpesa_balance WHERE user_id = '$user_id'")->fetch_assoc()['phone'] ?? '0000000000';

// Fetch latest 10 transactions
$transactions = $conn->query("SELECT * FROM mpesa_topups WHERE user_id = '$user_id' ORDER BY date_created DESC");

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.11/jspdf.plugin.autotable.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<div class="container-fluid">

    <h4>MPESA Balance: <span class="text-success">Ksh <?php echo number_format($balance, 2); ?>-(<?php echo ($phone); ?>)</span></h4><!-- Export Button -->
    <button class="btn btn-success" data-toggle="modal" data-target="#exportModal">Export Report</button>


    <?php if ($message): ?>
        <div class="alert alert-<?php echo $msg_class; ?>" id="messageAlert">
            <?php echo $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            setTimeout(function() {
                const messageAlert = document.getElementById('messageAlert');
                if (messageAlert) {
                    messageAlert.style.display = 'none';
                }
            }, 3000);
        </script>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <input type="hidden" name="id" value="<?php echo $edit_mode ? $edit_data['id'] : ''; ?>">

        <div class="form-group">
            <label>Transaction Type</label>
            <select name="type" class="form-control" required>
                <option value="credit" <?php echo ($edit_mode && $edit_data['type'] === 'credit') ? 'selected' : ''; ?>>Credit</option>
                <option value="debit" <?php echo ($edit_mode && $edit_data['type'] === 'debit') ? 'selected' : ''; ?>>Debit</option>
            </select>
        </div>

        <div class="form-group">
            <label>Amount</label>
            <input type="number" name="amount" class="form-control" min="1" required value="<?php echo $edit_mode ? $edit_data['amount'] : ''; ?>">
        </div>

        <div class="form-group">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control"><?php echo $edit_mode ? $edit_data['remarks'] : ''; ?></textarea>
        </div>

        <button type="submit" class="btn btn-<?php echo $edit_mode ? 'warning' : 'primary'; ?>">
            <?php echo $edit_mode ? 'Update Transaction' : 'Add Transaction'; ?>
        </button>
        <?php if ($edit_mode): ?>
            <a href="?page=maintenance/manage_mpesa_balance" class="btn btn-secondary ml-2">Cancel</a>
        <?php endif; ?>
    </form>
<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Number of Months</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="exportForm">
          <div class="form-group">
            <label for="months">Number of Months:</label>
            <select class="form-control" id="months" required>
              <option value="1">1 Month</option>
              <option value="2">2 Months</option>
              <option value="3">3 Months</option>
              <option value="6">6 Months</option>
              <option value="12">12 Months</option>
              <option value="all">All</option>
            </select>
          </div>
          <button type="button" class="btn btn-success" id="generateReport">Generate Report</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
    <hr>
    <h5>Recent Transactions</h5>

    <input type="text" id="searchInput" class="form-control mb-2" placeholder="Search by remarks or type...">
    <div class="mb-2">
    <label for="entriesToShow">Show entries:</label>
    <select id="entriesToShow" class="form-control w-auto d-inline-block ml-2">
        <option value="10" selected>10</option>
        <option value="20">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="500">500</option>
        <option value="all">All</option>
    </select>
</div>
    <table class="table table-bordered table-striped" id="transactionTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Remarks</th>
                <th>Transaction</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($transactions && $transactions->num_rows > 0): $i = 1; ?>
            <?php while ($row = $transactions->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['date_created'])); ?></td>
                    <td class="text-right">Ksh <?php echo number_format($row['amount'], 2); ?></td>
                    <td><span class="badge badge-<?php echo $row['type'] === 'credit' ? 'success' : 'danger'; ?>"><?php echo ucfirst($row['type']); ?></span></td>
                    <td><?php echo $row['remarks']; ?></td>
                    <td><?php echo $row['transaction']; ?></td>
                    <td>
                    <a href="?page=maintenance/manage_mpesa_balance&edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this transaction?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No transactions found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
// Search functionality
document.getElementById("searchInput").addEventListener("keyup", function () {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll("#transactionTable tbody tr");

    rows.forEach(row => {
        const remarks = row.cells[4].innerText.toLowerCase();
        const type = row.cells[3].innerText.toLowerCase();
        const transaction = row.cells[5].innerText.toLowerCase();
        row.style.display = (remarks.includes(value) || type.includes(value) || transaction.includes(value)) ? "" : "none";
    });
});

// Show selected number of entries
document.getElementById("entriesToShow").addEventListener("change", function () {
    const limit = this.value === "all" ? Infinity : parseInt(this.value);
    const rows = document.querySelectorAll("#transactionTable tbody tr");

    rows.forEach((row, index) => {
        row.style.display = index < limit ? "" : "none";
    });
});

// Trigger once on load to apply initial limit
document.getElementById("entriesToShow").dispatchEvent(new Event("change"));

</script>
<script>
// Show selected number of entries
document.getElementById("entriesToShow").addEventListener("change", function () {
    const selected = this.value;
    const rows = document.querySelectorAll("#transactionTable tbody tr");

    rows.forEach((row, index) => {
        if (selected === "all") {
            row.style.display = "";
        } else {
            row.style.display = index < parseInt(selected) ? "" : "none";
        }
    });
});

// Ensure entries are limited on page load (default 10)
window.addEventListener("DOMContentLoaded", () => {
    document.getElementById("entriesToShow").dispatchEvent(new Event("change"));
});
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</script>
<!-- jsPDF + autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const { jsPDF } = window.jspdf;

  document.getElementById("generateReport").addEventListener("click", function () {
    const selectedMonths = document.getElementById("months").value;

    fetch(`maintenance/export_mpesa.php?months=${selectedMonths}`)
      .then(response => response.json())
      .then(result => {
        const doc = new jsPDF();

        const transactions = result.transactions;
        const balance = parseFloat(result.balance).toFixed(2);
        const phone = result.phone || '0000000000';

        const systemTitle = "Expense Budget Management System";
        const username = "<?php echo $_SESSION['username']; ?>"; // session-based
        const generatedOn = new Date().toLocaleString();

        doc.setFontSize(18);
        doc.text(systemTitle, 14, 15);
        doc.setFontSize(12);
        doc.text("MPESA Transaction Report", 14, 25);

        // Phone number next to the balance
        doc.text("Report for: " + (selectedMonths === "all" ? "All Months" : selectedMonths + " Month(s)"), 14, 32);
        doc.text("Generated by: " + username, 14, 39);
        doc.text("Date Generated: " + generatedOn, 14, 46);

        doc.text("Current MPESA Balance: Ksh " + balance, 14, 53);
        doc.text("Phone Number: " + phone, 130, 53);  // Positioned next to balance

        let startY = 60;

        if (transactions.length === 0) {
          doc.text("No transactions found.", 14, startY);
        } else {
          doc.autoTable({
            startY: startY,
            head: [["Date", "Amount", "Type", "Remarks"]],
            body: transactions.map(txn => [
              txn.date_created,
              "Ksh " + parseFloat(txn.amount).toFixed(2),
              txn.type,
              txn.remarks
            ])
          });
        }

        doc.save("mpesa_report_" + selectedMonths + "_months.pdf");
      })
      .catch(error => {
        alert("Error fetching data: " + error);
        console.error(error);
      });
  });
});
</script>
