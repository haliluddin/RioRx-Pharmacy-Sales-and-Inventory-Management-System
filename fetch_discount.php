<?php
include 'db_connect.php';

if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $qry = $conn->query("SELECT discount FROM customer_list WHERE id = $customer_id");
    if ($qry->num_rows > 0) {
        $discount = $qry->fetch_assoc()['discount'];
        echo json_encode(['discount' => $discount]);
    } else {
        echo json_encode(['discount' => 0]);
    }
}
?>
