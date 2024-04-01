<?php
include('../includes/config.php');
function sanitizeInput($input)
{
    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = sanitizeInput(ucwords($_POST['categoryName']));

    // Check if category name already exists
    $check_sql = "SELECT * FROM tbl_category WHERE cat_name = '$categoryName'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo "exists"; // Return 'exists' if category name already exists
    } else {
        // If category name doesn't exist, proceed with insertion
        $insert_sql = "INSERT INTO tbl_category (cat_name) VALUES ('$categoryName')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "Category added successfully!";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Form data not received properly.";
}

$conn->close();
