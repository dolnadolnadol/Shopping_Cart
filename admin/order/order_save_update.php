<?php /* get connection */
    // header( "location: ./stock_index.php");
   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $conn = mysqli_connect("localhost", "root", "", "shopping"); 
        $RecID = $_POST['RecID'];
        $Qty = $_POST['Qty'];
        $ProID = $_POST['ProID'];
     
        $totalPrice = $_POST['totalProductPrice'];
        $cusID = $_POST['customerName'];
        $status = $_POST['status'];

        // Now $selectedProducts contains the array, and you can use it as needed
        echo $totalPrice;
        echo $cusID;
        echo $status;
        print_r($ProID);
        

        // Insert RECEIVE record
        if($status != 'Pending'){
            $stmt = mysqli_query($conn, "UPDATE receive SET DeliveryDate = NOW() , TotalPrice = '$totalPrice', Status ='$status'
            WHERE RecID ='$RecID'");

        }
        else{
            $stmt = mysqli_query($conn, "UPDATE receive SET TotalPrice = '$totalPrice'
            WHERE RecID ='$RecID'");
        }

        if (count($ProID) == count($Qty)) {
                $totalItems = count($ProID);
            
                for ($i = 0; $i < $totalItems; $i++) {
                    $proID = $ProID[$i];
                    $qty = $Qty[$i];

                    $cur = "SELECT * FROM receive_detail WHERE RecID = '$RecID' AND ProID ='$proID'";
                    $msresults = mysqli_query($conn, $cur);
                    $row = mysqli_fetch_assoc($msresults);
                    $oldQty = $row['Qty'];
                    $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty + '$oldQty', OnHands = OnHands + '$oldQty' WHERE ProID ='$proID'");

                    // Update Stock and OnHands
                    $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '$qty', OnHands = OnHands - '$qty' WHERE ProID ='$proID'");

                    $stmt = mysqli_query($conn, "UPDATE receive_detail SET Qty = '$qty'
                    WHERE ProID ='$proID' AND RecID ='$RecID'");


                    echo "ProID: $proID, Qty: $qty<br>";
                }
        } else {
            echo "ขนาดของอาร์เรย์ ProID และ Qty ไม่เท่ากัน";
        }             
        }
        header( "location: ./order_index.php");  
?>