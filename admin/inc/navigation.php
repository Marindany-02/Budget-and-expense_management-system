<!-- === STYLES FOR DARK/LIGHT THEMES === -->
<style>
  /* === DEFAULT THEME (DARK SIDEBAR) === */
  .main-sidebar {
    background-color: #001f3f !important;
    color: white !important;
  }
  .main-sidebar a,
  .main-sidebar .nav-icon {
    color: white !important;
  }

  /* === LIGHT SIDEBAR THEME === */
  .light-nav .main-sidebar {
    background-color:rgb(248, 250, 254) !important;
    color: #1f2d3d !important;
  }
  .light-nav .main-sidebar a,
  .light-nav .main-sidebar .nav-icon {
    color: #1f2d3d !important;
  }

  /* === BODY CONTRAST ADJUSTMENT === */
  .contrast-adjusted .content-wrapper {
    filter: contrast(1.1) brightness(1.05);
    background-color: #ffffff !important;
    color: #000000 !important;
  }

  .contrast-adjusted .content-wrapper a {
    color: #0056b3;
  }

  /* === ACTIVE NAV ITEM === */
  .main-sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.1) !important;
  }
</style>

<!-- === MAIN SIDEBAR === -->
<aside class="main-sidebar sidebar-navy-primary bg-navy elevation-4 sidebar-no-expand">
  <!-- Brand Logo -->
  <a href="<?php echo base_url ?>admin" class="brand-link bg-primary text-sm">
    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Store Logo" class="brand-image img-circle elevation-3 border-1" style="opacity: .8; width: 2.5rem; height: 2.5rem;">
    <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="clearfix"></div>

    <!-- Theme Toggle Button -->
    <div class="p-2 text-center">
      <button id="theme-toggle" class="btn btn-sm btn-light w-100">🌗 Switch Theme</button>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-4">
      <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item"><a href="./" class="nav-link nav-home"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=budget" class="nav-link nav-budget"><i class="nav-icon fas fa-wallet"></i><p>Budget Management</p></a></li>
        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=expense" class="nav-link nav-expense"><i class="nav-icon fas fa-money-bill-wave"></i><p>Expense Management</p></a></li><hr>
        <li class="nav-header">Reports</li>
        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=reports/budget" class="nav-link nav-reports-budget"><i class="nav-icon fas fa-file"></i><p>Budget Report</p></a></li>
        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=reports/expense" class="nav-link nav-reports-expense"><i class="nav-icon fas fa-file-alt"></i><p>Expense Report</p></a></li>
        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=reports/goals" class="nav-link nav-reports-expense"><i class="nav-icon fas fa-file-alt"></i><p>Goals Report</p></a></li>
<hr>
        <li class="nav-header">Maintenance</li>
        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=maintenance/category" class="nav-link nav-maintenance_category"><i class="nav-icon fas fa-th-list"></i><p>Category List</p></a></li>
        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=maintenance/manage_mpesa_balance" class="nav-link nav-manage_mpesa_balance"><i class="nav-icon fas fa-wallet"></i><p>M-Pesa Balance</p></a>
</li>

        <li class="nav-item"><a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info"><i class="nav-icon fas fa-cogs"></i><p>Settings</p></a></li>
        <!-- Reminders -->
<hr>
        <li class="nav-item has-treeview"><a href="#" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>Reminders<i class="right fas fa-angle-left"></i></p></a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo base_url ?>admin/?page=reminders/list" class="nav-link nav-reminders-list">
        <i class="fas fa-list nav-icon"></i>
        <p>Reminder List</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo base_url ?>admin/?page=reminders/create" class="nav-link nav-reminders-create">
        <i class="fas fa-plus-circle nav-icon"></i>
        <p>Add Reminder</p>
      </a>
    </li>
  </ul>
</li>

<!-- Goals -->
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-bullseye"></i>
    <p>
      Goals
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo base_url ?>admin/?page=goals/list" class="nav-link nav-goals-list">
        <i class="fas fa-list nav-icon"></i>
        <p>Goal List</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo base_url ?>admin/?page=goals/create" class="nav-link nav-goals-create">
        <i class="fas fa-plus-circle nav-icon"></i>
        <p>Add Goal</p>
      </a>
    </li>
  </ul>
</li>
      </ul>
    </nav>
  </div>
</aside>

<!-- === PAGE & THEME SCRIPT === -->
<script>
  $(document).ready(function () {
    // Highlight active page
    let page = '<?php echo isset($_GET["page"]) ? $_GET["page"] : "home" ?>';
    let s = '<?php echo isset($_GET["s"]) ? $_GET["s"] : "" ?>';
    if (s !== '') page += '_' + s;
    page = page.split('/')[0];

    let $activeLink = $('.nav-link.nav-' + page);
    if ($activeLink.length > 0) {
      $activeLink.addClass('active');
      if ($activeLink.hasClass('tree-item')) {
        $activeLink.closest('.nav-treeview').siblings('a').addClass('active');
        $activeLink.closest('.nav-treeview').parent().addClass('menu-open');
      }
      if ($activeLink.hasClass('nav-is-tree')) {
        $activeLink.parent().addClass('menu-open');
      }
    }

    // Theme toggle logic
    const savedMode = localStorage.getItem('sidebarMode') || 'dark';
    const $body = $('body');
    const $btn = $('#theme-toggle');

    if (savedMode === 'light') {
      $body.addClass('light-nav contrast-adjusted');
      $btn.text('🌙 Dark Mode');
    }

    $btn.click(function () {
      $body.toggleClass('light-nav contrast-adjusted');
      const isLight = $body.hasClass('light-nav');
      localStorage.setItem('sidebarMode', isLight ? 'light' : 'dark');
      $btn.text(isLight ? '🌙 Dark Mode' : '☀️ Light Mode');
    });
  });
</script>
