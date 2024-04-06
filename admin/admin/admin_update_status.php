<?php
    include_once '../../dbConfig.php'; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cusid = mysqli_real_escape_string($conn, $_POST['cusid']);
        $newStatus = mysqli_real_escape_string($conn, $_POST['newStatus']);
    
        if($newStatus == 'super-admin'){
            $updateQuery1 = "UPDATE customer SET authority = '$newStatus' WHERE CusID = '$cusid'";
        }
        else if($newStatus == 'product-admin'){
            $updateQuery1 = "UPDATE customer SET authority = '$newStatus' WHERE CusID = '$cusid'";
        }
        // else if($newStatus == 'permissions-admin'){
        //     $updateQuery1 = "UPDATE customer SET authority = '$newStatus' WHERE CusID = '$cusid'";
        // }
    
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
