<?php
include('../includes/config.php');
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sizesName = sanitizeInput(ucwords($_POST['sizesName']));

    // Check if sizes name already exists
    $check_sql = "SELECT * FROM tbl_size WHERE size_name = '$sizesName'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo "exists"; // Return 'exists' if sizes name already exists
    } else {
        // If sizes name doesn't exist, proceed with insertion
        $insert_sql = "INSERT INTO tbl_size (size_name) VALUES ('$sizesName')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "Sizes added successfully!";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Form data not received properly.";
}

$conn->close();
