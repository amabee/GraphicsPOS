<?php
// Include database configuration
include('../includes/db_config.php');

// Fetch and return the latest transaction data
$sql = "SELECT t.trans_id, s.supp_name, c.cat_name, p.prod_name, col.col_name, siz.size_name, t.total_price, t.price, t.quantity, t.status 
        FROM tbl_purchasetransaction t
        INNER JOIN tbl_supplier s ON t.supp_id = s.supp_id
        INNER JOIN tbl_category c ON t.cat_id = c.cat_id
        INNER JOIN tbl_product p ON t.prod_id = p.prod_id
        INNER JOIN tbl_color col ON t.color_id = col.color_id
        INNER JOIN tbl_size siz ON t.size_id = siz.size_id";
$result = $conn->query($sql);
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
echo json_encode($data);
?>
