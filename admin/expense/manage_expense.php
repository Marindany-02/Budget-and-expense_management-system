<?php
include_once(__DIR__ . '/../../config.php');
?>
<?php 
$id = isset($_GET['id']) ? $_GET['id'] : '';
$form_title = "Add Expense";
if(!empty($id)){
    $qry = $conn->query("SELECT * FROM running_balance WHERE id = '$id'");
    if($qry->num_rows > 0){
        $data = $qry->fetch_assoc();
        foreach($data as $k => $v){
            $$k = $v;
        }
        $form_title = "Update Expense";
    }
}
?>

<h4 class="mb-3 fw-bold"><?= $form_title ?></h4>

<div class="card shadow rounded p-4">
    <form action="" id="expense-form">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
        <input type="hidden" name="balance_type" value="2"> <!-- 2 = Expense -->

        <div class="form-group mb-3">
            <label for="category_id" class="control-label fw-bold">Category</label>
            <select name="category_id" id="category_id" class="form-control select2" required>
                <option value="" disabled selected>Select Category</option>
                <?php 
                $user_id = $_settings->userdata('id');
                $cat_qry = $conn->query("SELECT c.*, 
                    (SELECT IFNULL(SUM(amount),0) FROM running_balance rb WHERE rb.category_id = c.id AND rb.user_id = c.user_id AND rb.balance_type = 1) -
                    (SELECT IFNULL(SUM(amount),0) FROM running_balance rb WHERE rb.category_id = c.id AND rb.user_id = c.user_id AND rb.balance_type = 2) AS balance
                FROM categories c 
                WHERE c.status = 1 AND c.user_id = '{$user_id}' 
                ORDER BY c.category ASC");

                while($row = $cat_qry->fetch_assoc()):
                    $bal = number_format($row['balance']);
                ?>
                    <option value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>>
                        <?= $row['category'] ?> (<?= $bal ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="amount" class="control-label fw-bold">Amount</label>
            <input type="text" name="amount" id="amount" class="form-control text-end" required value="<?= isset($amount) ? number_format($amount) : '' ?>">
        </div>

        <div class="form-group mb-4">
            <label for="remarks" class="control-label fw-bold">Remarks</label>
            <textarea rows="3" name="remarks" id="remarks" class="form-control"><?= isset($remarks) ? $remarks : '' ?></textarea>
        </div>

        <div class="form-group d-flex justify-content-between">
            <a href="./?page=expense/index" class="btn btn-secondary px-4">← Back to Index</a>
            <button type="submit" class="btn btn-primary px-4">
                <?= !empty($id) ? 'Update Expense' : 'Save Expense' ?>
            </button>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#expense-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_expense",
                method: "POST",
                data: _this.serialize(),
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp){
                    if(resp.status === 'success'){
                        alert_toast("Expense saved successfully", 'success');
                        setTimeout(function(){
                            location.href = _base_url_ + "admin/?page=expense/index";
                        }, 1500);
                    } else {
                        alert_toast(resp.msg || "An error occurred", 'error');
                        end_loader();
                    }
                }
            });
        });

        $('.select2').select2({
            placeholder: "Select here",
            width: "100%"
        });
    });
</script>
