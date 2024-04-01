<?php include('../includes/header.php');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stocks</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Stocks</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <!-- Remove the modal button -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Date</th>
                 
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql = "SELECT * FROM tbl_product INNER JOIN tbl_stocks ON tbl_product.prod_id = tbl_stocks.prod_id;";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              // Check if quantity is below 30
                              $quantity = $row['quantity'];
                              $rowClass = ($quantity < 30) ? 'table-danger' : ''; // Set class to 'table-danger' if quantity is below 30
                              
                              ?>
                              <tr class="<?php echo $rowClass; ?>">
                                  <td><?php echo $row['prod_name'];?></td>
                                  <td><?php echo $row['quantity'];?></td>
                                  <td><?php echo '₱' . number_format($row['price'], 2);?></td>
                                  <td><?php echo $row['date_created'];?></td>
                                 
                              </tr>
                              <?php 
                          } 
                      }
                      $conn->close();
                    ?>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include('../includes/footer_s.php')?>
