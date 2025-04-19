<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    🎯 Contribution added successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php
include_once(__DIR__ . '/../../config.php');
if (!isset($_GET['id'])) {
    echo "🎯 Goal ID is missing!";
    exit;
}
$id = $_GET['id'];
$goal = $conn->query("SELECT * FROM goals WHERE id = $id")->fetch_assoc();
$contributions = $conn->query("SELECT * FROM goal_contributions WHERE goal_id = $id ORDER BY date_added DESC");

// Calculate total contribution (starting capital + added contributions)
$additional_total = 0;
while ($row = $contributions->fetch_assoc()) {
    $additional_total += $row['amount'];
}
$total_contribution = $goal['starting_capital'] + $additional_total;
$remaining = $goal['target_amount'] - $total_contribution;
?>

<!-- Load jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- Add Bootstrap CSS for better styling -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Add custom CSS for enhanced styling -->
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
  }
  
  .card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    background-color: white;
  }

  .card-header {
    background-color: #007bff;
    color: white;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
  }

  .card-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
  }

  .btn {
    border-radius: 5px;
    font-weight: 600;
  }

  .alert {
    border-radius: 8px;
  }

  h5 {
    margin-top: 20px;
    color: #007bff;
  }

  .form-group label {
    font-weight: 600;
  }

  .form-control {
    border-radius: 8px;
    padding: 10px;
  }

  .table th, .table td {
    padding: 12px;
    text-align: center;
  }

  .table {
    background-color: #f8f9fa;
    border-radius: 8px;
  }

  .table-striped tbody tr:nth-child(odd) {
    background-color: #f1f1f1;
  }

  #generatePDF {
    background-color: #28a745;
    color: white;
  }

  #generatePDF:hover {
    background-color: #218838;
  }

  .btn-secondary {
    background-color: #6c757d;
  }

  .btn-secondary:hover {
    background-color: #5a6268;
  }

  .alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
  }

  .btn-close {
    background-color: transparent;
    border: none;
    font-size: 1.5rem;
    color: #007bff;
  }

  .btn-close:hover {
    color: #0056b3;
  }
