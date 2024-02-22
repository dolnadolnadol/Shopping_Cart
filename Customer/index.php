<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        center {
            display: flex;
            justify-content: center;
        }

        .product-container {
            width: 100%;
            max-width: 1550px;
            display: flex;
            flex-wrap: wrap;
            margin-top: 35px;
        }

        .product-card {
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            flex: 0 1 calc(18% - 20px);
            padding: 10px;
            text-align: center;
            background-color: #fff;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-image {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-price {
            color: #27ae60;
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <?php include('./component/session.php');?>
    <?php include('./component/accessNavbar.php');?>
    <center>
        <div class="product-container">
            <?php
                $cx =  mysqli_connect("localhost", "root", "", "shopping");
                $cur = "SELECT * FROM product";
                $msresults = mysqli_query($cx, $cur);
                if(mysqli_num_rows($msresults) > 0){
                    while ($row = mysqli_fetch_array($msresults)) {
                    echo "<div class='product-card'>
                                <img class='product-image' src='./image/cart.png'>
                                <p class='product-name'>{$row['ProName']}</p>
                                <p class='product-price'>ราคา {$row['PricePerUnit']}</p>
                                <form method='post' action='detailProduct.php'>
                                    <input type='hidden' name='id_product' value='{$row['ProID']}'>
                                    <input class='buy-button' type='submit' value='ซื้อสินค้า'>
                                </form>
                        </div>";
                    }
                }
                else {
                    echo "<center><h1>ไม่มีสินค้า</h1></center>";
                }
            ?>
        </div>
    </center>
</body>
</html>

