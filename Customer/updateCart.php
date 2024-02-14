<?php
session_start();
$cx = mysqli_connect("localhost", "root", "", "shopping");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $newQuantity = $_POST['newQuantity'];

    // Update the cart table with the new quantity
   
    $uid = $_SESSION['id_username'];
    // $uid_query = mysqli_query($cx, "SELECT CusID FROM customer WHERE Username = '$user'");
    // $uid_row = mysqli_fetch_assoc($uid_query);
    // $uid_results = $uid_row['CusID'];

    $updateQuery = "UPDATE cart SET Qty = $newQuantity WHERE CusID = $uid  AND ProID = $productId";
    mysqli_query($cx, $updateQuery);

    // You may need to handle errors and send appropriate responses
    echo json_encode(array("success" => true));
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
}
?>