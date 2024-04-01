<?php
// Include the database connection file
include('../includes/config.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $supplier = $_POST['supplier'];
    $category = $_POST['category'];
    $product = $_POST['product'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $totalprice = $price * $quantity;

    // Check if it's an edit operation
    if (isset($_POST['operation']) && $_POST['operation'] === 'edit') {
        // Retrieve transaction ID for updating
        $transaction_id = $_POST['transactionId'];
        
        // Update purchase transaction in tbl_purchasetransaction
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
        $stmt->bind_param("iiiiididi", $supplier, $category, $product, $color, $size, $price, $quantity, $totalprice, $transaction_id);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: transaction.php");
            exit();
        } else {
            // Handle errors
            echo "Error updating record: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else { // It's an add operation
        // Insert purchase transaction into tbl_purchasetransaction
        $insert_sql = "INSERT INTO tbl_purchasetransaction (supp_id, cat_id, prod_id, color_id, size_id, price, quantity, total_price) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare the statement
        $stmt = $conn->prepare($insert_sql);

        // Bind parameters
        $stmt->bind_param("iiiiidid", $supplier, $category, $product, $color, $size, $price, $quantity, $totalprice);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: transaction.php");
            exit();
        } else {
            // Handle errors
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close database connection
$conn->close(); 
?>
