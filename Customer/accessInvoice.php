<?php
include('./component/session.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Manage Invoice to send */
    if(isset($_POST['id_customer'])){
        $cusID = $_POST['id_customer'];
        $cx =  mysqli_connect("localhost", "root", "", "shopping");
        $check_query = mysqli_query($cx, "SELECT Product.ProID , Qty , Product.PricePerUnit FROM cart 
        INNER JOIN Product ON Cart.ProID = Product.ProID WHERE CusID = '$cusID'");
        
        if(mysqli_num_rows($check_query) > 0){
            mysqli_data_seek($check_query, 0);

            // Generate new InvoiceID
            $result = mysqli_query($cx, "SELECT MAX(InvID) AS inv_id FROM invoice");
            $row = mysqli_fetch_assoc($result);
            $lastID = $row['inv_id'];
            $numericPart = intval(substr($lastID, 3));
            $newNumericPart = $numericPart + 1;
            $InvID = 'INV' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            // Insert invoice record
            $stmt = mysqli_query($cx, "INSERT INTO invoice (InvID, Period, CusID , Status)
                VALUES ('$InvID', NOW(), '$cusID' , 'Unpaid');");

            $totalPriceAllItems = 0; 
            $Total = 0;

            while ($row = mysqli_fetch_array($check_query)) {
                // Generate new NumID
                $resultDetail = mysqli_query($cx, "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'");
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
                $stmt = mysqli_query($cx, "INSERT INTO invoice_detail (NumID, InvID, ProID, Qty)
                    VALUES ('$NumID', '$InvID', '$proID', '$Qty');");        
            }

            /*-------------------------------------*/
            $stmt = mysqli_query($cx, "UPDATE invoice SET TotalPrice ='$Total'
            WHERE InvID ='$InvID'");
            /*-------------------------------------*/

            if (empty($cusID)) {
                echo "Customer ID is empty!";
                exit();
            }
            echo $cusID;
            //DELETE WHEN INSERT INVOICE SUCCESS
            $deleteQuery = "DELETE FROM cart WHERE CusID = '$cusID'";
            $result = mysqli_query($cx, $deleteQuery);

            if ($result) {
                // Deletion was successful
                // Additional code or actions can be added here if needed
                echo "Deletion successful!";
            } else {
                // Deletion failed
                // Additional error handling or messages can be added here
                echo "Deletion failed: " . mysqli_error($cx);
            }

            // Form submission with hidden values
            echo "<form id='auto_submit_form' method='post' action='invoice.php'>
            <input type='hidden' name='id_invoice' value='$InvID'>
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
            header("Location: ./cart.php");
            exit(); 
        } 
    }
}
?>