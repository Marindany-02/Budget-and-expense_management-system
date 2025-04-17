
<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
<?php
$logoutSuccess = isset($_GET['logout']) && $_GET['logout'] === 'success';
?>

<body class="hold-transition login-page bg-navy">
    <script>
    start_loader()
    </script>
    <?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
    </div>
<?php endif; ?>
<?php if ($logoutSuccess): ?>
  <div style="color: green; background: #e6ffe6; padding: 10px; border: 1px solid green; margin-bottom: 15px;">
    ✅ You have been logged out successfully.
  </div>
<?php endif; ?>
    <h2 class="text-center mb-4 pb-3"><?php echo $_settings->info('name') ?></h2>
    <div class="login-box">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg text-dark">Sign in to start your session</p>

                <form id="login-frm" action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-conten-center">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </form>

                <!-- Forgot Password Link -->
                <p class="mb-1">
                    <a href="forgot-password.php">I forgot my password</a>
                </p>

                <!-- Register Link -->
                <p class="mb-0">
                    <a href="register.php" class="text-center">Create a new account</a>
                </p>

            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <script>
    $(document).ready(function() {
        end_loader();
    })
    </script>
    <script>
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 3000);
</script>
<script>
  setTimeout(function() {
    let msg = document.querySelector('div');
    if (msg) msg.style.display = 'none';
  }, 3000); // hides after 3 seconds


  fetch('path_to_login_api', {
	method: 'POST',
	body: formData
})
.then(response => response.json())
.then(data => {
	if (data.status === 'success') {
		alert(data.message); // or show a Bootstrap alert
		window.location.href = 'dashboard.php';
	} else {
		alert(data.message);
	}
});

</script>


</body>

</html>
