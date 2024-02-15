<?php
    // Assuming you've posted the form to this page using method="post"
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $conn = mysqli_connect("localhost", "root", "", "shopping"); 

        $selectedProductsJSON = $_POST['selectedProducts'] ?? '';
        $selectedProducts = json_decode($selectedProductsJSON, true);

        $totalPrice = $_POST['totalProductPrice'];
        $cusID = $_POST['customerName'];
        $status = $_POST['status'];

        // Now $selectedProducts contains the array, and you can use it as needed
        print_r($selectedProducts); // Example: Print the array for testing
        echo $totalPrice;
        echo $cusID;
        echo $status;

        // Generate new invoice ID
        $result = mysqli_query($conn, "SELECT MAX(InvID) AS inv_id FROM invoice");
        $row = mysqli_fetch_assoc($result);
        $lastID = $row['inv_id'];
        $numericPart = intval(substr($lastID, 6));
        $newNumericPart = $numericPart + 1;
        $InvID = 'inv_id'.str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        // Insert invoice record
        $stmt = mysqli_query($conn, "INSERT INTO invoice(InvID, Period ,CusID, TotalPrice , Status)
            VALUES ('$InvID', NOW(),'$cusID','$totalPrice','$status');");

        foreach ($selectedProducts as $product) {
            
            // Generate new NumID
            $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'");
            $latestID = mysqli_fetch_assoc($resultDetail);
            $lastID = $latestID['num_id'];
            $numericPart = intval(substr($lastID, 3));
            $newNumericPart = $numericPart + 1; 
            $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
            echo $lastID."    ".$numericPart."    ".$newNumericPart."    ".$NumID;
            echo "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'";
            
            $proID = $product['productId'];
            $Qty = $product['quantity'];
            
        
            // Insert invoice_detail record
            $stmt = mysqli_query($conn, "INSERT INTO invoice_detail (InvID, NumID, ProID, Qty) VALUES ('$InvID', '$NumID', '$proID', '$Qty')");

            // Update Status
            // $stmt = mysqli_query($conn, "UPDATE invoice_detail SET Status = 'Paid' WHERE invID ='$invID'");

            // Update Stock and OnHands
            $stmt = mysqli_query($conn, "UPDATE product SET OnHands = OnHands + '$Qty' WHERE ProID ='$proID'");
               
        }
        header( "location: ./invoice_index.php");  
    }
?>