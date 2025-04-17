<?php require_once('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php'); ?>
<body class="hold-transition login-page bg-navy">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">🎯 Reset Password</h4>
            </div>
            <div class="card-body bg-navy">
                <form method="POST" action="forgot-password-action.php">
                    <div class="form-group mb-3">
                        <label class="text-white">First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-white">Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-white">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <button type="submit" name="verify_user" class="btn btn-primary w-100">Verify Identity</button>
                </form>
                <div class="text-center mt-3">
    <a href="login.php" class="text-info">🔐 Back to Login</a> |
    <a href="register.php" class="text-info">📝 Create Account</a>
</div>
            </div>
        </div>
    </div>
</body>
</html>
