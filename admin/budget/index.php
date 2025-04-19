<?php if($_settings->chk_flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $_settings->flashdata('success') ?></div>
<?php endif; ?>

<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
</script>
<?php endif; ?>

<?php if($_settings->chk_flashdata('error')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('error') ?>", 'error');
</script>
<?php endif; ?>


<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Budget Management</h3>
		<div class="card-tools">
			<a href="?page=budget/create" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span> Add New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-bordered table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="15%">
					<col width="25%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Category</th>
						<th>Amount</th>
						<th>Remarks</th>
						<th>User</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
<?php 
// Fetch user ID
$user_id_query = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$username = $_settings->userdata('username');
$user_id_query->bind_param("s", $username);
$user_id_query->execute();
$user_result = $user_id_query->get_result();
$user_id = $user_result->num_rows > 0 ? $user_result->fetch_assoc()['id'] : null;

if ($user_id) {
	// Fetch budget records
	$qry = $conn->prepare("SELECT r.*, c.category, c.balance, u.username 
		FROM `running_balance` r 
		INNER JOIN `categories` c ON r.category_id = c.id 
		INNER JOIN `users` u ON r.user_id = u.id
		WHERE c.status = 1 AND r.balance_type = 1 AND r.user_id = ?
		ORDER BY UNIX_TIMESTAMP(r.date_created) DESC");

	$qry->bind_param("i", $user_id);
	$qry->execute();
	$result = $qry->get_result();

	$i = 1;
	while ($row = $result->fetch_assoc()) {
		foreach ($row as $k => $v) {
			$row[$k] = trim(stripslashes($v));
		}
		$row['remarks'] = strip_tags(stripslashes(html_entity_decode($row['remarks'])));
?>
					<tr>
						<td class="text-center"><?php echo $i++; ?></td>
						<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
						<td><?php echo $row['category'] ?></td>
						<td><p class="m-0 text-right"><?php echo number_format($row['amount']) ?></p></td>
						<td><p class="m-0 truncate"><?php echo $row['remarks'] ?></p></td>
						<td><?php echo $row['username'] ?></td>
						<td align="center">
							<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
								Action
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu" role="menu">
	<a class="dropdown-item manage_budget" href="?page=budget/edit&id=<?php echo $row['id']; ?>">
    <span class="fa fa-edit text-primary"></span> Edit
</a>
						<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-category_id="<?php echo $row['category_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
							</div>
						</td>
					</tr>
<?php 
	}
}
?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		// Initialize modal for adding new budget
		$('#manage_budget').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Budget", 'budget/manage_budget.php');
		});
		
		// Initialize modal for updating budget
		$('.manage_budget').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Update Budget", 'budget/manage_budget.php?id=' + $(this).attr('data-id'));
		});
		
		// Delete budget on click
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this budget permanently?", "delete_budget", [$(this).attr('data-id'), $(this).attr('data-category_id')]);
		});
		
		// Initialize summernote for modal content
		$('#uni_modal').on('show.bs.modal',function(){
			$('.summernote').summernote({
		        height: 200,
		        toolbar: [
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'para', [ 'ol', 'ul' ] ],
		            [ 'view', [ 'undo', 'redo'] ]
		        ]
		    });
		});
		
		// Initialize DataTable for the table
		$('.table').dataTable({
			columnDefs: [
				{ orderable: false, targets: 5 }
			],
			order: [[0, 'asc']]
		});
	});

	// Function to delete a budget entry
	function delete_budget($id, $category_id){
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_budget",
			method: "POST",
			data: {id: $id, category_id: $category_id},
			dataType: "json",
			error: err => {
				console.log(err);
				alert_toast("An error occurred.", 'error');
				end_loader();
			},
			success: function(resp){
				if(typeof resp == 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occurred.", 'error');
					end_loader();
				}
			}
		});
	}
</script>
