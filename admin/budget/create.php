<?php
include_once(__DIR__ . '/../../config.php');
if (!isset($base_url)) {
    $base_url = 'http://localhost/expense_budget/'; // fallback base_url
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Budget</title>
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold mb-4 text-center text-primary">Add New Budget</h3>

            <div class="card shadow-sm rounded">
                <div class="card-body p-4">
                    <form action="" id="budget-form">
                        <input type="hidden" name="balance_type" value="1">

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">Category</label>
                            <select name="category_id" id="category_id" class="form-control select2" required>
                                <option value="">-- Select Category --</option>
                                <?php
                                $qry = $conn->query("SELECT * FROM categories WHERE user_id = '{$_SESSION['id']}' ORDER BY category ASC");
                                while ($row = $qry->fetch_assoc()):
                                ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['category'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label fw-semibold">Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control number text-end" value="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label fw-semibold">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="Optional"></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="<?= $base_url ?>admin/?page=budget" class="btn btn-secondary">
                                ← Back to Budget List
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                Save Budget
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= $base_url ?>assets/js/jquery.min.js"></script>
<script src="<?= $base_url ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('.select2').select2({ placeholder: "Select Category", width: '100%' });

    $('.number').on('input', function () {
        let val = $(this).val().replace(/[^0-9.]/g, '');
        let parts = val.split('.');
        if (parts.length > 2) val = parts[0] + '.' + parts[1];
        $(this).val(val);
    });

    $('#budget-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '<?= $base_url ?>classes/Master.php?f=save_budget',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function () {
                // Optional: loader
            },
            success: function (resp) {
                if (resp.status === 'success') {
                    alert('✅ Budget saved successfully!');
                    window.location.href = '<?= $base_url ?>admin/?page=budget';
                } else if (resp.status === 'failed') {
                    alert('❌ Error: ' + resp.msg);
                } else {
                    alert('⚠️ Unexpected error occurred.');
                    console.log(resp);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('❌ An error occurred while saving.');
            }
        });
    });
});
</script>
</body>
</html>
