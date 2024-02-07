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

    </style>
</head>

<body>
    <a href='index.php'> <- กลับไปหน้าหลัก </a>
    <div class="container">
        <h1>Your Cart</h1>
        <?php
 
            $totalPriceAllItems = 0;
            $cx =  mysqli_connect("localhost", "root", "", "shopping");

            foreach ($_SESSION['cart'] as $productId => $amount) {
                $cur = "SELECT * FROM Product";
                $msresults = mysqli_query($cx, $cur);
                if (mysqli_num_rows($msresults) > 0) {
                    while ($row = mysqli_fetch_array($msresults)) {
                      
                        $totalPrice = $row['PricePerUnit'] * $amount;
                        $totalPriceAllItems += $totalPrice;

                        echo '<div class="product">';
                        echo '<img src="cart.png" alt="Product">';
                        echo '<div class="product-details">';
                        echo '<p>' . $row['ProName'] . '</p>';
                        echo '<p>Price: ' . $row['PricePerUnit'] . '</p>';
                        echo '<p>Quantity: ' . $amount . '</p>';
                        echo '</div>';
                        
                        // Display total price to the right
                        echo '<div class="total-price">';
                        echo '<p>Price: ' . $totalPrice . '</p>';
                        echo '</div>';

                        // Form to remove item from cart
                        echo '<form method="post" action="accessCart.php">';
                        echo '<input type="hidden" name="deleteID" value="' . $row['ProID'] . '">';
                        echo '<input type="submit" class="remove-btn" value="Remove">';
                        echo '</form>';
                        echo '</div>';
                    }
                }
            }
            echo '<div class="total">';
            echo '<p>Total Price for All Items: ' . $totalPriceAllItems . '</p>';
            echo '</div>';

        ?>
        <div classname='buy-button'>
            <form method='post' action='invoice.php' >
                <input type='hidden' name='id_product' value='{$row['ProID']}'>
                <input class='buy-button' type='submit' value='ซื้อสินค้า'>
            </form>
        </div>    
    </div>
</body>
</html>
