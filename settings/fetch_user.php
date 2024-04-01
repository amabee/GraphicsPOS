<?php
include('../includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Fetch the user information based on the userId
    $sql = "SELECT * FROM tbl_user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $responseData = array(
            'fname' => $row['fname'],
            'lname' => $row['lname'],
            'username' => $row['username'],
            'email' => $row['email'],
            'contact' => $row['contact'],
            'usertype' => $row['usertype'],
            'status' => $row['status'],
            'date_created' => $row['date_created']
        );

        echo json_encode($responseData);
    } else {
        echo json_encode(array());
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Invalid request"));
}

$conn->close();
?>
