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

        // Generate new RECEIVE ID
        $result = mysqli_query($conn, "SELECT MAX(RecID) AS rec_id FROM receive");
        $row = mysqli_fetch_assoc($result);
        $lastID = $row['rec_id'];
        $numericPart = intval(substr($lastID, 6));
        $newNumericPart = $numericPart + 1;
        $RecID = 'rec_id'.str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        // Insert RECEIVE record
        $stmt = mysqli_query($conn, "INSERT INTO receive(RecID, OrderDate ,CusID, TotalPrice , Status)
            VALUES ('$RecID', NOW(),'$cusID','$totalPrice','$status');");

        foreach ($selectedProducts as $product) {
            
            // Generate new NumID
            $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'");
            $latestID = mysqli_fetch_assoc($resultDetail);
            $lastID = $latestID['num_id'];
            $numericPart = intval(substr($lastID, 3));
            $newNumericPart = $numericPart + 1; 
            $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
            echo $lastID."    ".$numericPart."    ".$newNumericPart."    ".$NumID;
            echo "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'";
            
            $proID = $product['productId'];
            $Qty = $product['quantity'];
            
        
            // Insert invoice_detail record
            $stmt = mysqli_query($conn, "INSERT INTO receive_detail (RecID, NumID, ProID, Qty) VALUES ('$RecID', '$NumID', '$proID', '$Qty')");

            // Update Status
            // $stmt = mysqli_query($conn, "UPDATE invoice_detail SET Status = 'Paid' WHERE invID ='$invID'");

            // Update Stock and OnHands
            $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '$Qty', OnHands = OnHands - '$Qty' WHERE ProID ='$proID'");
               
        }
        header( "location: ./order_index.php");  
    }
?>