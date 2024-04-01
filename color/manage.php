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
                    <h1>Color</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Color</li>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-color">Add Color</button>
                            <div class="modal fade" id="modal-color">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Color Form</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="colorForm" action="" method="POST">

                                                <input type="hidden" id="operationType" name="operationType">
                                                <input type="hidden" id="colorId" name="colorId">

                                                <div class="form-group">
                                                    <label for="supplierName">Color Name:</label>
                                                    <input type="text" class="form-control" id="colorName"
                                                        name="colorName" required>
                                                </div>
                                                <!-- Add other form fields as needed -->
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
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
                                        <th>Color</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tbl_color";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['col_name'];?></td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm editColor"
                                                data-id="<?php echo $row['color_id']; ?>"><i class="">Edit</i></a>
                                        </td>
                                    </tr>
                                    <?php }
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                                <tfoot></tfoot> <!-- Added missing tfoot tag -->
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
<div class="modal fade" id="colorExistModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Error</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The color name already exists!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        // Event listener for the Edit button
        $('.editColor').on('click', function(e) {
            e.preventDefault();

            // Get color data from the row
            var colorId = $(this).data('id');
            var colorName = $(this).closest('tr').find('td:eq(0)').text();

            // Populate the form fields with color data
            $('#colorId').val(colorId);
            $('#operationType').val('edit'); // Set operation type to 'edit'
            $('#colorName').val(colorName);

            // Show the modal
            $('#modal-color').modal('show');
        });

        // Submit handler for the modal form (including edit form)
        $('#colorForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Get form data
            var formData = $(this).serialize();

            // AJAX call to handle form submission
            $.ajax({
                url: 'process_color.php', // Replace with your processing file
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response === 'exists') {
                        $('#colorExistModal').modal('show'); // Display modal if color exists
                    } else {
                        console.log(response);
                        $('#modal-color').modal('hide');
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
