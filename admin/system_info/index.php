<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
	img#cimg2{
		height: 50vh;
		width: 100%;
		object-fit: contain;
		/* border-radius: 100% 100%; */
	}
</style>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h5 class="card-title">System Information</h5>
			<!-- <div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_department" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div> -->
		</div>
		<div class="card-body">
			<form action="" id="system-frm">
			<div id="msg" class="form-group"></div>
			<div class="form-group">
				<label for="name" class="control-label">System Name</label>
				<input type="text" class="form-control form-control-sm" name="name" id="name" value="<?php echo $_settings->info('name') ?>">
			</div>
			<div class="form-group">
				<label for="short_name" class="control-label">System Short Name</label>
				<input type="text" class="form-control form-control-sm" name="short_name" id="short_name" value="<?php echo  $_settings->info('short_name') ?>">
			</div>
			<div class="form-group">
				<label for="" class="control-label">System Logo</label>
				<div class="custom-file">
	              <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
	              <label class="custom-file-label" for="customFile">Choose file</label>
	            </div>
			</div>
			<div class="form-group d-flex justify-content-center">
				<img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
			</div>
			</form>
		</div>
		<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary" form="system-frm">Update</button>
				</div>
			</div>
		</div>

	</div>
</div>
<script>
	$(document).ready(function(){
		$('#system-frm').submit(function(e){
			e.preventDefault();
			var _this = $(this);
			$('.err-msg').remove();
			start_loader();

			// Get the user ID from the session
			var user_id = <?php echo $_SESSION['user_id']; ?>; // Ensure that user_id is set in the session

			// Append the user_id to the form data
			var formData = new FormData($(this)[0]);
			formData.append("user_id", user_id); // Add the user_id to form data

			$.ajax({
				url: _base_url_ + "classes/Master.php?f=update_system_info", // Assuming the backend script handles the update
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error: err => {
					console.log(err);
					alert_toast("An error occurred", 'error');
					end_loader();
				},
				success: function(resp){
					if (typeof resp == 'object' && resp.status == 'success') {
						alert_toast("System information updated successfully", 'success');
					} else {
						alert_toast("An error occurred", 'error');
					}
					end_loader();
				}
			})
		});

		// File preview functionality
		$('#customFile').change(function(){
			displayImg(this, $(this));
		});
	});
</script>
