<!-- Sidebar -->
<?php
if($_SESSION['user']['role'] === '1') {
    $dashboardHref = "../admin/dashboard.php";
    $sideBarHeading = "Admin Tables";
    $sideBarSubHeading = "Sub Heading for Admin:";
} elseif($_SESSION['user']['role'] === '2') {
    $dashboardHref = "../student/dashboard.php";
    $sideBarHeading = "Student Tables";
    $sideBarSubHeading = "Sub Heading for Student:";
}
?>
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo $dashboardHref; ?>">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
          <?php
          if($_SESSION['user']['role'] === '1') {
              echo '<div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>';
          } elseif($_SESSION['user']['role'] === '2') {
              echo '<div class="sidebar-brand-text mx-3">'."Welcome\n".$_SESSION['user']['firstname']." ".$_SESSION['user']['lastname'].'</div>';
          }
          ?>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo $dashboardHref; ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item - Tables Collapse Menu -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTables" aria-expanded="true" aria-controls="collapseTables">
                <i class="fas fa-fw fa-table"></i>
                <span><?php echo $sideBarHeading; ?></span>
            </a>
            <div id="collapseTables" class="collapse" aria-labelledby="headingTables" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header"><?php echo $sideBarSubHeading; ?></h6>
                    <?php
                    if($_SESSION['user']['role'] === '1'){
                        echo '<a class="collapse-item" href="../common/requeststable.php">View Requests</a>';
                        echo '<a class="collapse-item" href="../admin/usertables.php">User Tables</a>';
                    } elseif($_SESSION['user']['role'] === '2'){
                        echo '<a class="collapse-item" href="../common/requeststable.php">View My Requests</a>';
                    }
                    ?>
                    <a class="collapse-item" href="../common/bookstables.php">Books Tables</a>
                </div>
            </div>
        </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->
