<?php include('./component/session.php');

/* มี ฺBug ถ้าไม่ลบ Guest ก่อนที่จะกดกลับไปหน้าหลัก */
include('./component/backButton.php');
/* --------------------------------------- */

include('../logFolder/AccessLog.php');
include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .checkout-container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            bottom: 0;
        }

        .flex-container {
            width: 100%;
            display: flex;
        }

        .checkout-sidebar {
            flex: 0.5;
            border: 1px solid #ddd;
            padding: 20px;
            position: sticky;
            bottom: 0;
            border-color: #000;
            width: max-content;
            height: max-content;
        }



        .checkout-content {
            flex: 2;
            padding: 20px;
        }

        .checkout-header {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .checkout-steps {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .checkout-step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            cursor: pointer;
        }

        .checkout-step.active {
            border-bottom: 2px solid #27ae60;
        }

        .checkout-step:not(.active) {
            color: #888;
        }

        .checkout-form {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .checkout-button {
            background-color: #27ae60;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .checkout-button:hover {
            background-color: #219653;
        }

        .checkout-sidebar {
            flex: 0.5;
            border: 1px solid #ddd;
            padding: 20px;
            position: sticky;
            bottom: 0;
            border-color: #000;
            width: max-content;
            height: max-content;
            background-color: #f9f9f9;
            /* สีพื้นหลังของ sidebar */
            border-radius: 8px;
            /* เพิ่มมุมโค้งให้กับ sidebar */
        }

        .invoice-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .customer-details,
        .summary-details {
            margin-bottom: 20px;
        }

        .text-container {
            margin-bottom: 10px;
        }

        .view-details-link {
            color: #3498db;
            text-decoration: none;
        }

        .view-details-link:hover {
            text-decoration: underline;
        }

        .customer-details h4,
        .summary-details h4 {
            background-color: #3498db;
            /* สีพื้นหลังของ <h4> */
            color: #fff;
            /* สีข้อความของ <h4> */
            padding: 10px;
            /* ระยะห่างขอบของ <h4> */
            border-radius: 8px;
            /* มุมโค้งของ <h4> */
            margin-top: 0;
            /* ลบ margin ด้านบนของ <h4> */
        }

        input[type="submit"] {
            background-color: #3498db;
            font-weight: bold;
            color: white;
        }

        input[type="submit"]:hover {
            background-color: #2B7EB5;
        }

        input[type="submit"]:focus {
            background-color: #194969;
        }
    </style>
</head>

<body>
    <form id="profileForm" method="post" action="accessOrder.php" enctype="multipart/form-data">
        <?php
        if (isset($_SESSION['id_username'])) {
            $uid = $_SESSION['id_username'];
            $deli = $_POST['id_deli'];
            $lastId = $_POST['id_order'];
            $addrId = $_POST['id_address'];

            include_once '../dbConfig.php';

            $query_address = "SELECT * FROM address 
                      INNER JOIN customer ON customer.cusId = address.cusId  
                      WHERE address.CusID = ?";

            $stmt = mysqli_prepare($conn, $query_address);
            mysqli_stmt_bind_param($stmt, "s", $uid);
            mysqli_stmt_execute($stmt);
            $result_address = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result_address) > 0) {
                $row = mysqli_fetch_assoc($result_address);
            }
        }
        ?>

        <div class="checkout-container">
            <div class="checkout-header">
                <h4>Checkout</h4>
            </div>

            <div class="checkout-steps">
                <div class="checkout-step"> Step 1: Shipping</div>
                <div class="checkout-step active">Step 2: Payment</div>
                <div class="checkout-step">Step 3: Success</div>
            </div>

            <div class="flex-container">
                <!-- Main content -->
                <div class="checkout-content">
                    <div style="margin-left:1.5rem; padding:1rem; border:2px solid; font-weight:bold;">
                        โอนมาที่ KBANK - 0323802777 นางสาวชลลดา แซ่ลิ้ม <br />
                    </div>
                    <div id="paymentForm" class="checkout-form" style="display: block;">
                    <p style=" font-weight:bold; font-size:20px;">
                        Transaction Infomation
                    </p>
                        <!-- Payment form content -->
                        <div class="form-group">
                            <label for="firstname">First Name: </label>
                            <input type="text" id="fname" name="fname" required>
                        </div>

                        <div class="form-group">
                            <label for="lastname">Last Name: </label>
                            <input type="text" id="lname" name="lname" required>
                        </div>

                        <div class="form-group">
                            <label for="time">transaction time: </label>
                            <input type="datetime-local" id="time" name="time" required>
                        </div>

                        <!-- <div class="form-group">
                            <label for="tel">Tel :</label>
                            <input type="text" id="tel" name="tel" value="<?php echo $row['Tel'] ?? ''; ?>" required>
                        </div> -->

                        <div class="form-group">
                            <label for="slip">Attach Slip</label>
                            <input type="file" id="slip" name="slip" accept=".jpeg,.png,.jpg" required></input>
                        </div>
                        <input type='hidden' name='id_customer' value='<?php echo $uid; ?>'>
                        <input type='hidden' name='id_order' value='<?php echo $lastId ?>'>
                        <input type='hidden' name='id_deli' value='<?php echo $deli ?>'>
                        <!-- <button class="checkout-button" onclick="submit()">ชำระเงิน</button> -->
                        <div style="display:flex">
                            <input type="checkbox" value="wantinvoice" id="wantinvoice" style="background-color:red; width:fit-content; margin-left:2px; margin-right:2px; margin-bottom:2px;" onclick="invoicefill()">
                            <label for="invoice" style="font-weight:300">ต้องการรับinvoice</label>
                        </div>

                        <div id='invoicefill' style="display: none; margin:2rem; padding:1rem; border: 1px solid;">
                            <div class="form-group">
                                <label>เลขกำกับภาษี</label>
                                <input name="invoice-taxid" id="invoice-taxid" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>ชื่อ</label>
                                <input name="invoice-name" id="invoice-name" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>ที่อยู่</label>
                                <input name="invoice-address" id="invoice-address" type="text" required>
                            </div>
                        </div>

                        <input type="submit">
                    </div>
                </div>

                <div class="checkout-sidebar">
                    <?php
                    echo "<div class='invoice-container'>";
                    echo "<div class='body-container'>";
                    echo "<div class='invoice-header'>
                    <h4></h4>
                    </div>";

                    if (isset($_POST['id_order'])) {
                        $customerDetailsQuery = mysqli_query($conn, "SELECT * FROM address
                        WHERE address.addrId = '$addrId'");
                        $customerDetails = mysqli_fetch_array($customerDetailsQuery);
                        $customerId = $uid;

                        echo "<div class='customer-details'>
                       
                        <h4><strong>สรุปการจัดส่ง</strong></h4>
                    
                        <div class='text-container'>
                            <p><strong>Shipping Address</strong></p>
                            <p><strong>Name:</strong> {$customerDetails['fname']} {$customerDetails['lname']} </p>
                            <p><strong>Tel:</strong> {$customerDetails['tel']}</p>
                            <div class='address-container'>
                                <p><strong>Address:</strong> {$customerDetails['Address']}</p>
                            </div>
                        </div>
                        
                        <h4><strong>สรุปการคำสั่งซื้อ</strong></h4>";
                        $totalPriceAllItems = 0;
                        $Orderquery = mysqli_query($conn, "SELECT *,ordervalue.Qty AS qtyordervalue,product.ProductName AS PRONAME FROM orderkey
                        INNER JOIN ordervalue ON ordervalue.orderId =  orderkey.orderId
                        INNER JOIN product ON ordervalue.ProId =  product.proId
                        WHERE orderkey.orderId  = '$lastId'");

                        while ($invoiceDetails = mysqli_fetch_array($Orderquery)) {
                            $totalPrice = $invoiceDetails['Price'] * $invoiceDetails['qtyordervalue'];
                            $totalPriceAllItems += $totalPrice;

                            echo "<div class='text-container'>
                                <p><strong> {$invoiceDetails['PRONAME']}</strong></p>
                                <p><strong>฿{$invoiceDetails['Price']}</strong> จำนวน {$invoiceDetails['qtyordervalue']} ชิ้น</p>
                            </div>";
                        }

                        $tax = $totalPriceAllItems * 0.07;
                        $totalAmount = $tax + $totalPriceAllItems;

                        echo "
                       <h4>สรุปยอดชำระเงิน</h4>
                            <div class='text-container'>
                                <p><strong>VAT:</strong>{$tax}</p>
                                <p><strong>ราคารวม: </strong>  {$totalPriceAllItems}</p>                     
                            </div>
                        <hr> 
                            <div class='text-container'>
                                <p><strong>ยอดชำระสุทธิ: </strong>  {$totalAmount}</p>                              
                            </div>
                        <hr>                        
                    </div>";
                    }
                    ?>
                    <!-- <a href='./invoice.php?id_invoice={$inv}&id_receiver={$row['RecvID']}' class='view-details-link' style='text-decoration: underline; color: #3498db;'>View Full Details</a> -->
                </div>
            </div>
        </div>
        </div>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('invoice-taxid').required = false;
            document.getElementById('invoice-name').required = false;
            document.getElementById('invoice-address').required = false;
        });

        function invoicefill() {
            if (document.getElementById('invoicefill').style.display == 'none') {
                document.getElementById('invoicefill').style.display = 'block';
                document.getElementById('invoice-taxid').required = true;
                document.getElementById('invoice-name').required = true;
                document.getElementById('invoice-address').required = true;
            } else {
                document.getElementById('invoicefill').style.display = 'none';
                document.getElementById('invoice-taxid').required = false;
                document.getElementById('invoice-name').required = false;
                document.getElementById('invoice-address').required = false;
            }
        }
    </script>
</body>

</html>