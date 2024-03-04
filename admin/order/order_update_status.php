<?php
include('../callDatabase/sql_connection.php');
$sqlConnectionInstance = new Sql_connection();
$cx = $sqlConnectionInstance->sql_Connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recID = $_POST['recID'];
    $newStatus = $_POST['newStatus'];

    $recID = mysqli_real_escape_string($cx, $recID);
    $newStatus = mysqli_real_escape_string($cx, $newStatus);

    if($newStatus == 'Delivered'){
        $updateQuery = "UPDATE receive SET Status = '$newStatus', DeliveryDate = NOW() WHERE RecID = '$recID'";
    }
    else {
        $updateQuery = "UPDATE receive SET Status = '$newStatus', DeliveryDate = NULL WHERE RecID = '$recID'";
    }
    mysqli_query($cx, $updateQuery);

    $affectedRows = mysqli_affected_rows($cx);

    if ($affectedRows > 0) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "error" => "No records updated"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
}
?>
