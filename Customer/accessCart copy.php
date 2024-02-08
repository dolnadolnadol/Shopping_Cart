<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* Add product in cart */
    if(isset($_POST['id_product']) && isset($_POST['amount'])){

        $productId = $_POST['id_product'];
        $amount = $_POST['amount'];
        $user = $_SESSION['username'];

        $cx =  mysqli_connect("localhost", "root", "", "shopping");


        $uid_query = mysqli_query($cx, "SELECT CusID FROM customer WHERE Username = '$user'");
        $uid_row = mysqli_fetch_assoc($uid_query);
        $uid_results = $uid_row['CusID'];

        // Check if the record already exists in the cart table for the given CusID and ProID
        $check_query = mysqli_query($cx, "SELECT * FROM cart WHERE CusID = '$uid_results' AND ProID = '$productId'");
        if(mysqli_num_rows($check_query) > 0){
            echo "Product already exists in the cart for the user.";
            $stmt = mysqli_query($cx, "UPDATE cart SET Qty ='$amount'
            WHERE CusID ='$uid_results' AND ProID = '$productId'");
            header("Location: ./cart.php");
        }
        else {
            $stmt = "INSERT INTO cart(CusID, ProID, Qty) VALUES('$uid_results', '$productId', '$amount')";
            $msresults = mysqli_query($cx, $stmt);
            header("Location: ./index.php");
            exit(); 
        }
    }
    /* Delete product in cart */
    else if(isset($_POST['CusID']) && isset($_POST['deleteID'])){
        $cusID = $_POST['CusID'];
        $proID = $_POST['deleteID'];
        $cx =  mysqli_connect("localhost", "root", "", "shopping");
        $check_query = mysqli_query($cx, "SELECT * FROM cart WHERE CusID = '$cusID' AND ProID = '$proID'");
        if(mysqli_num_rows($check_query) > 0){
            $check_query = mysqli_query($cx, "DELETE FROM cart WHERE CusID = '$cusID' AND ProID = '$proID'");  
        }
        header("Location: ./cart.php");
        exit(); 
    }
  
    /* Manage Invoice to send */
    // else if(isset($_POST['id_customer'])){
    //     $cusID = $_POST['id_customer'];
    //     $cx =  mysqli_connect("localhost", "root", "", "shopping");
    //     $check_query = mysqli_query($cx, "SELECT Product.ProID , Qty , Product.PricePerUnit FROM cart 
    //     INNER JOIN Product ON Cart.ProID = Product.ProID WHERE CusID = '$cusID'");
        
    //     if(mysqli_num_rows($check_query) > 0){
    //     mysqli_data_seek($check_query, 0);

    //     // Generate new InvoiceID
    //     $result = mysqli_query($cx, "SELECT MAX(InvID) AS inv_id FROM invoice");
    //     $row = mysqli_fetch_assoc($result);
    //     $lastID = $row['inv_id'];
    //     $numericPart = intval(substr($lastID, 3));
    //     $newNumericPart = $numericPart + 1;
    //     $InvID = 'INV' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

    //     // Insert invoice record
    //     $stmt = mysqli_query($cx, "INSERT INTO invoice (InvID, Period, CusID)
    //         VALUES ('$InvID', NOW(), '$cusID');");

    //     $totalPriceAllItems = 0; 
    //     $Total = 0;

    //     while ($row = mysqli_fetch_array($check_query)) {
    //         // Generate new NumID
    //         $resultDetail = mysqli_query($cx, "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'");
    //         $row2 = mysqli_fetch_assoc($resultDetail);
    //         $lastID = $row2['num_id'];
    //         $numericPart = intval(substr($lastID, 3));
    //         $newNumericPart = $numericPart + 1;
    //         $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

    //         $proID = $row['ProID'];
    //         $Qty = $row['Qty'];

    //         $subTotal = $row['PricePerUnit'] * $row['Qty'];
    //         $totalPriceAllItems += $subTotal;
    //         $Tax = $totalPriceAllItems * 0.07;
    //         $Total = $Tax + $totalPriceAllItems;

    //         // Insert invoice_detail record
    //         $stmt = mysqli_query($cx, "INSERT INTO invoice_detail (NumID, InvID, ProID, Qty, Status)
    //             VALUES ('$NumID', '$InvID', '$proID', '$Qty', 'Pending');");        
    //     }

    //         /*------------จิงๆไม่ควรอยู่ตรงนี้------------*/
    //         $stmt = mysqli_query($cx, "UPDATE invoice SET TotalPrice ='$Total'
    //         WHERE InvID ='$InvID'");
    //         /*-------------------------------------*/

    //         //DELETE WHEN INSERT INVOICE SUCCESS
    //         $stmt = mysqli_query($cx,"DELETE FROM cart WHERE CusID='$cusID'");

    //         // Form submission with hidden values
    //         echo "<form id='auto_submit_form' method='post' action='invoice.php'>
    //         <input type='hidden' name='id_invoice' value='$InvID'>
    //         </form>";
    
    //         // Use JavaScript to trigger form submission
    //         echo "<script>
    //         document.addEventListener('DOMContentLoaded', function() {
    //             document.getElementById('auto_submit_form').submit();
    //         });
    //         </script>";
    //         exit();
    //     }
    //     else {
    //         header("Location: ./cart.php");
    //         exit(); 
    //     } 
    // }
}
?>


