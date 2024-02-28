<?php
include('../callDatabase/sql_connection.php');
$sqlConnectionInstance = new Sql_connection();
$cx = $sqlConnectionInstance->sql_Connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ใช้ $_POST แทน $_GET เนื่องจากข้อมูลถูกส่งด้วย POST
    $recID = $_POST['recID'];
    $newStatus = $_POST['newStatus'];

    // ป้องกัน SQL Injection ด้วย mysqli_real_escape_string
    $recID = mysqli_real_escape_string($cx, $recID);
    $newStatus = mysqli_real_escape_string($cx, $newStatus);

    if($newStatus !== 'Pending') {
        $updateQuery = "UPDATE receive SET Status = '$newStatus', DeliveryDate = NOW() WHERE RecID = '$recID'";
    }
    else {
        $updateQuery = "UPDATE receive SET Status = '$newStatus', DeliveryDate = NULL WHERE RecID = '$recID'";
    }
    mysqli_query($cx, $updateQuery);

    // คำสั่ง SQL UPDATE อาจให้ผลลัพธ์หลายรายการ, คุณสามารถตรวจสอบว่ามีการแก้ไขข้อมูลจริงๆ ไหม
    $affectedRows = mysqli_affected_rows($cx);

    if ($affectedRows > 0) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "error" => "No records updated"));
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
}
?>
