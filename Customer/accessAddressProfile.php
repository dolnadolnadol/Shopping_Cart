<?php
include('./component/session.php');

include_once '../dbConfig.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id_customer']) && isset($_POST['addrId'])) {
        $uid = $_POST['delete_id_customer'];
        $addrId = $_POST['addrId'];

        $delete_result_detail_head = mysqli_query($conn, "DELETE FROM address WHERE AddrId = '$addrId'");

        header("Location: ./profile.php");
        exit();
    } 
    else {
        $recv_id = $_POST['id_receiver'];
        $uid = $_POST['id_customer'];

        $get_cus = mysqli_query($conn, "select * FROM customer WHERE CusID = '$uid'");
        $get_cusone = mysqli_fetch_assoc($get_cus);
        $fname = $get_cusone['fname'];
        $lname = $get_cusone['lname'];
        $tel = $get_cusone['Tel'];

        if ($recv_id === null || $recv_id === '' && !isset($_POST['delete_id_customer'])) {
            // $uid = $_POST['id_customer'];
            $province = $_POST['province'];
            $city = $_POST['city'];
            $postalCode = $_POST['postal'];
            $new_address = $_POST['addr'];

            $insert_query_head = "INSERT INTO address (Address ,fname,lname,tel, Province  , City , PostalCode, CusId) 
                VALUES('$new_address', '$fname', '$lname', '$tel','$province', '$city' , '$postalCode' , '$uid')";
            $insert_result_head = mysqli_query($conn, $insert_query_head);

            if ($insert_result_head) {
                $recv_id = mysqli_insert_id($conn);
            } else {
                die("Error inserting into receiver: " . mysqli_error($conn));
            }

            // Generate new NumID
            // $resultDetail = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(NumID, 4) AS UNSIGNED)) AS num_id FROM receiver_detail WHERE CusID = '$uid'");
            // $latestID = mysqli_fetch_assoc($resultDetail);
            // $lastID = $latestID['num_id'];

            // // Increment the numeric part
            // $newNumericPart = $lastID + 1;

            // // Format the complete NumID
            // $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            // echo $NumID;
            // echo $recv_id;

            // $insert_query_detail = "INSERT INTO receiver_detail (CusID, RecvID, NumID) VALUES('$uid', '$recv_id', '$NumID')";
            // $insert_result_detail = mysqli_query($conn, $insert_query_detail);

            // if (!$insert_result_detail) {
            //     die("Error inserting receiver_detail: " . mysqli_error($conn));
            // }

            header("Location: ./profile.php");
        }
    }
}

 
?>
