<?php
include('../includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supplierId'])) {
    $supplierId = $_POST['supplierId'];

    // Fetch the supplier information based on the supplierId
    $sql = "SELECT * FROM tbl_supplier WHERE supp_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Prepare the data to send back as JSON
        $responseData = array(
            'name' => $row['supp_name'],
            'contact' => $row['supp_contact'],
            'address' => $row['supp_address'],
            'email' => $row['supp_email']
        );

        // Encode the data as JSON and send it back
        echo json_encode($responseData);
    } else {
        // If supplier not found, return an empty response
        echo json_encode(array());
    }

    $stmt->close();
} else {
    // If the request is not POST or if supplierId is not set, return an error response
    echo json_encode(array("error" => "Invalid request"));
}

$conn->close();
?>
