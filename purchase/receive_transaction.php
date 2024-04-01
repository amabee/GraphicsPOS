<?php
include('../includes/config.php');

// Check if the transaction ID is set and not empty
if(isset($_POST['transaction_id']) && !empty($_POST['transaction_id'])) {
    // Get the transaction ID from POST data and sanitize it
    $transactionId = intval($_POST['transaction_id']);

    // Update transaction status
    $statusUpdateQuery = "UPDATE tbl_purchasetransaction SET status = 2 WHERE trans_id = ?";
    $statusUpdateStmt = $conn->prepare($statusUpdateQuery);
    $statusUpdateStmt->bind_param("i", $transactionId);

    // Update stock quantity
    $updateStockQuery = "UPDATE tbl_stocks SET quantity = quantity + 
        (SELECT quantity FROM tbl_purchasetransaction WHERE trans_id = ?) 
        WHERE prod_id = (SELECT prod_id FROM tbl_purchasetransaction WHERE trans_id = ?)";
    $updateStockStmt = $conn->prepare($updateStockQuery);
    $updateStockStmt->bind_param("ii", $transactionId, $transactionId);

    // Insert new stock record if product doesn't exist
    $insertStockQuery = "INSERT INTO tbl_stocks (prod_id, quantity, price) 
                         SELECT prod_id, quantity, price
                         FROM tbl_purchasetransaction 
                         WHERE trans_id = ? 
                         AND NOT EXISTS (SELECT 1 FROM tbl_stocks WHERE prod_id = (SELECT prod_id FROM tbl_purchasetransaction WHERE trans_id = ?))";
    $insertStockStmt = $conn->prepare($insertStockQuery);
    $insertStockStmt->bind_param("ii", $transactionId, $transactionId);

    // Execute status update
    if ($statusUpdateStmt->execute()) {
        // Execute insert statement
        $insertStockStmt->execute();

        // Execute update statement
        if($updateStockStmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
