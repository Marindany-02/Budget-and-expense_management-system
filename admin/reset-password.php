<?php 
require_once('../config.php'); 
if (!isset($_SESSION['reset_username'])) {
    header("Location: forgot-password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php'); ?>
<body class="hold-transition login-page bg-navy">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">🔐 Set New Password</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="reset-password-action.php">
                    <div class="form-group mb-3">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="reset_pass" class="btn btn-success w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
