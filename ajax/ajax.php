<?php
include('../includes/config.php');

function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deactUser') {
    $suppId = isset($_POST['suppId']) ? intval($_POST['suppId']) : 0;

    $suppId = mysqli_real_escape_string($conn, $suppId);

    $sql = "UPDATE tbl_supplier SET status = 2 WHERE supp_id = '$suppId'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Error deactivating user: ' . mysqli_error($conn)]);
        exit;
    }

    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User deactivated successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User deactivation failed.']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'activateUser') {
    $suppId = isset($_POST['suppId']) ? intval($_POST['suppId']) : 0;

    $suppId = mysqli_real_escape_string($conn, $suppId);

    $sql = "UPDATE tbl_supplier SET status = 1 WHERE supp_id = '$suppId'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Error activating user: ' . mysqli_error($conn)]);
        exit;
    }

    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User activated successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User activation failed.']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteProduct') {
    $prodId = isset($_POST['prodId']) ? intval($_POST['prodId']) : 0;

    $prodId = mysqli_real_escape_string($conn, $prodId);

    $sql = "DELETE FROM tbl_product WHERE prod_id = '$prodId'";
    $result = mysqli_query($conn, $sql);


    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No records deleted']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteStock') {
    $stockId = isset($_POST['stockId']) ? intval($_POST['stockId']) : 0;

    $stockId = mysqli_real_escape_string($conn, $stockId);

    $sql = "DELETE FROM tbl_stocks WHERE id = '$stockId'";
    $result = mysqli_query($conn, $sql);


    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No records deleted']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteCategory') {
    $catId = isset($_POST['catId']) ? intval($_POST['catId']) : 0;

    $catId = mysqli_real_escape_string($conn, $catId);

    $sqlDeleteProducts = "DELETE FROM tbl_product WHERE cat_id = '$catId'";
    $resultDeleteProducts = mysqli_query($conn, $sqlDeleteProducts);

    if (!$resultDeleteProducts) {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting associated products: ' . mysqli_error($conn)]);
        exit;
    }

    $sqlDeleteCategory = "DELETE FROM tbl_category WHERE cat_id = '$catId'";
    $resultDeleteCategory = mysqli_query($conn, $sqlDeleteCategory);

    if ($resultDeleteCategory) {
        echo json_encode(['status' => 'success', 'message' => 'Category and associated products deleted successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting category: ' . mysqli_error($conn)]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deactUser') {
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;

    $userId = mysqli_real_escape_string($conn, $userId);

    $sql = "UPDATE tbl_user SET status = 2 WHERE user_id = '$userId'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Error deactivating user: ' . mysqli_error($conn)]);
        exit;
    }

    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User deactivated successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User deactivation failed.']);
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deactUser') {
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;

    $userId = mysqli_real_escape_string($conn, $userId);

    $sql = "UPDATE tbl_user SET status = 2 WHERE supp_id = '$userId'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Error deactivating user: ' . mysqli_error($conn)]);
        exit;
    }

    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User deactivated successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User deactivation failed.']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'activateUser') {
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;

    $userId = mysqli_real_escape_string($conn, $userId);

    $sql = "UPDATE tbl_user SET status = 1 WHERE supp_id = '$userId'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Error deactivating user: ' . mysqli_error($conn)]);
        exit;
    }

    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User deactivated successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User deactivation failed.']);
        exit;
    }
}
?>
