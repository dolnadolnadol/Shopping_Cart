<?php
include('./component/session.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    /* Manage Order to send */
    if(isset($_POST['id_invoice'])&&isset($_POST['id_customer'])){
        $cusID = $_POST['id_customer'];
        $invID = $_POST['id_invoice'];

        echo $cusID;
        echo $invID;

        $cx =  mysqli_connect("localhost", "root", "", "shopping");

        $check_query = mysqli_query($cx, "SELECT InvID , CusID , TotalPrice FROM invoice WHERE CusID = '$cusID' AND InvID = '$invID'");
        $row = mysqli_fetch_assoc($check_query);
        $totalPrice = $row['TotalPrice'];

        if(mysqli_num_rows($check_query) > 0){
            mysqli_data_seek($check_query, 0);

            // Generate new RECEIVE ID
            $result = mysqli_query($cx, "SELECT MAX(RecID) AS rec_id FROM receive");
            $row = mysqli_fetch_assoc($result);
            $lastID = $row['rec_id'];
            $numericPart = intval(substr($lastID, 6));
            $newNumericPart = $numericPart + 1;
            $RecID = 'rec_id'.str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            echo $RecID;

            // Insert RECEIVE record
            $stmt = mysqli_query($cx, "INSERT INTO receive(RecID, OrderDate , TaxID ,CusID, TotalPrice , Status)
                VALUES ('$RecID', NOW() ,'$cusID','$totalPrice','Pending');");

  
            while (true) {
                // Generate new NumID
                $resultDetail = mysqli_query($cx, "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'");
                $latestID = mysqli_fetch_assoc($resultDetail);
                $lastID = $latestID['num_id'];
                $numericPart = intval(substr($lastID, 3));
                $newNumericPart = $numericPart + 1; 
                $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

                echo $NumID;
                // echo $lastID."    ".$numericPart."    ".$newNumericPart."    ".$NumID;
                // echo "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'";

                $resultDetail = mysqli_query($cx, "SELECT invID , ProID , Qty FROM invoice_detail WHERE invID = '$invID' AND NumID = '$NumID'");
                
                echo mysqli_num_rows($resultDetail);
               
                if ($resultDetail && mysqli_num_rows($resultDetail) > 0) {
                    $invoice_detail = mysqli_fetch_assoc($resultDetail);
                    $proID = $invoice_detail['ProID'];
                    $Qty = $invoice_detail['Qty'];
                    $invID = $invoice_detail['invID'];

                    // Insert invoice_detail record
                    $stmt = mysqli_query($cx, "INSERT INTO receive_detail (RecID, NumID, ProID, Qty) VALUES ('$RecID', '$NumID', '$proID', '$Qty')");

                    // Update Status
                    $stmt = mysqli_query($cx, "UPDATE invoice SET Status = 'Paid' WHERE invID ='$invID'");

                    // Update Stock and OnHands
                    $stmt = mysqli_query($cx, "UPDATE product SET StockQty = StockQty - '$Qty', OnHands = OnHands - '$Qty' WHERE ProID ='$proID'");
                } else {
                    // No more matching records found, break the loop
                    break;
                }        
            }
            $TotalWithTax = $totalPrice * 1.07;


            /*-------------------------------------*/
            $stmt = mysqli_query($cx, "UPDATE receive SET TotalPrice ='$TotalWithTax'
            WHERE RecID ='$RecID'");
            /*-------------------------------------*/

            // Form submission with hidden values
            echo "<form id='auto_submit_form' method='post' action='order.php'>
            <input type='hidden' name='id_order' value='$RecID'>
            </form>";
    
            // Use JavaScript to trigger form submission
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('auto_submit_form').submit();
            });
            </script>";
            exit();
        }

    }
}
?>