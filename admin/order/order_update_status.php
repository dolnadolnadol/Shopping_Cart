<?php
    include_once '../../dbConfig.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recID = $_POST['recID'];
    $newStatus = $_POST['newStatus'];

    $recID = mysqli_real_escape_string($conn, $recID);
    $newStatus = mysqli_real_escape_string($conn, $newStatus);

    if($newStatus == 'Delivered'){
        $updateQuery = "UPDATE receive SET Status = '$newStatus', DeliveryDate = NOW() WHERE RecID = '$recID'";
    }
    else {
        $updateQuery = "UPDATE receive SET Status = '$newStatus', DeliveryDate = NULL WHERE RecID = '$recID'";
    }
    mysqli_query($conn, $updateQuery);

    $affectedRows = mysqli_affected_rows($conn);

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
