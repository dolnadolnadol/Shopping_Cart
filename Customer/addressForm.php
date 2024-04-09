<?php include('./component/backButton.php');
include('./component/session.php');
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
            display: flex;
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
            display: none;
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
            margin-bottom: 1rem;
        }

        Textarea {
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
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
    </style>
</head>

<body>
    <form id="profileForm" method="post" action="addOrder.php">
        <?php
        if (isset($_SESSION['id_username'])) {
            $uid = $_SESSION['id_username'];

            include_once '../dbConfig.php';
            $query_address = "select * from address where address.CusID = '$uid' LIMIT 1";
            $result_address = mysqli_query($conn, $query_address);
            if (mysqli_num_rows($result_address) > 0) {
                // Fetch a single row from the result set
                $row = mysqli_fetch_assoc($result_address);
            }
        }
        ?>
        <div class="checkout-container">
            <div style="width:100%;">
                <div class="checkout-header">
                    <h2>Checkout</h2>
                </div>

                <div class="checkout-steps">
                    <div class="checkout-step active">Step 1: Shipping</div>
                    <div class="checkout-step">Step 2: Payment</div>
                    <div class="checkout-step">Step 3: Success</div>
                </div>

                <div id="shippingForm" class="checkout-form" style="display: block;">
                    <!-- Shipping form content -->
                    <div class="form-group">
                        <!-- <div style="display:none"> -->
                        <label for="fullname">First Name</label>
                        <input type="text" id="fname" name="fname" value="<?php echo isset($row['fname']) ? $row['fname'] : ''; ?>" readonly required>
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lname" name="lname" value="<?php echo isset($row['lname']) ? $row['lname'] : ''; ?>" readonly required>
                        <label for="tel">Tel</label>
                        <input required type="tel" id="tel" name="tel" value="<?php echo isset($row['tel']) ? $row['tel'] : ''; ?>" readonly>

                        <input type="hidden" name="changeinfo" id="changeinfo">
                        <input type="hidden" name="addrId" value="<?php echo $row['AddrId'] ?? ''; ?>">
                        <button type="button" onclick="editInfo()">edit info</button>
                        <button type="button" id="saveInfo" onclick="saveInfobutton()" style="display:none;">save</button>
                        <!-- </div> -->
                        <p>
                            Address For Song Kong AHHH
                        </p>

                        <!-- <select>
                            <?php
                            // $query_address = "select * from address where address.CusID = '$uid'";
                            // $result_address = mysqli_query($conn, $query_address);
                            // if (mysqli_num_rows($result_address) > 0) {
                            //     // Fetch a single row from the result set
                            //     $row = mysqli_fetch_array($result_address);
                            //     while($row){
                            //         echo "<option value='$row'> "  . $row['fname'] ." ". $row['lname'] ." ". $row['tel'] ." ". $row['Address'] ." ". $row['City'] ." ". $row['Province'] ." ". $row['PostalCode'] ." ".  "</option>";
                            //         $row = mysqli_fetch_array($result_address);
                            //     }
                            // }
                            ?>
                        </select>
                        <button> edit </button> -->

                        <!-- <div style="display:none"> -->
                        <label for="address">Address</label>
                        <input required type="text" name="address" id="address" value="<?php echo isset($row['Address']) ? $row['Address'] : ''; ?>" readonly>
                        <label for="province">Province</label>
                        <input required type="text" name="province" id="province" value="<?php echo isset($row['Province']) ? $row['Province'] : ''; ?>" readonly>
                        <label for="city">City</label>
                        <input required type="text" name="city" id="city" value="<?php echo isset($row['City']) ? $row['City'] : ''; ?>" readonly>
                        <label for="postalcode">PostalCode</label>
                        <input required type="text" name="postalcode" id="postalcode" value="<?php echo isset($row['PostalCode']) ? $row['PostalCode'] : ''; ?>" readonly>

                        <input type="hidden" name="changeaddress" id="changeaddress">
                        <button type="button" onclick="editAddress()">edit address</button>
                        <button type="button" id="saveaddr" onclick="saveAddressbutton()" style="display:none;">save</button>
                        <!-- </div> -->
                    </div>

                    <!-- <button class="checkout-button" onclick="submit()">Next to Payment</button> -->
                    <input type='submit' value="Place your order">

                    <!-- ตรวจสอบว่าเป็น Guest หรือ User และแสดงปุ่ม 'ชำระเงิน' ตามเงื่อนไข -->
                    <?php if (isset($_SESSION['cart'])) : ?>
                        <input type='hidden' name='cart' value='<?php echo json_encode($_SESSION['cart']); ?>'>
                    <?php elseif (isset($_SESSION['id_username'])) : ?>
                        <input type='hidden' name='id_customer' value='<?php echo $uid; ?>'>
                    <?php else : ?>
                        <p>Oops Something went wrong</p>
                        <?php echo 'header("Location: ./cart.php")'; ?>
                    <?php endif; ?>
                </div>

            </div>

            <!-- <div>
                <div style="border:1px solid;">
                    รวมจ้ารวม
                </div>
                <div class="checkout-sidebar">
                    Sidebar content
                    <?php
                    $check_query = mysqli_query($conn, "SELECT product.proId AS proId, cart.Qty AS Qty, product.price AS price FROM cart 
                    INNER JOIN product ON cart.proId = product.proId WHERE CusID = '$cusID'");
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
                        $invoiceId = $_POST['id_invoice'];

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
        </div> -->
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (
                "<?php echo isset($row['Address']) ? $row['Address'] : ''; ?>" === '' ||
                "<?php echo isset($row['Province']) ? $row['Province'] : ''; ?>" === '' ||
                "<?php echo isset($row['City']) ? $row['City'] : ''; ?>" === '' ||
                "<?php echo isset($row['PostalCode']) ? $row['PostalCode'] : ''; ?>" === ''
            ) {
                editAddress();
            }
            if (
                "<?php echo isset($row['fname']) ? $row['fname'] : ''; ?>" === '' ||
                "<?php echo isset($row['lname']) ? $row['lname'] : ''; ?>" === '' ||
                "<?php echo isset($row['tel']) ? $row['tel'] : ''; ?>" === ''
            ) {
                editInfo();
            }
        });

        function editInfo() {
            document.getElementById("saveInfo").style.display = "block";
            document.getElementById("fname").readOnly = false;
            document.getElementById("lname").readOnly = false;
            document.getElementById("tel").readOnly = false;

            // document.getElementById("fname").addEventListener('input', function(event) {
            //     const newValue = event.target.value;
            //     console.log("New value of fname:", document.getElementById("fname").value);
            // });
        }

        function saveInfobutton() {
            document.getElementById("saveInfo").style.display = "none";
            document.getElementById("fname").readOnly = true;
            document.getElementById("lname").readOnly = true;
            document.getElementById("tel").readOnly = true;
            document.getElementById("changeinfo").value ="value";
        }

        function editAddress() {
            document.getElementById("saveaddr").style.display = "block";
            document.getElementById("address").readOnly = false;
            document.getElementById("province").readOnly = false;
            document.getElementById("city").readOnly = false;
            document.getElementById("postalcode").readOnly = false;
        }

        function saveAddressbutton() {
            document.getElementById("saveaddr").style.display = "none";
            document.getElementById("address").readOnly = true;
            document.getElementById("province").readOnly = true;
            document.getElementById("city").readOnly = true;
            document.getElementById("postalcode").readOnly = true;
            document.getElementById("changeaddress").value ="value";
        }
    </script>
</body>

</html>