<?php
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'true') {
        // Redirect to the login page
        header("Location: login.php");
        session_destroy();
        exit();
    }
?>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #ef476f;
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
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }


        .buy-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            margin-right: 0;
            margin-top: 20px; 
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
                var pricePerUnit = parseFloat(product.querySelector('.product-details p:nth-child(2)').textContent.split(' ')[1]);
                var quantity = parseInt(product.querySelector('.amount').value);
                var totalPrice = pricePerUnit * quantity;

                var totalPriceElement = product.querySelector('.total-price p');
                totalPriceElement.textContent = 'Price: ' + totalPrice;
                totalPriceElement.parentElement.id = 'total-price-' + index;

                total += totalPrice;
            });

            document.querySelector('.total p').textContent = 'Total Price for All Items: ' + total;
        }

        function incrementAmount(index) {
            var amountInput = document.querySelector('.amount[data-index="' + index + '"]');
            amountInput.value = parseInt(amountInput.value) + 1;
            updateTotalPrice();
        }

        function decrementAmount(index) {
            var amountInput = document.querySelector('.amount[data-index="' + index + '"]');
            if (parseInt(amountInput.value) > 1) {
                amountInput.value = parseInt(amountInput.value) - 1;
                updateTotalPrice();
            }
        }
    </script>
</head>

<body>
    <a href='index.php'> <- กลับไปหน้าหลัก </a>
    <div class="container">
        <h1>Your Cart</h1>
        <?php
 
            $totalPriceAllItems = 0;
            $cx =  mysqli_connect("localhost", "root", "", "shopping");



            //Find User ID
            $user = $_SESSION['username'];
            $uid_query = mysqli_query($cx, "SELECT CusID FROM customer WHERE Username = '$user'");
            $uid_row = mysqli_fetch_assoc($uid_query);
            $uid_results = $uid_row['CusID'];


            //Find product.ProID , product.ProName  ,product.PricePerUnit , Qty
            $cur = "SELECT product.ProID , product.ProName  ,product.PricePerUnit , Qty  FROM cart
            INNER JOIN product ON cart.ProID = product.ProID";
            $msresults = mysqli_query($cx, $cur);

            if (mysqli_num_rows($msresults) > 0) {
                $index = 0;
                while ($row = mysqli_fetch_array($msresults)) {
                    $totalPrice = $row['PricePerUnit'] * $row['Qty'];
                    $totalPriceAllItems += $totalPrice;


                    echo '<div class="product">';
                    echo '<img src="cart.png" alt="Product">';
                    echo '<div class="product-details">';
                    echo '<p>' . $row['ProName'] . '</p>';
                    echo '<p>Price: ' . $row['PricePerUnit'] . '</p>';
                    echo "<div class='button-increase'>
                        <button type='button' id='change-amount' class='change-amount' onclick='decrementAmount($index)'>-</button>    
                        <input type='text' name='amount' id='amount' class='amount' data-index='$index' value='" . $row['Qty'] . "' readonly>
                        <button type='button' id='change-amount' class='change-amount' onclick='incrementAmount($index)'>+</button>
                    </div>";
                    echo '</div>';

                    echo '<div class="total-price" id="total-price-' . $index . '">';
                    echo '<p>Price: ' . $totalPrice . '</p>';
                    echo '</div>';

                    echo '<form method="post" action="accessCart.php">';
                    echo '<input type="hidden" name="CusID" value="' . $uid_results . '">';
                    echo '<input type="hidden" name="deleteID" value="' . $row['ProID'] . '">';
                    echo '<input type="submit" class="remove-btn" value="Remove">';
                    echo '</form>';
                    echo '</div>';

                    $index++;
                }
            }

            echo '<div class="total">';
            echo '<p>Total Price for All Items: ' . $totalPriceAllItems . '</p>';
            echo '</div>';

            echo '<div classname="buy-button">
                <form method="post" action="accessInvoice.php" >
                    <input type="hidden" name="id_customer" value="'.$uid_results.'">
                    <input class="buy-button" type="submit" value="Buy Products">
                </form>
            </div>';        
        ?>
    </div>
</body>
</html>
