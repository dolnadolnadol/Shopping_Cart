<?php
    include_once '../../dbConfig.php'; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $orderId = mysqli_real_escape_string($conn, $_POST['orderId']);
        $deliId = mysqli_real_escape_string($conn, $_POST['deliId']);
        $newStatus = mysqli_real_escape_string($conn, $_POST['newStatus']);

        $cur = "SELECT * FROM orderkey 
        WHERE orderkey.orderId = '$orderId'";
        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
    
        if($newStatus == 'Pending'){
            $updateQuery1 = "UPDATE orderkey SET PaymentStatus = '$newStatus' WHERE orderId = '$orderId'";
            $updateQuery2 = "UPDATE orderdelivery SET statusDeli = 'Prepare', DeliDate = null WHERE DeliId = '$deliId'";
        }
        else if($newStatus == 'Success'){
            $updateQuery1 = "UPDATE orderkey SET PaymentStatus = '$newStatus' WHERE orderId = '$orderId'";
            $updateQuery2 = "UPDATE orderdelivery SET statusDeli = 'Inprogress', DeliDate = null WHERE DeliId = '$deliId'";
            mysqli_query($conn, "INSERT INTO invoice (timestamp, cusId, orderId, DeliId) 
            VALUES (NOW(), '{$row['cusId']}', '$orderId', '$deliId')");
        }
        else if($newStatus == 'Inprogress'){
            $updateQuery1 = "UPDATE orderkey SET PaymentStatus = 'Success' WHERE orderId = '$orderId'";
            $updateQuery2 = "UPDATE orderdelivery SET statusDeli = '$newStatus', DeliDate = null WHERE DeliId = '$deliId'";
        }
        else if($newStatus == 'Delivered'){
            $updateQuery1 = "UPDATE orderkey SET PaymentStatus = 'Success' WHERE orderId = '$orderId'";
            $updateQuery2 = "UPDATE orderdelivery SET statusDeli = '$newStatus', DeliDate = NOW() WHERE DeliId = '$deliId'";
        }
        else if($newStatus == 'Prepare'){
            $updateQuery1 = "UPDATE orderkey SET PaymentStatus = 'Pending' WHERE orderId = '$orderId'";
            $updateQuery2 = "UPDATE orderdelivery SET statusDeli = '$newStatus', DeliDate = null WHERE DeliId = '$deliId'";
        }
    
        mysqli_query($conn, $updateQuery1);
        mysqli_query($conn, $updateQuery2);
    
        $affectedRows1 = mysqli_affected_rows($conn);
        $affectedRows2 = mysqli_affected_rows($conn);
    
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