</style>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">🎯 Goal Details</h3>
    <div>
      <button class="btn btn-danger btn-sm" id="generatePDF">
        <i class="fa fa-file-pdf"></i> Generate PDF
      </button>
      <a href="?page=goals/list" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Back to List</a>
    </div>
  </div>
  <div class="card-body" id="goal-details">
    <h4 id="goal-name"><?= htmlspecialchars($goal['goal_name']) ?></h4>
    <p><strong>Description:</strong> <span id="goal-desc"><?= nl2br(htmlspecialchars($goal['goal_description'])) ?></span></p>
    <p><strong>🎯 Target Amount:</strong> KES <span id="goal-target"><?= number_format($goal['target_amount']) ?></span></p>
    <p><strong>Expected Completion:</strong> <span id="goal-completion"><?= $goal['expected_completion_date'] ?></span></p>
    <p><strong>💰 Starting Capital:</strong> KES <span id="goal-start"><?= number_format($goal['starting_capital']) ?></span></p>
    <p><strong>💸 Total Contributions:</strong> KES <span id="goal-total"><?= number_format($total_contribution) ?></span></p>
    <p><strong>📉 Remaining Balance:</strong> KES <span id="goal-remaining"><?= number_format($remaining) ?></span></p>

    <hr>
    <h5>Add Contribution <i class="fa fa-plus-circle"></i></h5>
    <form method="POST" action="goals/add_contribution.php">
        <input type="hidden" name="goal_id" value="<?= $id ?>">
        <div class="form-group">
            <label>🔑 Transaction Code</label>
            <input type="text" name="transaction_code" required class="form-control">
        </div>
        <div class="form-group">
            <label>💵 Amount (KES)</label>
            <input type="number" name="amount" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Submit</button>
    </form>

    <hr>
    <h5>📜 Contribution History</h5>
    <table class="table table-bordered table-striped" id="contrib-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Transaction Code</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $contributions->data_seek(0); // reset pointer
        $i = 1;
        while ($row = $contributions->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['transaction_code']) ?></td>
                <td><?= number_format($row['amount']) ?></td>
                <td><?= $row['date_added'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
  </div>
</div>

<script>
  setTimeout(function(){
    let alert = document.querySelector('.alert');
    if(alert){
      alert.classList.remove('show');
      alert.classList.add('fade');
    }
  }, 3000); // Hide after 3 seconds
</script>

<script>
  window.jsPDF = window.jspdf.jsPDF;

  document.getElementById("generatePDF").addEventListener("click", function () {
    const doc = new jsPDF();
    let y = 10;

    // Title
    doc.setFontSize(18);
    doc.setFont('helvetica', 'bold');
    doc.text("Goal Details Report", 105, y, { align: "center" });
    y += 15;

    // Set regular font for body
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');

    // Fetching goal details
    const name = document.getElementById("goal-name").textContent.trim();
    const desc = document.getElementById("goal-desc").textContent.trim();
    const target = document.getElementById("goal-target").textContent.trim();
    const start = document.getElementById("goal-start").textContent.trim();
    const completion = document.getElementById("goal-completion").textContent.trim();
    const total = document.getElementById("goal-total").textContent.trim();
    const remaining = document.getElementById("goal-remaining").textContent.trim();

    // Goal Details section
    doc.text("Goal Name:", 10, y);
    doc.text(name, 60, y);
    y += 7;

    doc.text("Description:", 10, y);
    doc.text(desc, 60, y);
    y += 7;

    doc.text("Target Amount:", 10, y);
    doc.text(`KES ${target}`, 60, y);
    y += 7;

    doc.text("Expected Completion:", 10, y);
    doc.text(completion, 60, y);
    y += 7;

    doc.text("Starting Capital:", 10, y);
    doc.text(`KES ${start}`, 60, y);
    y += 7;

    doc.text("Total Contributions:", 10, y);
    doc.text(`KES ${total}`, 60, y);
    y += 7;

    doc.text("Remaining Balance:", 10, y);
    doc.text(`KES ${remaining}`, 60, y);
    y += 15; // Adding space before the table

    // Contribution History Table Header
    const headers = ["No", "Code", "Amount (KES)", "Date"];
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text("Contribution History", 10, y);
    y += 10;

    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    doc.text(headers[0], 10, y);
    doc.text(headers[1], 45, y);
    doc.text(headers[2], 105, y);
    doc.text(headers[3], 150, y);
    y += 7;

    // Contribution Rows
    const rows = document.querySelectorAll("#contrib-table tbody tr");
    let rowIndex = 1;
    rows.forEach(row => {
      const cols = row.querySelectorAll("td");
      const line = [
        `${rowIndex}`, 
        cols[1].textContent.trim(),
        `KES ${cols[2].textContent.trim()}`,
        cols[3].textContent.trim()
      ];

      // Table rows content
      doc.text(line[0], 10, y);
      doc.text(line[1], 45, y);
      doc.text(line[2], 105, y);
      doc.text(line[3], 150, y);
      y += 7;
      rowIndex++;

      // Check if page overflow happens
      if (y > 270) {
        doc.addPage();
        y = 10;
        // Reprint the table headers on new page
        doc.setFont('helvetica', 'bold');
        doc.text(headers[0], 10, y);
        doc.text(headers[1], 45, y);
        doc.text(headers[2], 105, y);
        doc.text(headers[3], 150, y);
        y += 7;
      }
    });

    // Add Timestamp at the bottom
    const timestamp = new Date().toLocaleString(); // Get current date and time
    doc.setFontSize(10);
    doc.setFont('helvetica', 'italic');
    doc.text(`Generated on: ${timestamp}`, 10, y);
    y += 10;

    // Contact Details Section
    const contactDetails = [
 
    ];
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    contactDetails.forEach(line => {
      doc.text(line, 10, y);
      y += 6;
    });

    // Save the PDF
    doc.save(`${name}_details.pdf`);
  });
</script>
