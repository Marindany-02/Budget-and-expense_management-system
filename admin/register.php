<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php') ?>

<body class="hold-transition register-page bg-navy">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b><?php echo $_settings->info('name') ?></b> 🎯</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg text-dark">Register a new account</p>

                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
                <?php endif; ?>

                <form action="register-action.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="firstname" class="form-control" placeholder="First name" required>
                        <input type="text" name="lastname" class="form-control" placeholder="Last name" required>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="login.php">Already have an account? Sign In</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
