<?php
include('../includes/config.php'); // Include your database connection or configuration file

// Query to get the latest transaction number from the database
$sql = "SELECT MAX(sale_id) AS latest_transaction FROM tbl_saletransaction"; // Replace 'your_transaction_table' with your actual table name

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $latestTransactionNumber = $row['latest_transaction'] + 1;

    // Get the current year and month
    $currentYearMonth = date('Ym');
    
    // Concatenate the current year and month with the latest transaction number
    $formattedTransactionNumber = $currentYearMonth . '-' . $latestTransactionNumber;

    // Return the formatted transaction number as a response
    echo $formattedTransactionNumber;
} else {
    echo "0"; // If no transactions exist yet, return 0 or an appropriate default value
}

$conn->close();
?>
