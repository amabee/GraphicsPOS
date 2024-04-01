<?php
include('../includes/config.php');
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = sanitizeInput(ucwords($_POST['firstName']));
    $lastName = sanitizeInput(ucwords($_POST['lastName']));
    $contactNumber = sanitizeInput($_POST['contactNumber']);
    $emailAddress = sanitizeInput($_POST['emailAddress']);
    $username = sanitizeInput($_POST['username']);
    $designation = sanitizeInput($_POST['designation']);

    // Generating a random password
    $password = $_POST['password'];

    // Hashing the password using md5 (not recommended for secure password hashing)
    $hashedPassword = md5($password);

    $sql = "INSERT INTO `tbl_user`(`fname`, `lname`, `username`, `email`, `contact`, `password`, `usertype`) VALUES ('$firstName', '$lastName', '$username', '$emailAddress', '$contactNumber', '$hashedPassword', '$designation')";

    if ($conn->query($sql) === TRUE) {
        echo "User added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Form data not received properly.";
}

$conn->close();
