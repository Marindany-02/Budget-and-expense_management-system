<?php 
require_once('../config.php');
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
<body class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini-md sidebar-mini-xs" style="height: auto;">
  <div class="wrapper">
    <?php require_once('inc/topBarNav.php') ?>
    <?php require_once('inc/navigation.php') ?>


    <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
        <!-- Flash Success Message -->
        <?php if (isset($_SESSION['flashdata']['success'])): ?>
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055; right: 1rem; top: 1rem;">
    <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          <?= $_SESSION['flashdata']['success']; ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <?php unset($_SESSION['flashdata']['success']); ?>
<?php endif; ?>


    <!-- Content Wrapper -->
    <div class="content-wrapper pt-3" style="min-height: 567.854px;">
      <section class="content text-dark">
        <div class="container-fluid">
          <?php 
            if (!file_exists($page.".php") && !is_dir($page)) {
              include '404.html';
            } else {
              if (is_dir($page))
                include $page.'/index.php';
              else
                include $page.'.php';
            }
          ?>
        </div>
      </section>

      <!-- Modals -->
      <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Confirmation</h5></div>
            <div class="modal-body"><div id="delete_content"></div></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='confirm'>Continue</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"></h5></div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="uni_modal_right" role='dialog'>
        <div class="modal-dialog modal-full-height modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="fa fa-arrow-right"></span>
              </button>
            </div>
            <div class="modal-body"></div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="viewer_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
            <img src="" alt="">
          </div>
        </div>
      </div>

    </div>
    <!-- /.content-wrapper -->

    <?php require_once('inc/footer.php') ?>
    <script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.querySelector('.toast');
    if (toastEl) {
      const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
      toast.show();
    }
  });
</script>

  </div>
</body>
</html>
