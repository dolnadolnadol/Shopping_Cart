<?php
include('./component/session.php');
include_once '../dbConfig.php';
// include('../logFolder/AccessLog.php');
// include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Manage Order to send */
    if (isset($_POST['id_order']) && isset($_POST['id_customer'])) {
        $cusID = $_POST['id_customer'];
        $orderId = $_POST['id_order'];
        $deli = $_POST['id_deli'];

        $payer_fname = $_POST['fname'];
        $payer_lname = $_POST['lname'];
        $payer_time = $_POST['time'];
        $slip = file_get_contents($_FILES["slip"]["tmp_name"]);

        $stmt = $conn->prepare("INSERT INTO receipt(cusId, orderId, fname, lname, time, slip)  VALUES(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $cusID, $orderId, $payer_fname, $payer_lname, $payer_time, $slip);
        $success = $stmt->execute();
        $lastID = mysqli_insert_id($conn);

        if (!$success) {
            die("Error inserting into payer: " . mysqli_error($conn));
        } else {
            if (isset($_POST['invoice-taxid']) && $_POST['invoice-taxid'] != '') {
                $taxid = $_POST['invoice-taxid'];
                $taxname = $_POST['invoice-name'];
                $taxaddr = $_POST['invoice-address'];

                $insert_invoice = "INSERT INTO invoice(cusId, orderId, DeliId, taxId, name, address)
                                    VALUES('$cusID', '$orderId', '$deli', '$taxid', '$taxname', '$taxaddr')";
                $insert_invoice_result = mysqli_query($conn, $insert_invoice);
                $invId = mysqli_insert_id($conn);
            }

            $statusde = "inprogress";
            $stmt = $conn->prepare("update orderdelivery set statusDeli = ? , trackid = ? where DeliId = ?");
            $stmt->bind_param("ssi", $statusde, $track, $deli);
            $success = $stmt->execute();
            if ($success) {
                echo "<form id='auto_submit_form' method='post' action='bill.php'>
                <input type='hidden' name='id_order' value='$orderId'>
                <input type='hidden' name='id_deli' value='$deli'>
                <input type='hidden' name='id_inv' value='$invId'>
            </form>";

                // Use JavaScript to trigger form submission
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('auto_submit_form').submit();
                    });
                    </script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }



        // $check_query = mysqli_query($conn, "SELECT orderId , CusID , TotalPrice FROM invoice WHERE CusID = '$cusID' AND orderId = '$orderId'");
        // $row = mysqli_fetch_assoc($check_query);
        // $totalPrice = $row['TotalPrice'];

        // if(mysqli_num_rows($check_query) > 0){
        //     mysqli_data_seek($check_query, 0);

        //     // Generate new RECEIVE ID
        //     $result = mysqli_query($conn, "SELECT MAX(RecID) AS rec_id FROM receive");
        //     $row = mysqli_fetch_assoc($result);
        //     $lastID = $row['rec_id'];
        //     $numericPart = intval(substr($lastID, 6));
        //     $newNumericPart = $numericPart + 1;
        //     $RecID = 'rec_id'.str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        //     echo $RecID;

        //     // Insert RECEIVE record
        //     $stmt = mysqli_query($conn, "INSERT INTO receive(RecID, OrderDate , TaxID , RecvID ,CusID, TotalPrice , Status)
        //         VALUES ('$RecID', NOW() , '$TaxID' , '$recvID' ,'$cusID','$totalPrice','Pending');");

        //     // ACCESS LOG
        //     $uid = $cusID;
        //     $productId = "";
        //     if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //         $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        //     } else {
        //         $ipAddress = $_SERVER['REMOTE_ADDR'];
        //     }
        //     $callingFile = __FILE__;
        //     $action = 'INSERT'; // Static Change Action
        //     CallLog::callLog($ipAddress, $conn, $uid, $productId, $callingFile, $action);
        //     //END LOG

        //     while (true) {
        //         // Generate new NumID
        //         $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'");
        //         $latestID = mysqli_fetch_assoc($resultDetail);
        //         $lastID = $latestID['num_id'];
        //         $numericPart = intval(substr($lastID, 3));
        //         $newNumericPart = $numericPart + 1; 
        //         $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        //         echo $NumID;

        //         $resultDetail = mysqli_query($conn, "SELECT orderId , proId , Qty FROM invoice_detail WHERE orderId = '$orderId' AND NumID = '$NumID'");

        //         echo mysqli_num_rows($resultDetail);

        //         if ($resultDetail && mysqli_num_rows($resultDetail) > 0) {
        //             $invoice_detail = mysqli_fetch_assoc($resultDetail);
        //             $proId = $invoice_detail['proId'];
        //             $Qty = $invoice_detail['Qty'];
        //             $orderId = $invoice_detail['orderId'];

        //             // Insert invoice_detail record
        //             $stmt = mysqli_query($conn, "INSERT INTO receive_detail (RecID, NumID, proId, Qty) VALUES ('$RecID', '$NumID', '$proId', '$Qty')");

        //             // Update Status
        //             $stmt = mysqli_query($conn, "UPDATE invoice SET Status = 'Paid' WHERE orderId ='$orderId'");

        //             $stmt2 = mysqli_query($conn,"SELECT Qty from product where proId = '$proId'");
        //             $QtyRow = mysqli_fetch_assoc($stmt2);
        //             $Qty = $QtyRow['Qty'];

        //             // Update Stock and OnHand
        //             if(isset($_SESSION['guest']) && ($Qty-$Qty) >= 0 ){
        //                 $stmt = mysqli_query($conn, "UPDATE product SET Qty = Qty - '$Qty', OnHand = OnHand WHERE proId ='$proId'");
        //             } else if(($Qty-$Qty) >= 0) {
        //                 $stmt = mysqli_query($conn, "UPDATE product SET Qty = Qty - '$Qty', OnHand = OnHand - '$Qty' WHERE proId ='$proId'");
        //             }
        //         } else {
        //             // No more matching records found, break the loop
        //             break;
        //         }        
        //     }
        //     $TotalWithTax = $totalPrice * 1.07;


        //     /*-------------------------------------*/
        //     $stmt = mysqli_query($conn, "UPDATE receive SET TotalPrice ='$TotalWithTax'
        //     WHERE RecID ='$RecID'");
        //     /*-------------------------------------*/

        //     // Form submission with hidden values
        //     echo "<form id='auto_submit_form' method='post' action='bill.php'>
        //     <input type='hidden' name='id_order' value='$RecID'>
        //     </form>";

        //     // Use JavaScript to trigger form submission
        //     echo "<script>
        //     document.addEventListener('DOMContentLoaded', function() {
        //         document.getElementById('auto_submit_form').submit();
        //     });
        //     </script>";
        //     exit();
        // }
    }
}
