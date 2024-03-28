<?php include('./component/session.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .product {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .product img {
            width: 80px;
            height: auto;
            margin-right: 10px;
        }

        .product-details {
            flex: 1;
        }

        .remove-btn {
            color: #ef476f;
            cursor: pointer;
            width: 25px;
            height: 25px; 
            margin-left: 15px;
        }
        

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }


        .buy-button-container {
            text-align: right;
            margin-top: 20px;
        }

        .buy-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
        }

        .buy-button:hover {
            background-color: #2980b9;
        }
        .button-increase {
            border: 1px solid #f5f6f6;
            padding: 4px;
            display: inline-block;
            border-radius: 3px;
        }

        #amount {
            border: none;
            outline: none;
            background: none;
            text-align: center;
            width: 50px;
            height: 25px;
        }

        #change-amount {
            background-color: #f3f6f9;
            border: 1px solid #f5f6f6;
            width: 1.4em;
            border-radius: .25rem;
            font-weight: 300;
            height: 100%;
            font-size: 1.4em;
            color: #878a99;
        }

        #change-amount:hover {
            background-color: #f3f0f0;
        }

        #change-amount.clicked {
            background-color: #030000;
        }

    </style>
    <script>
        function updateTotalPrice() {
            var total = 0;
            var products = document.querySelectorAll('.product');
            products.forEach(function (product, index) {
                var Price = parseFloat(product.querySelector('.product-details p:nth-child(2)').textContent.split(' ')[1]);
                var quantity = parseInt(product.querySelector('.amount').value);
                var totalPrice = Price * quantity;

                var totalPriceElement = product.querySelector('.total-price p');
                totalPriceElement.textContent = 'Price: ' + totalPrice;
                totalPriceElement.parentElement.id = 'total-price-' + index;

                total += totalPrice;
            });

            document.querySelector('.total p').textContent = 'Total Price : ' + total;
        }
        
        function incrementAmount(index, productId) {
            var amountInput = document.querySelector('.amount[data-index="' + index + '"]');
            var newQuantity = parseInt(amountInput.value) + 1;
            amountInput.value = newQuantity;
            updateTotalPrice();
            updateCart(productId, newQuantity);
        }

        function decrementAmount(index, productId) {
            var amountInput = document.querySelector('.amount[data-index="' + index + '"]');
            var newQuantity = parseInt(amountInput.value) - 1;
            if (newQuantity > 0) {
                amountInput.value = newQuantity;
                updateTotalPrice();
                updateCart(productId, newQuantity);
            }
        }

        

        function updateCart(productId, newQuantity) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateCart.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Handle successful response
                        console.log('Cart updated successfully');
                    } else {
                        // Handle error
                        console.error('Error updating cart');
                    }
                }
            };

             // Append the data to the request body
            var data = 'productId=' + encodeURIComponent(productId) + '&newQuantity=' + encodeURIComponent(newQuantity);
            xhr.send(data);
        }

    </script>
