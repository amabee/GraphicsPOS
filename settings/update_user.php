<?php
include('../includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $operationType === "edit") {
    // Retrieve form data
    $operationType = sanitizeInput($_POST['operationType']);
    $userId = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $designation = $_POST['designation'];

    // Update user information in the database
    $sql = "UPDATE tbl_user SET fname = ?, lname = ?, username = ?, email = ?, contact = ?, usertype = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $firstName, $lastName, $username, $email, $contactNumber, $designation, $userId);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // If successful, return success response
        echo json_encode(array("status" => "success", "message" => "User updated successfully!"));
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
