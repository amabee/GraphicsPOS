<?php include('../includes/header.php');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sales</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Sales</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-danger">
                        <div class="card-header">Sales Transaction</div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Transaction Number</th>
                                        <th>Product Name</th>
                                        <th>Number of Items</th>
                                        <th>Total Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT transnumber, tbl_product.prod_name, SUM(total_item) AS total_quantity, SUM(total_price) AS total_amount
                                            FROM tbl_saletransaction
                                            INNER JOIN tbl_product ON tbl_saletransaction.prod_id = tbl_product.prod_id
                                            GROUP BY transnumber";

                                    $result = $conn->query($sql);

                                    if ($result === false) {
                                        echo "<tr><td colspan='5'>Error: " . $conn->error . "</td></tr>";
                                    } elseif ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['transnumber'];?></td>
                                                <td><?php echo $row['prod_name'];?></td>
                                                <td><?php echo $row['total_quantity'];?></td>
                                                <td><?php echo '₱' . number_format($row['total_amount'], 2);?></td>
                                                <td>
                                                    <a href="#" class="btn btn-info btn-sm">Return</a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else {
                                        echo "<tr><td colspan='5'>0 results</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
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
<script>
    $(document).ready(function() {
        $('#stocksForm').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting traditionally

            // Get form data
            var formData = $(this).serialize();

            // AJAX call to handle form submission
            $.ajax({
                url: 'process_stocks.php', // Replace with your processing file
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle the success response here (if needed)
                    console.log(response);

                    // Close the modal after successful submission
                    $('#modal-stocks').modal('hide');

                    // Update the table with the new data (example: reload the page)
                    location.reload(); // This will refresh the page to reflect changes
                },
                error: function(xhr, status, error) {
                    // Handle errors if the AJAX request fails
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