</head>
<!-- <?php include('./component/backButton.php')?> -->
<body>
    <?php include('./component/accessNavbar.php')?>
    <div class="container">
        <h1>Your Cart</h1>
        <?php
            $totalPriceAllItems = 0;
            $index = 0;
            include_once '../dbConfig.php'; 

            /* สำหรับ User */
            if (isset($_SESSION['id_username']) && isset($_SESSION['status'])) {
                $uid = $_SESSION['id_username'];
                //Find product.proId , product.ProductName  ,product.Price , Qty
                $cur = "SELECT *, cart.Qty AS cartq FROM cart
                INNER JOIN product ON cart.ProId = product.proId where cart.cusId = '$uid'" ;
                $msresults = mysqli_query($conn, $cur);
           

                if (mysqli_num_rows($msresults) > 0) {
                    while ($row = mysqli_fetch_array($msresults)) {
                        $totalPrice = $row['Price'] * $row['cartq'];
                        $totalPriceAllItems += $totalPrice;

                        echo '<div class="product">';
                        echo '<img src="./image/cart.png" alt="Product">';
                        echo '<div class="product-details">';
                        echo '<p>' . $row['ProductName'] . '</p>';
                        echo '<p>Price: ' . $row['Price'] . '</p>';
                        echo "<div class='button-increase'>
                            <button type='button' id='change-amount' class='change-amount' onclick='decrementAmount($index, {$row['proId']})'>-</button>
                            <input type='text' name='amount' id='amount' class='amount' data-index='$index' value='" . $row['cartq'] . "' readonly>
                            <button type='button' id='change-amount' class='change-amount' onclick='incrementAmount($index, {$row['proId']})'>+</button>
                        </div>";
                        echo '</div>';

                        echo '<div class="total-price" id="total-price-' . $index . '">';
                        echo '<p>Price: ' . $totalPrice . '</p>';
                        echo '</div>';

                        echo '<form method="post" action="accessCart.php">';
                        echo '<input type="hidden" name="CusID" value="' . $uid . '">';
                        echo '<input type="hidden" name="deleteID" value="' . $row['proId'] . '">';
                        echo '<input type="image" alt="delete" class="remove-btn" style="width:" src="./image/minus-circle.png">';

                        echo '</form>';
                        echo '</div>';

                        $index++;
                    }
                }
                echo '<div class="total">';
                echo '<p>Total Price : ' . $totalPriceAllItems . '</p>';
                echo "<hr>";
                echo '</div>';

                echo "<div class='buy-button-container'>
                <form method='post' action='addressForm.php'>
                    <input type='hidden' name='id_customer' value='$uid'>";
                        if ($totalPriceAllItems <= 0){
                            echo "<input class='buy-button' style='background-color:gray;' type='submit' value='ซื้อสินค้า' disabled>";
                        }else{
                            echo "<input class='buy-button' type='submit' value='ซื้อสินค้า'>";
                        }
                    "
                </form>
                </div>";
            }

            /* สำหรับ Guest */
            else if(isset($_SESSION['cart'])){
                foreach ($_SESSION['cart'] as $product_id => $product) {
                    $cur = "SELECT product.proId, product.ProductName, product.Price FROM product WHERE proId = '$product_id'";
                    $msresults = mysqli_query($conn, $cur);
                    $row = mysqli_fetch_array($msresults);
                
                    $totalPrice = $row['Price'] * $product['quantity'];
                    $totalPriceAllItems += $totalPrice;
            
                    echo '<div class="product">';
                    echo '<img src="./image/cart.png" alt="Product">';
                    echo '<div class="product-details">';
                    echo '<p>' . $row['ProductName'] . '</p>';
                    echo '<p>Price: ' . $row['Price'] . '</p>';
                
                    /* Check if 'quantity' key exists in the $product array */
                    if (isset($product['quantity'])) {
                        echo "<div class='button-increase'>
                                <button type='button' id='change-amount' class='change-amount' onclick='decrementAmount($index, {$row['proId']})'>-</button>
                                <input type='text' name='amount' id='amount' class='amount' data-index='$index' value='" . $product['quantity'] . "' readonly>
                                <button type='button' id='change-amount' class='change-amount' onclick='incrementAmount($index, {$row['proId']})'>+</button>
                            </div>";
                    }
                    
                    echo '</div>';
                
                    echo '<div class="total-price" id="total-price-' . $index . '">';
                    echo '<p>Price: ' . $totalPrice . '</p>';
                    echo '</div>';
                
                    echo '<form method="post" action="accessCart.php">';
                    echo '<input type="hidden" name="deleteID" value="' . $row['proId'] . '">';
                    echo '<input type="image" alt="delete" class="remove-btn" style="width:" src="./image/minus-circle.png">';
                    echo '</form>';
                    echo '</div>';
                
                    $index++;
                }
            
                // Move this bracket to the correct position
                echo '<div class="total">';
                echo '<p>Total Price : ' . $totalPriceAllItems . '</p>';
                echo "<hr>";
                echo '</div>';

                echo "<div class='buy-button-container'>
                    <form method='post' action='addressForm.php'>
                        <input type='hidden' name='cart' value='" . json_encode($_SESSION['cart']) . "'>
                        ";
                        if ($totalPriceAllItems <= 0){
                            echo "<input class='buy-button' style='background-color:gray;' type='submit' value='ซื้อสินค้า' disabled>";
                        }else{
                            echo "<input class='buy-button' type='submit' value='ซื้อสินค้า'>";
                        }
                    "</form>
                </div>";
            }
             
        ?>
    </div>
  
</body>
</html>
