<?php
    include_once '../../dbConfig.php'; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $orderId = mysqli_real_escape_string($conn, $_POST['orderId']);
        $newStatus = mysqli_real_escape_string($conn, $_POST['newStatus']);
    
        if($newStatus == 'Not Approve'){
            $updateQuery1 = "UPDATE invoice SET approveStatus = '$newStatus' WHERE orderId = '$orderId'";
        }
        else if($newStatus == 'Approve'){
            $updateQuery1 = "UPDATE invoice SET approveStatus = '$newStatus' WHERE orderId = '$orderId'";
        }
    
        mysqli_query($conn, $updateQuery1);
    
        $affectedRows1 = mysqli_affected_rows($conn);
    
        if ($affectedRows1 > 0 || $affectedRows2 > 0) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "error" => "No records updated"));
        }
    } else {
        http_response_code(405);
        echo json_encode(array("error" => "Method Not Allowed"));
    }   
?>
