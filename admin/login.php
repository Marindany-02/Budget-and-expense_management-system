<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" style="height: auto;">
<?php require_once('inc/header.php') ?>

<?php
$logoutSuccess = isset($_GET['logout']) && $_GET['logout'] === 'success';
?>

<body class="hold-transition login-page bg-navy">
    <script>start_loader()</script>

    <!-- Flash Messages -->
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
        <div id="logout-msg" class="alert alert-success">
            ✅ You have been logged out successfully.
        </div>
    <?php endif; ?>

    <!-- Login Box -->
    <h2 class="text-center mb-4 pb-3"><?php echo $_settings->info('name') ?></h2>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg text-dark">Sign in to start your session</p>

                <form id="login-frm" action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" autofocus required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
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

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>

    <script>
        $(document).ready(function () {
            end_loader();

            // Auto-hide alerts
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 3000);
        });
    </script>
</body>
</html>
