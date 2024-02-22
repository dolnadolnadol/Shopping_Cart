<?php include('./component/session.php'); ?>
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
    </style>
</head>

<body>
    <form id="profileForm" method="post" action="accessInvoice.php">
        <?php
        if (isset($_POST['id_customer'])) {
            $uid = $_POST['id_customer'];

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
                <h2>Checkout</h2>
            </div>

            <div class="checkout-steps">
                <div class="checkout-step active" onclick="goToStep(1)">Step 1: Shipping</div>
                <div class="checkout-step" onclick="goToStep(2)">Step 2: Payment</div>
                <div class="checkout-step" onclick="goToStep(3)">Step 3: Success</div>
            </div>

            <div id="shippingForm" class="checkout-form" style="display: block;">
                <!-- Shipping form content -->
                <div class="form-group">
                    <label for="fullname">First Name:</label>
                    <input type="text" id="fullname" name="fname" value="<?php echo $row['RecvFName'] ?? ''; ?>" required>
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lname" value="<?php echo $row['RecvLName'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="tel">Tel:<span>*</span></label>
                    <input type="tel" name="tel" value="<?php echo $row['Tel'] ?? ''; ?>" required>
                    <label for="address">Address:</label>
                    <textarea name="address" id="address" rows="3"><?php echo $row['Address'] ?? ''; ?></textarea>
                </div>

                <button class="checkout-button" onclick="goToStep(2)">Next: Payment</button>
            </div>

            <div id="paymentForm" class="checkout-form" style="display: none;">
                <!-- Payment form content -->
                <div class="form-group">
                    <label for="creditCard">Credit Card Number:</label>
                    <input type="text" id="creditCard" name="creditCard" required>
                </div>

                <div class="form-group">
                    <label for="expiryDate">Expiry Date:</label>
                    <input type="text" id="expiryDate" name="expiryDate" required>
                </div>

                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" required>
                </div>

                <!-- ตรวจสอบว่าเป็น Guest หรือ User และแสดงปุ่ม 'ชำระเงิน' ตามเงื่อนไข -->
                <?php if (isset($_SESSION['cart'])) : ?>
                    <input type='hidden' name='cart' value='<?php echo json_encode($_SESSION['cart']); ?>'>
                    <input class='buy-button' type='submit' value='ชำระเงิน'>
                <?php elseif (isset($_POST['id_customer'])) : ?>
                    <input type='hidden' name='id_customer' value='<?php echo $uid; ?>'>
                    <input class='buy-button' type='submit' value='ชำระเงิน'>
                <?php else : ?>
                    <p>Oops Something went wrong</p>
                    <?php echo 'header("Location: ./cart.php")'; ?>
                <?php endif; ?>
            </div>

            <div id="successForm" class="checkout-form" style="display: none;">
                <!-- Success form content -->
                <h3>Order Placed Successfully!</h3>
                <p>Your order has been confirmed. Thank you for shopping with us.</p>
                <input class='buy-button' type='submit' value='ชำระเงิน'>
            </div>
        </div>
    </form>
    <script>
        function goToStep(step) {
            // Hide all forms
            document.getElementById('shippingForm').style.display = 'none';
            document.getElementById('paymentForm').style.display = 'none';
            document.getElementById('successForm').style.display = 'none';

            // Show the selected form
            document.getElementById(getFormId(step)).style.display = 'block';

            // Update the active step indicator
            updateActiveStep(step);
        }

        function updateActiveStep(step) {
            // Remove 'active' class from all steps
            document.querySelectorAll('.checkout-step').forEach(function(element) {
                element.classList.remove('active');
                element.style.cursor = 'pointer'; // Set cursor to pointer for all steps
            });

            // Add 'active' class to the selected step
            document.querySelector('.checkout-step:nth-child(' + step + ')').classList.add('active');

            // Set cursor to not-allowed for incomplete steps
            if (step > 1 && !isStep1Completed()) {
                document.querySelector('.checkout-step:nth-child(1)').style.cursor = 'not-allowed';
            }

            if (step > 2 && !isStep2Completed()) {
                document.querySelector('.checkout-step:nth-child(2)').style.cursor = 'not-allowed';
            }
        }

        function isStep1Completed() {
            // Add logic to check if Step 1 is completed
            var fullName = document.getElementById('fullname').value;
            var address = document.getElementById('address').value;
            return fullName && address;
        }

        function isStep2Completed() {
            // Add logic to check if Step 2 is completed
            var creditCard = document.getElementById('creditCard').value;
            var expiryDate = document.getElementById('expiryDate').value;
            var cvv = document.getElementById('cvv').value;
            return creditCard && expiryDate && cvv;
        }

        function getFormId(step) {
            // Map step number to form ID
            return (step === 1) ? 'shippingForm' :
                (step === 2) ? 'paymentForm' :
                (step === 3) ? 'successForm' :
                '';
        }
    </script>
</body>

</html>