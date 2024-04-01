<?php 
include('../includes/header.php');

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sizes</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sizes</li>
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-sizes">Add Sizes</button>
                    <div class="modal fade" id="modal-sizes">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Sizes Form</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="sizesForm" action="" method="POST">
                                    <div class="form-group">
                                        <label for="supplierName">Sizes Name:</label>
                                        <input type="text" class="form-control" id="sizesName" name="sizesName" required>
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
                    <th>Sizes</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                        $sql = "SELECT * FROM tbl_size";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['size_name'];?></td>
                                    <td>
                                    <a href="#" class="btn btn-info btn-sm editSize"><i class="">Edit</i></a>
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
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php include('../includes/footer_s.php')?>
<div class="modal fade" id="sizesExistModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Error</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The sizes name already exists!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
  $(document).ready(function() {
        $('#sizesForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'process_sizes.php', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response === 'exists') {
                        $('#sizesExistModal').modal('show'); // Display modal if sizes exists
                    } else {
                        console.log(response);
                        $('#modal-sizes').modal('hide');
                        location.reload(); 
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

