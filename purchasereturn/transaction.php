<?php include('../includes/header.php');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchases</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Purchases</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
          
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-danger">
              <div class="card-header">Purchase Transactions</div>
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Transaction Number</th>
                    <th>Supplier</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th class="actions-column">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php

                    // Fetch and display purchase transactions with names instead of IDs
                    $sql = "SELECT t.trans_id, s.supp_name, c.cat_name, p.prod_name, col.col_name, siz.size_name, t.total_price, t.price, t.quantity, t.status 
                            FROM tbl_purchasetransaction t
                            INNER JOIN tbl_supplier s ON t.supp_id = s.supp_id
                            INNER JOIN tbl_category c ON t.cat_id = c.cat_id
                            INNER JOIN tbl_product p ON t.prod_id = p.prod_id
                            INNER JOIN tbl_color col ON t.color_id = col.color_id
                            INNER JOIN tbl_size siz ON t.size_id = siz.size_id";
                    
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                       while ($row = $result->fetch_assoc()) {
                        $status = $row['status'];?>
                        <tr>
                                    <td class="first-td"><?php echo $row['trans_id'];?></td>
                                    <td class="first-td"><?php echo $row['supp_name'];?></td>
                                    <td class="first-td"><?php echo $row['cat_name'];?></td>
                                    <td class="first-td"><?php echo $row['prod_name'];?></td>
                                    <td class="first-td"><?php echo $row['col_name'];?></td>
                                    <td class="first-td"><?php echo $row['size_name'];?></td>
                                    <td class="first-td"><?php echo $row['price'];?></td>
                                    <td class="first-td"><?php echo $row['quantity'];?></td>
                                    <td class="first-td"><?php echo $row['total_price'];?></td>
                                    <td>
                                      <?php 
                                      if ($status == 1) {
                                        echo '<span class="badge badge-warning">Pending</span>';
                                      } elseif ($status == 2) {
                                          echo '<span class="badge badge-success">Returned</span>';
                                      }
                                      ?>
                                    </td>
                                    <td> <!-- Added actions-column class -->
                                    <!-- <a href="#" class="btn btn-info btn-sm editPurchase" data-id="<?php echo $row['trans_id']; ?>"><i class="fas fa-pen"></i></a>
                                      <a href="#" class="btn btn-success btn-sm receivePurchase" data-id="<?php echo $row['trans_id']; ?>"><i class="fas fa-check"></i></a>
                                      <?php  ?> -->
                                      
                                    <a href="#" class="btn btn-info btn-sm editProduct" data-id="<i class="">Return</i></a>
                                    
                                    </td>
                        </tr>
                            <?php }
                    } else {
                        echo "<tr><td colspan='8'>No transactions found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<?php include('../includes/footer_s.php')?>

<style>
/* Add this CSS to your stylesheet or in a <style> tag in the <head> section */

/* Adjust the table layout */
#example {
  width: 100%; /* Set the table width to 100% */
}

/* Set specific column widths */
#example th,
#example td {
  text-align: center; /* Center align text in all cells */
  padding: 10px; /* Add padding to cells */
}

/* Style for the Actions column */
.actions-column {
  width: 150px; /* Set a fixed width for the Actions column */
}

/* Style for the buttons within the Actions column */
.actions-column .btn {
  margin-right: 5px; /* Add some spacing between buttons */
}
</style>

<script>
    $(document).ready(function(){
        // Handle click event for the "check" button
        $('.receivePurchase').click(function(e){
            e.preventDefault();
            
            // Get the transaction ID from the data-id attribute of the clicked button
            var transactionId = $(this).data('id');
            
            // Send an AJAX request to mark the transaction as received and update stock quantity
            $.ajax({
                url: 'receive_transaction.php', // Replace with the URL of your PHP script
                method: 'POST',
                data: { transaction_id: transactionId },
                success: function(response) {
                    // If the transaction is successfully received, update the UI as needed
                    if(response === 'success') {
                        alert('Transaction received successfully.');
                        // Add code here to update the UI if needed
                    } else {
                        alert('Failed to receive transaction. Please try again.');
                    }
                },
                error: function() {
                    alert('Error: Failed to receive transaction. Please try again.');
                }
            });
        });
        
        // Handle click event for the "edit" button
        $('.editPurchase').click(function(e){
            e.preventDefault();

            // Get the transaction ID from the data-id attribute of the clicked button
            var transactionId = $(this).data('id');
            
            // Redirect to edit page with the transaction ID
            window.location.href = 'add_transaction.php?id=' + transactionId;
        });
    });
</script>
