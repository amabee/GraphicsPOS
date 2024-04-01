<?php
include('../includes/config.php');

function sanitizeInput($input)
{
    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $operationType = sanitizeInput($_POST['operationType']);
    $prodId = sanitizeInput($_POST['prodId']);
    $prodName = sanitizeInput($_POST['productName']);
    $prodDescription = sanitizeInput($_POST['productDescription']); // Corrected variable name
    $catId = sanitizeInput($_POST['productCategory']); // Corrected variable name

    if ($operationType === 'add') {
        // Insert new product record
        $sql = "INSERT INTO tbl_product (prod_name, prod_description, cat_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $prodName, $prodDescription, $catId); // Corrected data types
    } elseif ($operationType === 'edit') {
        // Update existing product record
        $sql = "UPDATE tbl_product SET prod_name = ?, prod_description = ?, cat_id = ? WHERE prod_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $prodName, $prodDescription, $catId, $prodId); // Corrected data types and added prodId
    } else {
        // Invalid operation type
        echo json_encode(array("status" => "error", "message" => "Invalid operation type."));
        exit();
    }

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Operation successful
        echo json_encode(array("status" => "success"));
    } else {
        // Error occurred
        echo json_encode(array("status" => "error", "message" => "Failed to process the request."));
    }
    
    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Request method is not POST
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}

?>
