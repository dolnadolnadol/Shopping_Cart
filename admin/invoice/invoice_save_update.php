<?php /* get connection */
    header( "location: ./invoice_index.php");  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include_once '../../dbConfig.php'; 
        $InvID = $_POST['InvID'];
        $RecvID = $_POST['id_receiver'];
        $Qty = $_POST['Qty'];
        $proId = $_POST['proId'];
     
        $totalPrice = $_POST['totalProductPrice'];
        $cusID = $_POST['customerName'];
        $status = $_POST['status'];

        // Now $selectedProducts contains the array, and you can use it as needed
        echo $totalPrice;
        echo $cusID;
        echo $status;
        print_r($proId);
        echo $InvID;

        /* Receiver */
        $recv_fname = $_POST['recv_fname'];
        $recv_lname = $_POST['recv_lname'];
        $recv_tel = $_POST['recv_tel'];
        $recv_address = $_POST['recv_address'];
        

        // Insert invoice Invord
        if($status != 'Unpaid'){
            mysqli_query($conn, "UPDATE invoice SET Period = NOW() , TotalPrice = '$totalPrice', Status ='$status'
            WHERE InvID ='$InvID'");

            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");

        }
        else{
            mysqli_query($conn ,"UPDATE invoice SET TotalPrice = '$totalPrice' 
            WHERE InvID ='$InvID'");
            
            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");
        
        }

        if (count($proId) == count($Qty)) {
                $totalItems = count($proId);
            
                for ($i = 0; $i < $totalItems; $i++) {
                    $proId = $proId[$i];
                    $qty = $Qty[$i];

                    $cur = "SELECT * FROM invoice_detail WHERE InvID = '$InvID' AND proId ='$proId'";
                    $msresults = mysqli_query($conn, $cur);
                    $row = mysqli_fetch_assoc($msresults);
                    $oldQty = $row['Qty'];
                    $stmt = mysqli_query($conn, "UPDATE product SET OnHand = OnHand - '$oldQty' WHERE proId ='$proId'");

                    // Update Stock and OnHand
                    $stmt = mysqli_query($conn, "UPDATE product SET OnHand = OnHand + '$qty' WHERE proId ='$proId'");

                    $stmt = mysqli_query($conn, "UPDATE invoice_detail SET Qty = '$qty'
                    WHERE proId ='$proId' AND InvID ='$InvID'");


                    echo "proId: $proId, Qty: $qty<br>";
                }
        } else {
            echo "ขนาดของอาร์เรย์ proId และ Qty ไม่เท่ากัน";
        }             
        }
        
?>