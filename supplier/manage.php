
<?php include('../includes/header.php');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Suppliers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Suppliers</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-supplier">Add Supplier</button>
                    <div class="modal fade" id="modal-supplier">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Supplier Form</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="supplierForm" action="" method="POST">

                                    <input type="hidden" id="operationType" name="operationType">
                                    <input type="hidden" id="supplierId" name="supplierId">

                                    <div class="form-group">
                                        <label for="supplierName">Supplier Name:</label>
                                        <input type="text" class="form-control" id="supplierName" name="supplierName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contactNumber">Contact Number:</label>
                                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" minlength="11" maxlength="11" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="supplierAddress">Supplier Address:</label>
                                        <input type="text" class="form-control" id="supplierAddress" name="supplierAddress" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="supplierEmail">Supplier Email Address:</label>
                                        <input type="email" class="form-control" id="supplierEmail" name="supplierEmail" required>
                                    </div>
                                    <!-- Add other form fields as needed -->
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Contact Number</th>
                    <th>Address(s)</th>
                    <th>Email Address</th>
                    <th>Status</th>
                    <th class="actions-column">Actions</th> <!-- Added Actions column -->
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                        $sql = "SELECT * FROM tbl_supplier";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              $status = $row['status'];?>
                                <tr>
                                    <td><?php echo $row['supp_name'];?></td>
                                    <td><?php echo $row['supp_contact'];?></td>
                                    <td><?php echo $row['supp_address'];?></td>
                                    <td><?php echo $row['supp_email'];?></td>
                                    <td>
                                      <?php 
                                      if ($status == 1) {
                                        echo '<span class="badge badge-success">Active</span>';
                                      } elseif ($status == 2) {
                                          echo '<span class="badge badge-danger">Deactivated</span>';
                                      }
                                      ?>
                                    </td>
                                    <td class="actions-column"> <!-- Added actions-column class -->
                                    <a href="#" class="btn btn-info btn-sm editSupplier" data-id="<?php echo $row['supp_id']; ?>"><i class="">Edit</i></a>
                                      <?php if ($status == 1) { ?>
                                      <a href="#" class="btn btn-danger btn-sm deactUser" data-id="<?php echo $row['supp_id']; ?>"><i class="fas fa-lock"></i></a>
                                      <?php } elseif ($status == 2) { ?>
                                      <a href="#" class="btn btn-warning btn-sm activateUser" data-id="<?php echo $row['supp_id']; ?>"><i class="fas fa-unlock"></i></a>
                                      <?php } ?>
                                   
                                    </td>
                                </tr>
                    <?php }
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
  </div>
<?php include('../includes/footer_s.php')?>

<!-- Error Modal -->
<div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-error-label">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="error-message"></p>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
    // Event listener for the Edit button
    $('.editSupplier').on('click', function(e) {
        e.preventDefault();

        // Get supplier data from the row
        var suppId = $(this).data('id');
        var suppName = $(this).closest('tr').find('td:eq(0)').text();
        var contactNumber = $(this).closest('tr').find('td:eq(1)').text();
        var suppAddress = $(this).closest('tr').find('td:eq(2)').text();
        var suppEmail = $(this).closest('tr').find('td:eq(3)').text();

        // Populate the form fields with supplier data
        $('#supplierId').val(suppId);
        $('#operationType').val('edit'); // Set operation type to 'edit'
        $('#supplierName').val(suppName);
        $('#contactNumber').val(contactNumber);
        $('#supplierAddress').val(suppAddress);
        $('#supplierEmail').val(suppEmail);

        // Show the modal
        $('#modal-supplier').modal('show');
    });

    // Submit handler for the modal form
    $('#supplierForm').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: 'process_supplier.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === "error") {
                    $('#error-message').text(data.message);
                    $('#modal-error').modal('show');
                } else {
                    console.log(response);
                    $('#modal-supplier').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
// ACTIVATION / DEACTIVATION CODE

    $(document).ready(function() {
        $('.deactUser').on('click', function(e) {
            e.preventDefault();

            var suppId = $(this).data('id');

            if (confirm('Are you sure you want to deactivate this user?')) {
                $.ajax({
                    type: 'POST',
                    url: '../ajax/ajax.php',
                    data: { action: 'deactUser', suppId: suppId },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    });


    $(document).ready(function() {
        $('.activateUser').on('click', function(e) {
            e.preventDefault();

            var suppId = $(this).data('id');

            if (confirm('Are you sure you want to activate this user?')) {
                $.ajax({
                    type: 'POST',
                    url: '../ajax/ajax.php',
                    data: { action: 'activateUser', suppId: suppId },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    });

</script>

<style>
/* Add this CSS to your stylesheet or in a <style> tag in the <head> section */

/* Style for the Actions column */
.actions-column {
  width: 150px; /* Adjust the width as needed */
}

/* Center align text in the Actions column */
.actions-column {
  text-align: center;
}

/* Style for the buttons within the Actions column */
.actions-column .btn {
  margin-right: 5px; /* Add some spacing between buttons */
}
</style>

