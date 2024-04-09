<?php /* get connection */
    header( "location: ./order_index.php");  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include_once '../../dbConfig.php'; 
        $RecID = $_POST['RecID'];
        $RecvID = $_POST['id_receiver'];
        $TaxID = $_POST['id_payer'];
        $Qty = $_POST['Qty'];
        $proId = $_POST['proId'];
     
        $totalPrice = $_POST['totalProductPrice'];
        $cusID = $_POST['customerName'];
        $status = $_POST['status'];

        // Now $selectedProducts contains the array, and you can use it as needed
        echo $totalPrice;
        echo $cusID;
        echo $status;
        echo $RecvID;
        print_r($proId);
        
        /* Receiver */
        $recv_fname = $_POST['recv_fname'];
        $recv_lname = $_POST['recv_lname'];
        $recv_tel = $_POST['recv_tel'];
        $recv_address = $_POST['recv_address'];

        /* Payer */
        $payer_fname = $_POST['payer_fname'];
        $payer_lname = $_POST['payer_lname'];
        $payer_tel = $_POST['payer_tel'];

        // Insert RECEIVE record
        if($status != 'Pending'){
            mysqli_query($conn, "UPDATE receive SET DeliveryDate = NOW() , TotalPrice = '$totalPrice', Status ='$status'
            WHERE RecID ='$RecID'");

            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");

            mysqli_query($conn, "UPDATE payer SET PayerFName = '$payer_fname', PayerLName = '$payer_lname' , Tel = '$payer_tel'
            WHERE TaxID ='$TaxID'");

        }
        else{
            mysqli_query($conn, "UPDATE receive SET TotalPrice = '$totalPrice'
            WHERE RecID ='$RecID'");

            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");

            mysqli_query($conn, "UPDATE payer SET PayerFName = '$payer_fname', PayerLName = '$payer_lname' , Tel = '$payer_tel'
            WHERE TaxID ='$TaxID'");

        }

        if (count($proId) == count($Qty)) {
                $totalItems = count($proId);
            
                for ($i = 0; $i < $totalItems; $i++) {
                    $proId = $proId[$i];
                    $qty = $Qty[$i];

                    $cur = "SELECT * FROM receive_detail WHERE RecID = '$RecID' AND proId ='$proId'";
                    $msresults = mysqli_query($conn, $cur);
                    $row = mysqli_fetch_assoc($msresults);
                    $oldQty = $row['Qty'];
                    $stmt = mysqli_query($conn, "UPDATE product SET Qty = Qty + '$oldQty', OnHand = OnHand + '$oldQty' WHERE proId ='$proId'");

                    // Update Stock and OnHand
                    $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '$qty', OnHand = OnHand - '$qty' WHERE proId ='$proId'");

                    $stmt = mysqli_query($conn, "UPDATE receive_detail SET Qty = '$qty'
                    WHERE proId ='$proId' AND RecID ='$RecID'");


                    echo "proId: $proId, Qty: $qty<br>";
                }
        } else {
            echo "ขนาดของอาร์เรย์ proId และ Qty ไม่เท่ากัน";
        }             
        }
        
?>