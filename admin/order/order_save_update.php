<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูลที่เราต้องการจากฟอร์มหรือไม่
    
        // เชื่อมต่อกับฐานข้อมูล
        $cx = mysqli_connect('localhost', 'root', '', 'shopping');

        // ดึงข้อมูลจากฟอร์ม
        $RecID = $_POST['RecID'];
        $status = $_POST['status'];
        $customerName = $_POST['customerName'];
        $dueDate = $_POST['dueDate'];
        $amountList = $_POST['amountList'];
        $quantityList = $_POST['quantityList'];
        $totalProductPrice = $_POST['totalProductPrice'];

        echo $RecID;
        echo $status;
        echo $customerName;
        echo $dueDate;
        echo $totalProductPrice;

        $selectedProducts = $_POST['selectedProducts'];
        $proIDs = explode(',', $selectedProducts);

        foreach ($proIDs as $proID) {
            // ทำอะไรก็ตามที่ต้องการกับ $proID
            echo "Selected ProID: " . $proID . "<br>";
        }

        // ทำการ Insert ข้อมูลลงในตาราง receive
        $insertReceiveQuery = "INSERT INTO receive (RecID, OrderDate , CusID , totalPrice ,Status) VALUES ('$RecID',  '$dueDate' ,'$totalProductPrice' ,'$status')";
        mysqli_query($cx, $insertReceiveQuery);

        // ทำการ Insert ข้อมูลลงในตาราง order
        $insertOrderQuery = "INSERT INTO orders (RecID, CusName, DueDate) VALUES ('$RecID', '$customerName', '$dueDate')";
        mysqli_query($cx, $insertOrderQuery);

        // ทำการ Insert ข้อมูลลงในตาราง order_details สำหรับแต่ละสินค้าในรายการ
        for ($i = 0; $i < count($productList); $i++) {
            $productName = $productList[$i];
            $amount = $amountList[$i];
            $quantity = $quantityList[$i];

            $insertOrderDetailsQuery = "INSERT INTO receive_details (RecID, ProName, Amount, Quantity) 
                                        VALUES ('$RecID', '$productName', '$amount', '$quantity')";
            mysqli_query($cx, $insertOrderDetailsQuery);
        }

        // ปิดการเชื่อมต่อกับฐานข้อมูล
        mysqli_close($cx);

        // ส่งผลการทำงานกลับไปยังเว็บไซต์หลักหรือทำการ redirect
        header('Location: success_page.php');
        exit();
    } 
else {
    // ถ้าไม่ใช่การ submit จากฟอร์ม
    echo 'Invalid request.';
}
?>
