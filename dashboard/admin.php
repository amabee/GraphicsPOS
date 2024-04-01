<?php 
$title = "Dashboard";
include('../includes/header.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  
  <!-- /.content -->
  <!DOCTYPE html>
  <html>
  <head>
    <title>Dashboard</title>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      .card {
        height: 250px; /* Set a fixed height for the cards */
      }
    </style>
  </head>
  <body>
    <!-- Analytics Section -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Analytics Section -->
        <div class="row">
          <!-- Supplier -->
          <div class="col-lg-4">
            <div class="card bg-success">
              <div class="card-body">
                <h3 class="text-center text-white">Total Supplier</h3>
                <div class="text-center">
                  <?php
                  include('../includes/config.php');

                  // Fetch total number of suppliers
                  $sql = "SELECT COUNT(*) AS totalSuppliers FROM tbl_supplier";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $totalSuppliers = $row['totalSuppliers'];
                    echo '<h1 class="text-white">' . $totalSuppliers . '</h1>'; // Display total suppliers
                  } else {
                    echo '<h1 class="text-white">0</h1>'; // Display 0 if no suppliers found
                  }

                  // Close database connection
                  $conn->close();
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!-- Products -->
          <div class="col-lg-4">
            <div class="card bg-warning">
              <div class="card-body">
                <h3 class="text-center text-white">Total Products</h3>
                <div class="text-center">
                  <?php
                  include('../includes/config.php');

                  // Fetch total number of products
                  $sql = "SELECT COUNT(*) AS totalProducts FROM tbl_product";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $totalProducts = $row['totalProducts'];
                    echo '<h1 class="text-white">' . $totalProducts . '</h1>'; // Display total products
                  } else {
                    echo '<h1 class="text-white">0</h1>'; // Display 0 if no products found
                  }

                  // Close database connection
                  $conn->close();
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!-- Total Amount Sales -->
          <div class="col-lg-4">
            <div class="card bg-danger">
              <div class="card-body">
                <h3 class="text-center text-white">Total Amount Sales</h3>
                <div class="text-center">
                  <?php
                  include('../includes/config.php');

                  // Fetch total amount of sales
                  $sql = "SELECT SUM(total_price) AS totalAmountSales FROM tbl_saletransaction";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $totalAmountSales = $row['totalAmountSales'];
                    echo '<h1 class="text-white">' . $totalAmountSales . '</h1>'; // Display total amount sales
                  } else {
                    echo '<h1 class="text-white">0</h1>'; // Display 0 if no sales found
                  }

                  // Close database connection
                  $conn->close();
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Graphs Section -->
        <div class="container mt-4">
          <div class="row">
            <div class="col-lg-4">
              <!-- Daily Sales Graph -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Daily Sales
                  </h3>
                </div>
                <div class="card-body">
                  <!-- Line graph canvas -->
                  <canvas id="dailySalesChart" width="400" height="300"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <!-- Monthly Sales Graph -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Monthly Sales
                  </h3>
                </div>
                <div class="card-body">
                  <!-- Line graph canvas -->
                  <canvas id="monthlySalesChart" width="400" height="300"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <!-- Annual Sales Graph -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Annual Sales
                  </h3>
                </div>
                <div class="card-body">
                  <!-- Line graph canvas -->
                  <canvas id="annualSalesChart" width="400" height="300"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <script>
          // Sample data for line graphs
          var dailySalesData = {
            labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            datasets: [{
              label: 'Daily Sales',
              data: [12, 19, 3, 5, 2, 3, 9],
              fill: false,
              borderColor: 'rgb(75, 192, 192)',
              tension: 0.1
            }]
          };

          var monthlySalesData = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
              label: 'Monthly Sales',
              data: [65, 59, 80, 81, 56, 55, 40, 20, 25, 30, 45, 70],
              fill: false,
              borderColor: 'rgb(255, 99, 132)',
              tension: 0.1
            }]
          };

          var annualSalesData = {
            labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
            datasets: [{
              label: 'Annual Sales',
              data: [3, 5, 2, 7],
              fill: false,
              borderColor: 'rgb(54, 162, 235)',
              tension: 0.1
            }]
          };

          // Render line graphs
          var dailySalesChart = new Chart(document.getElementById('dailySalesChart'), {
            type: 'line',
            data: dailySalesData,
          });

          var monthlySalesChart = new Chart(document.getElementById('monthlySalesChart'), {
            type: 'line',
            data: monthlySalesData,
          });

          var annualSalesChart = new Chart(document.getElementById('annualSalesChart'), {
            type: 'line',
            data: annualSalesData,
          });
        </script>
      </div>
    </section>
  </body>
  </html>

</div>
<!-- ./wrapper -->

<?php include('../includes/footer_s.php'); ?>
