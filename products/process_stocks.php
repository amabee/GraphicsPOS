<?php
include('../includes/config.php');
function sanitizeInput($input)
{
    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity = sanitizeInput($_POST['stockQuantity']);
    $price = sanitizeInput($_POST['stockPrice']);
    $name = sanitizeInput($_POST['name']);

    // Get current date and time
    $date_time = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `tbl_stocks`(`quantity`, `price`, `prod_id`, `date_created`) VALUES  ('$quantity', '$price', '$name', '$date_time')";

    if ($conn->query($sql) === TRUE) {
        echo "New stock successfully!";
        echo $quantity;
        echo $price;
        echo $name;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Form data not received properly.";
}

$conn->close();
