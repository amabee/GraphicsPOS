<?php
session_start();
include('config.php');
include('header_s.php');

if (!isset($_SESSION["user"])) {
  header("Location: ../index.php");
}
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="sidebar">
    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../assets/dist/img/user9-128x128.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo $_SESSION['user']['username']; ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php if (isset($_SESSION['user']) && ($_SESSION['user']['type'] == 1 || $_SESSION['user']['type'] == 2)) { ?>
          <!-- Admin or Manager links -->
          <li class="nav-item"><a href="../dashboard/admin.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a></li>
          <li class="nav-item"><a href="../supplier/manage.php" class="nav-link"><i class="nav-icon fas fa-user-tie"></i>
              <p>Supplier</p>
            </a></li>
          <li class="nav-item">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-tree"></i>
              <p>Product Management<i class="fas fa-angle-left right"></i> </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="../products/manage.php" class="nav-link"><i class="far fa-circle nav-icon"></i>
                  <p>Products</p>
                </a></li>
              <li class="nav-item"><a href="../products/manage_stocks.php" class="nav-link"><i class="far fa-circle nav-icon"></i>
                  <p>Stocks</p>
                </a></li>
              <li class="nav-item"><a href="../color/manage.php" class="nav-link"><i class="far fa-circle nav-icon"></i>
                  <p>Color</p>
                </a></li>
              <li class="nav-item"><a href="../sizes/manage.php" class="nav-link"><i class="far fa-circle nav-icon"></i>
                  <p>Sizes</p>
                </a></li>
            </ul>
          </li>
          <li class="nav-item"><a href="../category/manage.php" class="nav-link"><i class="nav-icon fas fa-layer-group"></i>
              <p>Category</p>
            </a></li>
          <li class="nav-item"><a href="../sales/transaction.php" class="nav-link"><i class="nav-icon fas fa-chart-line"></i>
              <p>Sales Transaction</p>
            </a></li>
          <li class="nav-item"><a href="../salesreturn/salesreturn.php" class="nav-link"><i class="nav-icon fas fa-undo"></i>
              <p>Sales Return</p>
            </a></li>
          <li class="nav-item"><a href="../purchase/transaction.php" class="nav-link"><i class="nav-icon fas fa-shopping-cart"></i>
              <p>Purchase Transaction</p>
            </a></li>
          <li class="nav-item"><a href="../purchasereturn/transaction.php" class="nav-link"><i class="nav-icon fas fa-reply"></i>
              <p>Purchase Return</p>
            </a></li>
          <li class="nav-item"><a href="" class="nav-link"><i class="nav-icon fas fa-file-alt"></i>
              <p>Reports</p>
            </a></li>
        <?php } ?>

        <!-- Common links -->
        <li class="nav-item"><a href="../sales/point-of-sales.php" class="nav-link"><i class="nav-icon fas fa-shopping-cart"></i>
            <p>Cashiering</p>
          </a></li>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['type'] == 1) { ?>
          <!-- System Settings link for Admin -->
          <li class="nav-item"><a href="../settings/manage.php" class="nav-link"><i class="nav-icon fas fa-cogs"></i>
              <p>System Settings</p>
            </a></li>
        <?php } ?>

        <!-- Logout link with confirmation dialog -->
        <li class="nav-item"><a href="../logout.php" onclick="return confirm('Are you sure you want to logout?');" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a></li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>