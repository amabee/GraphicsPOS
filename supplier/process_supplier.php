<?php
include('../includes/config.php');

function sanitizeInput($input)
{
    // Remove HTML tags and encode special characters
    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $operationType = sanitizeInput($_POST['operationType']);
    $supplierId = sanitizeInput($_POST['supplierId']);
    $supplierName = sanitizeInput($_POST['supplierName']);
    $contactNumber = sanitizeInput($_POST['contactNumber']);
    $supplierAddress = sanitizeInput($_POST['supplierAddress']);
    $supplierEmail = sanitizeInput($_POST['supplierEmail']);

    if ($operationType === 'add') {
        // Insert new supplier record
        $sql = "INSERT INTO tbl_supplier (supp_name, supp_contact, supp_address, supp_email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $supplierName, $contactNumber, $supplierAddress, $supplierEmail);
    } elseif ($operationType === 'edit') {
        // Update existing supplier record
        $sql = "UPDATE tbl_supplier SET supp_name = ?, supp_contact = ?, supp_address = ?, supp_email = ? WHERE supp_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $supplierName, $contactNumber, $supplierAddress, $supplierEmail, $supplierId);
    } else {
        // Invalid operation type
        echo json_encode(array("status" => "error", "message" => "Invalid operation type."));
        exit();
    }

    // Execute the prepared statement
    if ($stmt->execute()) {
        // If successful, return success response
        echo json_encode(array("status" => "success", "message" => "Supplier " . ($operationType === 'add' ? 'added' : 'updated') . " successfully!"));
    } else {
        // If error, return error response
        echo json_encode(array("status" => "error", "message" => "Error: " . $stmt->error));
    }

    $stmt->close();
} else {
    // If request method is not POST, return error response
    echo json_encode(array("status" => "error", "message" => "Invalid request."));
}

$conn->close();
