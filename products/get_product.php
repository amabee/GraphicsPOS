<?php
include('../includes/config.php');


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Retrieve product details from the database
    $sql = "SELECT * FROM tbl_product WHERE prod_id = '$productId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(array("status" => "error", "message" => "Product not found."));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid request."));
}

$conn->close();
?>
