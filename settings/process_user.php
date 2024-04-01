<?php
include('../includes/config.php');

function sanitizeInput($input)
{
    // Remove HTML tags and encode special characters
    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $operationType = sanitizeInput($_POST['operationType']);
    $userId = sanitizeInput($_POST['userId']);
    $firstName = sanitizeInput(ucwords($_POST['firstName']));
    $lastName = sanitizeInput(ucwords($_POST['lastName']));
    $contactNumber = sanitizeInput($_POST['contactNumber']);
    $emailAddress = sanitizeInput($_POST['emailAddress']);
    $username = sanitizeInput($_POST['username']);
    $designation = sanitizeInput($_POST['designation']);

    $password = $_POST['password'];
    $hashedPassword = md5($password);

    if ($operationType === "add") {
        $sql = "INSERT INTO `tbl_user`(`fname`, `lname`, `username`, `email`, `contact`, `password`, `usertype`) VALUES (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $firstName, $lastName, $username, $emailAddress, $contactNumber, $hashedPassword, $designation);
        
        // Execute the SQL query for insert
        if ($stmt->execute()) {
            echo "User added successfully!";
        } else {
            echo "Error adding user: " . $stmt->error;
        }
    } else if ($operationType === 'edit') {
        $sql = "UPDATE `tbl_user` SET `fname`=?, `lname`=?, `username`=?, `email`=?, `contact`=?, `usertype`=? WHERE `user_id`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $firstName, $lastName, $username, $emailAddress, $contactNumber, $designation, $userId);
        
        // Execute the SQL query for update
        if ($stmt->execute()) {
            echo "User updated successfully!";
        } else {
            echo "Error updating user: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>