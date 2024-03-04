<?php /* get connection */
    $conn = mysqli_connect("localhost", "root", "", "shopping");
   
    /*SELECT*/
    $code = $_POST['id_customer'];
    $cur = "SELECT * FROM Customer WHERE CusID = '$code'";

    $msresults = mysqli_query($conn,$cur);
    $row = mysqli_fetch_array($msresults);
    if(mysqli_num_rows($msresults) > 0) {
  
        $cur = "SELECT Customer.CusFName , Customer.CusLName , Customer.Sex , Customer.Tel , receiver.* FROM Customer 
        INNER JOIN receiver_detail ON receiver_detail.CusID = customer.CusID
        INNER JOIN receiver ON receiver_detail.RecvID = receiver.RecvID
        WHERE receiver_detail.CusID = '$code'";

        $msresults_receiver = mysqli_query($conn,$cur);
        $row_recv = mysqli_fetch_array($msresults_receiver);
        
        echo "<form method='post' action='customer_save_update.php'>";
        echo "<center>";
    
        echo "<h1> Update Customer Form </h1>";
        echo "<h2>รหัสลูกค้า ". $row['CusID'] ."</h2><br>";
        echo "<input type='hidden' name='id_customer' value='" . $row['CusID'] . "'>";
        echo "<input type='hidden' name='id_receiver' value='" . (isset($row_recv['RecvID']) ? $row_recv['RecvID'] : "") . "'>";
        echo "ชื่อ <input type='text' name='a1' value='" . $row['CusFName'] . "'><br>";
        echo "นามสกุล <input type='text' name='a2' value='" . $row['CusLName'] . "'><br>";
        echo "เพศ <input type='text' name='a3' value='" . $row['Sex'] . "'><br>";
        echo "เบอร์โทรศัพท์ <input type='text' name='a4' value='" . $row['Tel'] . "'><br>";
        echo "ที่อยู่ <textarea name='a5'>" . (isset($row_recv['Address']) ? $row_recv['Address'] : "") . "</textarea><br>";
        echo "⚠️โปรดให้เเน่ใจที่จะต้องการอัปเดตข้อมูล⚠️<br><br>";
        echo "<input type='submit' value='ยืนยัน'>";
        echo "<input type='button' value='กลับ' onclick='window.history.back();'>";
    
        echo "</form>\n"; 
        echo "</center>";
    }

    /* close connection */
    mysqli_close($conn);
?>