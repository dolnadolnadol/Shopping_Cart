<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* Manage Order to send */
    if(isset($_POST['id_invoice'])&&isset($_POST['id_customer'])){
        $cusID = $_POST['id_customer'];
        $invID = $_POST['id_invoice'];
        $cx =  mysqli_connect("localhost", "root", "", "shopping");

        $check_query = mysqli_query($cx, "SELECT InvID , CusID FROM invoice WHERE CusID = '$cusID' AND InvID = '$invID'");
        
        if(mysqli_num_rows($check_query) > 0){
            mysqli_data_seek($check_query, 0);

            // Generate new RECEIVE ID
            $result = mysqli_query($cx, "SELECT MAX(RecID) AS rec_id FROM receive");
            $row = mysqli_fetch_assoc($result);
            $lastID = $row['rec_id'];
            $numericPart = intval(substr($lastID, 3));
            $newNumericPart = $numericPart + 1;
            $RecID = 'RecID' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            // Insert RECEIVE record
            $stmt = mysqli_query($cx, "INSERT INTO receive(RecID, Period, CusID)
                VALUES ('$RecID', NOW(), '$cusID');");

            $totalPriceAllItems = 0; 
            $Total = 0;

            while ($row = mysqli_fetch_array($check_query)) {
                // Generate new NumID
                $resultDetail = mysqli_query($cx, "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'");
                $row2 = mysqli_fetch_assoc($resultDetail);
                $lastID = $row2['num_id'];
                $numericPart = intval(substr($lastID, 3));
                $newNumericPart = $numericPart + 1;
                $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

                $proID = $row['ProID'];
                $Qty = $row['Qty'];

                $subTotal = $row['PricePerUnit'] * $row['Qty'];
                $totalPriceAllItems += $subTotal;
                $Tax = $totalPriceAllItems * 0.07;
                $Total = $Tax + $totalPriceAllItems;

                // Insert invoice_detail record
                $stmt = mysqli_query($cx, "INSERT INTO receive_detail (RecID ,NumID, ProID, Qty)
                    VALUES ('$RecID', '$NumID', '$proID', '$Qty');");        
            }

            /*-------------------------------------*/
            $stmt = mysqli_query($cx, "UPDATE receive SET TotalPrice ='$TotalPrice'
            WHERE RecID ='$RecID'");
            /*-------------------------------------*/

            // Form submission with hidden values
            echo "<form id='auto_submit_form' method='post' action='order.php'>
            <input type='hidden' name='id_invoice' value='$RecID'>
            </form>";
    
            // Use JavaScript to trigger form submission
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('auto_submit_form').submit();
            });
            </script>";
            exit();
        }
        else {
            // header("Location: ./index.php");
            // exit(); 
        } 
    }
}
?>


