<?php include('../includes/header.php');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Products</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Products</li>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-product">Add Product</button>
                    <div class="modal fade" id="modal-product">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Product Form</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="productForm" action="" method="POST">

                                    <input type="hidden" id="operationType" name="operationType">
                                    <input type="hidden" id="prodId" name="prodId">

                                    <div class="form-group">
                                        <label for="prod_name">Product Name:</label>
                                        <input type="text" class="form-control" id="productName" name="productName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="prod_description">Product Description:</label>
                                        <input type="text" class="form-control" id="productDescription" name="productDescription" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cat_id">Product Category:</label>
                                        <select class="form-control" id="productCategory" name="productCategory" required>
                                            <option value=""></option>
                                            <?php
                                            // Assuming you have a database connection named $dbConnection
                                            $sql = "SELECT * FROM tbl_category";
                                            $result = mysqli_query($conn, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No categories found</option>';
                                            }
                                            ?>
                                        </select>
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
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                        $sql = "SELECT * FROM tbl_product INNER JOIN tbl_category ON tbl_product.cat_id = tbl_category.cat_id;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['prod_name'];?></td>
                                    <td><?php echo $row['prod_description'];?></td>
                                    <td><?php echo $row['cat_name'];?></td>
                                    <td>
                                    <a href="#" class="btn btn-info btn-sm editProduct" data-id="<?php echo $row['prod_id']; ?>"><i class="fas fa-pen"></i></a>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            echo "0 results";
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
    $('#productForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting traditionally

        // Get form data
        var formData = $(this).serialize();

        // AJAX call to handle form submission
        $.ajax({
            url: 'process_product.php', // Replace with your processing file
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response); // Log the response to see what the server is returning
                var data = JSON.parse(response);
                if (data.status === "error") {
                    $('#error-message').text(data.message);
                    $('#modal-error').modal('show');
                } else {
                    console.log(response);

                    // Close the modal after successful submission
                    $('#modal-product').modal('hide');

                    // Update the table with the new data (example: reload the page)
                    location.reload(); // This will refresh the page to reflect changes
                }
            },
            error: function(xhr, status, error) {
                // Handle errors if the AJAX request fails
                console.error(xhr.responseText);
            }
        });
    });
});

$(document).ready(function() {
    // Event listener for the Edit button
    $('.editProduct').on('click', function(e) {
        e.preventDefault();

        // Get product data from the row
        var prodId = $(this).data('id');
        console.log('Product ID:', prodId);
        var prodName = $(this).closest('tr').find('td:eq(0)').text();
        console.log('Product Name:', prodName);
        var prodDescription = $(this).closest('tr').find('td:eq(1)').text();
        console.log('Product Description:', prodDescription);
        var catId = $(this).closest('tr').find('td:eq(2)').text();
        console.log('Category ID:', catId);

        // Populate the form fields with product data
        $('#prodId').val(prodId);
        $('#operationType').val('edit'); // Set operation type to 'edit'
        $('#productName').val(prodName);
        $('#productDescription').val(prodDescription);
        $('#productCategory').val(catId);

        // Show the modal
        $('#modal-product').modal('show');
    });
    
    // Submit handler for the modal form (including edit form)
    $('#productForm').submit(function(e) {
        e.preventDefault(); // Prevent form submission

        // Get form data
        var formData = $(this).serialize();

        // AJAX call to handle form submission
        $.ajax({
            url: 'process_product.php', // Replace with your processing file
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === "error") {
                    $('#error-message').text(data.message);
                    $('#modal-error').modal('show');
                } else {
                    console.log(response);
                    $('#modal-product').modal('hide');
                    location.reload(); // Refresh page to reflect changes
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>

<style>
/* Add this CSS to your stylesheet or in a <style> tag in the <head> section */

/* Style for the Actions column */
.actions-column {
  width: 100px; /* Adjust the width as needed */
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