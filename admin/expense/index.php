<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Expense Management</h3>
		<div class="card-tools">
			<a href="?page=expense/manage_expense" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
		<div class="table-responsive">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="15%">
					<col width="30%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Category</th>
						<th>Amount</th>
						<th>Remarks</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
// Get user ID based on username stored in session
$user = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'")->fetch_assoc(); 
$user_id = $user['id']; // Get user_id

$i = 1;
// Only fetch records belonging to this user
$qry = $conn->query("SELECT r.*, c.category, c.balance 
                     FROM `running_balance` r 
                     INNER JOIN `categories` c ON r.category_id = c.id 
                     WHERE c.status = 1 AND r.balance_type = 2 AND r.user_id = '{$user_id}'
                     ORDER BY UNIX_TIMESTAMP(r.date_created) DESC");

while($row = $qry->fetch_assoc()):
	foreach($row as $k => $v){
		$row[$k] = trim(stripslashes($v));
	}
	$row['remarks'] = strip_tags(stripslashes(html_entity_decode($row['remarks'])));
?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['category'] ?></td>
							<td ><p class="m-0 text-right"><?php echo number_format($row['amount']) ?></p></td>
							<td ><p class="m-0 truncate"><?php echo ($row['remarks']) ?></p></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
								  <a class="dropdown-item" href="?page=expense/manage_expense&id=<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">
								  <span class="fa fa-edit text-primary"></span> Edit
</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-category_id="<?php echo $row['category_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$('.edit_expense').click(function(){
		var id = $(this).data('id');
		uni_modal("Edit Expense", "expense/manage_expense.php?id=" + id, "mid-large");
	});

	$(document).ready(function(){

		$('.delete_data').click(function(){
			_conf("Are you sure to delete this expense permanently?","delete_expense",[$(this).attr('data-id'),$(this).attr('data-category_id')])
		})
		$('#uni_modal').on('show.bs.modal',function(){
			$('.summernote').summernote({
		        height: 200,
		        toolbar: [
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'para', [ 'ol', 'ul' ] ],
		            [ 'view', [ 'undo', 'redo'] ]
		        ]
		    })
		})
		$('.table').dataTable({
			columnDefs: [
				{ orderable: false, targets: 5 }
			],
			order: [[0, 'asc']]
		});
	})
	function delete_expense($id,$category_id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_expense",
			method:"POST",
			data:{id: $id,category_id: $category_id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
<script>
	$(document).on('click', '.edit-expense', function() {
	let id = $(this).data('id');
	// Fetch the data for the given ID
	$.ajax({
		url: 'ajax.php?action=get_expense',
		method: 'POST',
		data: { id: id },
		dataType: 'json',
		success: function(resp) {
			if (resp.status === 'success') {
				// Populate the form fields
				$('#expense_id').val(resp.data.id);
				$('#amount').val(resp.data.amount);
				$('#remarks').val(resp.data.remarks);
				$('#category_id').val(resp.data.category_id).trigger('change');
				// Change button text to "Update" maybe
				$('#saveBtn').text('Update Expense');
			} else {
				alert(resp.msg || 'Failed to fetch expense data.');
			}
		},
		error: function(err) {
			console.log(err);
			alert('An error occurred while fetching the expense.');
		}
	});
});

</script>