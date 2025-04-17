<?php
$user = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'")->fetch_assoc();
$user_id = $user['id'];
?>


<?php
// Fetch reminders that are due within the next 7 days
$reminders = $conn->query("SELECT * FROM reminders 
                           WHERE user_id = '$user_id' 
                             AND reminder_date >= CURDATE() 
                             AND reminder_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                             AND status = 'Pending'
                           ORDER BY reminder_date ASC");
?>
<style>
  .info-tooltip,.info-tooltip:focus,.info-tooltip:hover{
    background:unset;
    border:unset;
    padding:unset;
  }
</style>
<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-money-bill-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Current Overall Budget</span>
                <span class="info-box-number text-right">
                  <?php 
$cur_bul = $conn->query("SELECT SUM(balance) AS total 
FROM `categories` 
WHERE status = 1 AND user_id = '$user_id'")->fetch_assoc()['total'];
echo number_format($cur_bul);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-day"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Today's Budget Entries</span>
                <span class="info-box-number text-right">
                  <?php 
$today_budget = $conn->query("SELECT SUM(amount) AS total 
FROM `running_balance` 
WHERE category_id IN (SELECT id FROM categories WHERE status = 1 AND user_id = '$user_id') 
  AND DATE(date_created) = '".date("Y-m-d")."' 
  AND balance_type = 1")->fetch_assoc()['total'];
echo number_format($today_budget);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>
          <?php
$mpesa = $conn->query("SELECT balance FROM mpesa_balance WHERE user_id = '$user_id'")->fetch_assoc();
$mpesa_balance = number_format($mpesa['balance'], 2);
?>

<div class="col-12 col-sm-6 col-md-3">
  <div class="info-box">
    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-wallet"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">M-Pesa Balance</span>
      <span class="info-box-number text-right"><?php echo $mpesa_balance; ?></span>
    </div>
  </div>
</div>

          <div class="col-12 col-sm-6 col-md-3">
            
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calendar-day"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Today's Budget Expenses</span>
                <span class="info-box-number text-right">
                <?php 
$today_expense = $conn->query("SELECT SUM(amount) AS total 
FROM `running_balance` 
WHERE category_id IN (SELECT id FROM categories WHERE status = 1 AND user_id = '$user_id') 
  AND DATE(date_created) = '".date("Y-m-d")."' 
  AND balance_type = 2")->fetch_assoc()['total'];
echo number_format($today_expense);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div>
        <div class="row">
  <div class="col-lg-12">
    <h4>Upcoming Reminders (Next 7 Days)</h4>
    <hr>
  </div>
</div>

<div class="col-md-12">
  <?php if ($reminders->num_rows > 0): ?>
    <ul class="list-group">
      <?php while ($reminder = $reminders->fetch_assoc()): ?>
        <li class="list-group-item">
          <strong><?php echo htmlspecialchars($reminder['title']); ?></strong>
          <span class="badge badge-info float-right"><?php echo date('F j, Y', strtotime($reminder['reminder_date'])); ?></span>
          <p><?php echo nl2br(htmlspecialchars($reminder['description'])); ?></p>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>No reminders within the next 7 days.</p>
  <?php endif; ?>
</div>
<div class="row">
  <div class="col-lg-12">
    <h4>Current Budget in each Categories</h4>
    <hr>
  </div>
</div>
<div class="col-md-12 d-flex justify-content-center">
  <div class="input-group mb-3 col-md-5">
    <input type="text" class="form-control" id="search" placeholder="Search Category">
    <div class="input-group-append">
      <span class="input-group-text"><i class="fa fa-search"></i></span>
    </div>
  </div>
</div>
<div class="row row-cols-4 row-cols-sm-1 row-cols-md-4 row-cols-lg-4">
  <?php 
$categories = $conn->query("SELECT * FROM `categories` 
WHERE status = 1 AND user_id = '$user_id' 
ORDER BY `category` ASC");
while($row = $categories->fetch_assoc()):
  ?>
  <div class="col p-2 cat-items">
    <div class="callout callout-info">
      <span class="float-right ml-1">
        <button type="button" class="btn btn-secondary info-tooltip" data-toggle="tooltip" data-html="true" title='<?php echo (html_entity_decode($row['description'])) ?>'>
          <span class="fa fa-info-circle text-info"></span>
        </button>
      </span>
      <h5 class="mr-4"><b><?php echo $row['category'] ?></b></h5>
      <div class="d-flex justify-content-end">
        <b><?php echo number_format($row['balance']) ?></b>
      </div>
    </div>
  </div>
  <?php endwhile; ?>
</div>
<div class="col-md-12">
  <h3 class="text-center" id="noData" style="display:none">No Data to display.</h3>
</div>
<script>
  function check_cats(){
    if($('.cat-items:visible').length > 0){
      $('#noData').hide('slow')
    }else{
      $('#noData').show('slow')
    }
  }
  $(function(){
    $('[data-toggle="tooltip"]').tooltip({
      html:true
    })
    check_cats()
    $('#search').on('input',function(){
      var _f = $(this).val().toLowerCase()
      $('.cat-items').each(function(){
        var _c = $(this).text().toLowerCase()
        if(_c.includes(_f) == true)
          $(this).toggle(true);
        else
          $(this).toggle(false);
      })
    check_cats()
    })
  })
</script>
