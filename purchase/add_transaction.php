<?php
include('../includes/header.php');
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

$transaction_id = "";
$supplier_id = "";
$category_id = "";
$product_id = "";
$color_id = "";
$size_id = "";
$price = "";
$quantity = "";
$total_price = 0;

// Check if transaction ID is provided for editing
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Fetch transaction details from the database
    $sql = "SELECT * FROM tbl_purchasetransaction WHERE trans_id = $transaction_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        // Set form fields with transaction details for editing
        $supplier_id = $transaction['supp_id'];
        $category_id = $transaction['cat_id'];
        $product_id = $transaction['prod_id'];
        $color_id = $transaction['color_id'];
        $size_id = $transaction['size_id'];
        $price = $transaction['price'];
        $quantity = $transaction['quantity'];
    } else {
        echo "Transaction not found.";
        exit(); // Stop further execution
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission for both adding and editing transactions
    // Add your validation and processing logic here

    // Ensure $price and $quantity are numeric before calculating total price
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    // Calculate total price
    $total_price = $price * $quantity;
    // Use $total_price in further processing

    // Update the transaction in the database using prepared statements
    $update_sql = "UPDATE tbl_purchasetransaction SET 
    supp_id = ?,
    cat_id = ?,
    prod_id = ?,
    color_id = ?,
    size_id = ?,
    price = ?,
    quantity = ?,
    total_price = ?
    WHERE trans_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($update_sql);

    // Bind parameters
    $stmt->bind_param("iiiiididi", $supplier_id, $category_id, $product_id, $color_id, $size_id, $price, $quantity, $total_price, $transaction_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Transaction updated successfully";
    } else {
        echo "Error updating transaction: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $transaction_id ? 'Edit' : 'Add'; ?> Purchase Transaction</h1>
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
                    <div class="card card-outline card-danger">
                        <div class="card-header"><?php echo $transaction_id ? 'Edit' : 'Add'; ?> Purchase Transaction</div>
                        <div class="card-body">
                            <form action="process_transaction.php" method="POST">
                                <!-- Add hidden input field to differentiate between add and edit operations -->
                                <input type="hidden" name="operation" value="<?php echo $transaction_id ? 'edit' : 'add'; ?>">
                                <input type="hidden" id="transactionId" name="transactionId" value="<?php echo $transaction_id; ?>">

                                <!-- Example: -->
                                <div class="form-group">
                                    <label for="supplier">Supplier:</label>
                                    <!-- Populate supplier dropdown with the selected supplier pre-selected -->
                                    <select class="form-control" id="supplier" name="supplier" required>
                                        <option value="">Select a supplier</option>
                                        <?php
                                        $sql = "SELECT * FROM tbl_supplier";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['supp_id'] == $supplier_id) ? "selected" : "";
                                                echo "<option value='" . $row['supp_id'] . "' $selected>" . $row['supp_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category:</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Select a category</option>
                                        <?php
                                        // Fetch categories from database and populate dropdown options
                                        $sql = "SELECT * FROM tbl_category";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['cat_id'] == $category_id) ? "selected" : "";
                                                echo "<option value='" . $row['cat_id'] . "' $selected>" . $row['cat_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="product">Product:</label>
                                    <select class="form-control" id="product" name="product" required>
                                        <option value="">Select a product</option>
                                        <?php
                                        // Fetch categories from database and populate dropdown options
                                        $sql = "SELECT * FROM tbl_product";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['prod_id'] == $product_id) ? "selected" : "";
                                                echo "<option value='" . $row['prod_id'] . "' $selected>" . $row['prod_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="color">Color:</label>
                                    <select class="form-control" id="color" name="color" required>
                                        <option value="">Select a color</option>
                                        <?php
                                        // Fetch categories from database and populate dropdown options
                                        $sql = "SELECT * FROM tbl_color";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['color_id'] == $color_id) ? "selected" : "";
                                                echo "<option value='" . $row['color_id'] . "' $selected>" . $row['col_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="size">Size:</label>
                                    <select class="form-control" id="size" name="size" required>
                                        <option value="">Select a size</option>
                                        <?php
                                        // Fetch categories from database and populate dropdown options
                                        $sql = "SELECT * FROM tbl_size";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['size_id'] == $size_id) ? "selected" : "";
                                                echo "<option value='" . $row['size_id'] . "' $selected>" . $row['size_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price:</label>
                                    <input type="text" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Quantity:</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo $transaction_id ? 'Update' : 'Submit'; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include('../includes/footer_s.php'); ?>