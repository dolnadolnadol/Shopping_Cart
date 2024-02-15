<?php /* get connection */
    header( "location: ./invoice_index.php");  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $conn = mysqli_connect("localhost", "root", "", "shopping"); 
        $InvID = $_POST['InvID'];
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
        echo $InvID;
        

        // Insert invoice Invord
        if($status != 'Unpaid'){
            $stmt = mysqli_query($conn, "UPDATE invoice SET Period = NOW() , TotalPrice = '$totalPrice', Status ='$status'
            WHERE InvID ='$InvID'");

        }
        else{
            $stmt =mysqli_query($conn ,"UPDATE invoice SET TotalPrice = '$totalPrice' WHERE InvID ='$InvID'");
        }

        if (count($ProID) == count($Qty)) {
                $totalItems = count($ProID);
            
                for ($i = 0; $i < $totalItems; $i++) {
                    $proID = $ProID[$i];
                    $qty = $Qty[$i];

                    $cur = "SELECT * FROM invoice_detail WHERE InvID = '$InvID' AND ProID ='$proID'";
                    $msresults = mysqli_query($conn, $cur);
                    $row = mysqli_fetch_assoc($msresults);
                    $oldQty = $row['Qty'];
                    $stmt = mysqli_query($conn, "UPDATE product SET OnHands = OnHands - '$oldQty' WHERE ProID ='$proID'");

                    // Update Stock and OnHands
                    $stmt = mysqli_query($conn, "UPDATE product SET OnHands = OnHands + '$qty' WHERE ProID ='$proID'");

                    $stmt = mysqli_query($conn, "UPDATE invoice_detail SET Qty = '$qty'
                    WHERE ProID ='$proID' AND InvID ='$InvID'");


                    echo "ProID: $proID, Qty: $qty<br>";
                }
        } else {
            echo "ขนาดของอาร์เรย์ ProID และ Qty ไม่เท่ากัน";
        }             
        }
        
?>