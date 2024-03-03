<?php include('./component/session.php');

/* มี ฺBug ถ้าไม่ลบ Guest ก่อนที่จะกดกลับไปหน้าหลัก */
include('./component/backButton.php');
/* --------------------------------------- */

include('../logFolder/AccessLog.php');
include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');?>
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
            flex:0.5;
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
    background-color: #f9f9f9; /* สีพื้นหลังของ sidebar */
    border-radius: 8px; /* เพิ่มมุมโค้งให้กับ sidebar */
}

.invoice-container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

/* .invoice-header {
    color: #fff;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 20px;
} */

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
    background-color: #3498db; /* สีพื้นหลังของ <h4> */
    color: #fff; /* สีข้อความของ <h4> */
    padding: 10px; /* ระยะห่างขอบของ <h4> */
    border-radius: 8px; /* มุมโค้งของ <h4> */
    margin-top: 0; /* ลบ margin ด้านบนของ <h4> */
}
input[type="submit"]{
    background-color: #3498db;
    font-weight:bold;
    color:white;
}
input[type="submit"]:hover{
    background-color: #2B7EB5;
}
input[type="submit"]:focus{
    background-color: #194969;
}
    </style>
</head>

<body>
    <form id="profileForm" method="post" action="accessOrder.php">
        <?php
        if (isset($_SESSION['id_username'])) {
            $uid = $_SESSION['id_username'];
            $inv = $_POST['id_invoice'];
            $recv_id = $_POST['id_receiver'];


            $cx =  mysqli_connect("localhost", "root", "", "shopping");
            $query_address = "SELECT * FROM receiver 
            INNER JOIN receiver_detail ON receiver.RecvID = receiver_detail.RecvID  
            WHERE receiver_detail.CusID = '$uid'";
            $result_address = mysqli_query($cx, $query_address);
            if (mysqli_num_rows($result_address) > 0) {
                // Fetch a single row from the result set
                $row = mysqli_fetch_assoc($result_address);
            }
        }
        ?>
        <div class="checkout-container">
            <div class="checkout-header">
                <h4>Checkout</h4>
            </div>

            <div class="checkout-steps">
                <div class="checkout-step">Step 1: Shipping</div>
                <div class="checkout-step active">Step 2: Payment</div>
                <div class="checkout-step">Step 3: Success</div>
            </div>

            <div class="flex-container">
                <!-- Main content -->
                <div class="checkout-content">
                    <div id="paymentForm" class="checkout-form" style="display: block;">
                        <!-- Payment form content -->
                        <div class="form-group">
                            <label for="firstname">Reciever First Name: </label>
                            <input type="text" id="fname" name="fname" value="<?php echo $row['RecvFName'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="lasttname">Reciever Last Name: </label>
                            <input type="text" id="lname" name="lname" value="<?php echo $row['RecvLName'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="tel">Tel :</label>
                            <input type="text" id="tel" name="tel" value="<?php echo $row['Tel'] ?? ''; ?>" required>
                        </div>
                        <input type='hidden' name='id_customer' value='<?php echo $uid; ?>'>
                        <input type='hidden' name='id_receiver' value='<?php echo $row['RecvID']; ?>'>
                        <input type='hidden' name='id_invoice' value='<?php echo $inv; ?>'>
                        <!-- <button class="checkout-button" onclick="submit()">ชำระเงิน</button> -->
                        <input type="submit">
                    </div>
                </div>

                <div class="checkout-sidebar">
                    <!-- Sidebar content -->
                    <?php
                    $cx = mysqli_connect("localhost", "root", "", "shopping");
                    echo "<div class='invoice-container'>";
                    echo "<div class='body-container'>";
                    echo "<div class='invoice-header'>
                    <h4></h4>
                    </div>";

                    if (isset($_POST['id_invoice'])) {
                        $customerDetailsQuery = mysqli_query($cx, "SELECT * FROM receiver 
                        INNER JOIN receiver_detail ON receiver.RecvID =  receiver_detail.RecvID WHERE receiver_detail.CusID = '$uid' AND receiver_detail.RecvID = '$recv_id'");
                        $customerDetails = mysqli_fetch_array($customerDetailsQuery);
                        $customerId = $uid;
                        $invoiceId = $_POST['id_invoice'];

                        echo "<div class='customer-details'>
                       
                        <h4><strong>สรุปการจัดส่ง</strong></h4>
                    
                        <div class='text-container'>
                            <p><strong>Shipping Address</strong></p>
                            <p><strong>Name:</strong> {$customerDetails['RecvFName']} {$customerDetails['RecvLName']} </p>
                            <p><strong>Tel:</strong> {$customerDetails['Tel']}</p>
                            <div class='address-container'>
                                <p><strong>Address:</strong> {$customerDetails['Address']}</p>
                            </div>
                        </div>
                        
                        <h4><strong>สรุปการคำสั่งซื้อ</strong></h4>";      
                        $totalPriceAllItems = 0;
                        $invoiceDetailsQuery = mysqli_query($cx, "SELECT * , product.ProName FROM invoice_detail
                        INNER JOIN product ON product.ProID =  invoice_detail.ProID 
                        INNER JOIN invoice ON invoice.InvID =  invoice_detail.InvID 
                        WHERE invoice.InvID  = '$inv'");
                     
                        while ($invoiceDetails = mysqli_fetch_array($invoiceDetailsQuery)) {
                            $totalPrice = $invoiceDetails['PricePerUnit'] * $invoiceDetails['Qty'];
                            $totalPriceAllItems += $totalPrice;

                            echo "<div class='text-container'>
                                <p><strong {$invoiceDetails['ProName']}</strong></p>
                                <p><strong>฿{$invoiceDetails['PricePerUnit']}</strong>     จำนวน {$invoiceDetails['Qty']} ชิ้น</p>
                            </div>";                         
                        }
                        
                        $tax = $totalPriceAllItems * 0.07;
                        $totalAmount = $tax + $totalPriceAllItems;
                       
                        echo "
                       <h4>สรุปยอดชำระเงิน</h4>
                            <div class='text-container'>
                                <p><strong>VAT:</strong>{$tax}</p>
                                <p><strong>ราคารวม:</strong>{$totalPriceAllItems}</p>                     
                            </div>
                        <hr> 
                            <div class='text-container'>
                                <p><strong>ยอดชำระสุทธิ:</strong>{$totalAmount}</p>                              
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
        // function submit() {
        //     document.querySelector('form').submit();
        // }
    </script>
</body>

</html>