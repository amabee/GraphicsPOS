<?php
include('../includes/config.php');
function sanitizeInput($input)
{
    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['selectedProducts']) && isset($data['transactionNumber'])) {
    $transactionNumber = $conn->real_escape_string($data['transactionNumber']);

    // Loop through selected products and insert into database
    foreach ($data['selectedProducts'] as $product) {
        $prod_id = sanitizeInput($conn->real_escape_string($product['prod_id']));
        $total_item = sanitizeInput($conn->real_escape_string($product['quantity']));

        // Prepare and execute SQL statement to update stocks
        $updateStocksSql = "UPDATE tbl_stocks SET quantity = quantity - '$total_item' WHERE prod_id = '$prod_id'";
        if ($conn->query($updateStocksSql) !== TRUE) {
            echo "Error updating stocks: " . $updateStocksSql . "<br>" . $conn->error;
        }

        // Calculate total price based on quantity and price
        $price = floatval($product['price']); // Convert to float for calculations
        $total_price = $price * $total_item; // Calculate total price

        // Prepare and execute SQL statement to insert sale transaction
        $insertTransactionSql = "INSERT INTO tbl_saletransaction (transnumber, prod_id, total_price, total_item)
            VALUES ('$transactionNumber', '$prod_id','$total_price', '$total_item')";

        if ($conn->query($insertTransactionSql) !== TRUE) {
            echo "Error inserting transaction: " . $insertTransactionSql . "<br>" . $conn->error;
        }
    }

    // Close connection
    $conn->close();
} else {
    echo "No data received";
}
