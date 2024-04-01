<?php
include('../includes/config.php');
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
$transactionNumber = sanitizeInput($_POST['transaction_number']);

// Update the stock in the database (adjust this according to your database structure)
$sql = "UPDATE tbl_product 
        SET stock_quantity = stock_quantity + (
            SELECT total_item FROM tbl_saletransaction 
            WHERE transnumber = '$transactionNumber'
        )
        WHERE prod_id IN (
            SELECT prod_id FROM tbl_saletransaction 
            WHERE transnumber = '$transactionNumber'
        )";

if ($conn->query($sql) === TRUE) {
    echo "Stock returned successfully!";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
