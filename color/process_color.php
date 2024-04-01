<?php
include('../includes/config.php');
function sanitizeInput($input)
{
    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data (Remember to sanitize/validate these values)
    $operationType = sanitizeInput($_POST['operationType']);
    $colorId = sanitizeInput($_POST['colorId']);
    $colorName = sanitizeInput($_POST['colorName']);

    if ($operationType === 'add') {
        // Insert new color record
        $sql = "INSERT INTO tbl_color (col_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $colorName); // Bind color name
    } elseif ($operationType === 'edit') {
        // Update existing color record
        $sql = "UPDATE tbl_color SET col_name = ? WHERE color_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $colorName, $colorId); // Bind color name and ID
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
