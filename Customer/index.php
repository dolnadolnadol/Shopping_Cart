<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
            padding-top: 5rem;
        }

        .product-container {
            /* background-color: red; */
            display: flex;
            flex-wrap: wrap;
            /* justify-content: space-between; */
            margin-top: 35px;
        }

        .product-card {
            width: 15%;
            margin-bottom: 20px;
            margin-right: 5rem;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            border-radius: 8px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
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
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .buy-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <?php include('./component/session.php');?>
    <?php include('./component/accessNavbar.php');?>
    <div class="container">
        <div class="product-container">
            <?php
                include_once '../dbConfig.php';
                $cur = "SELECT * FROM product";
                $msresults = mysqli_query($conn, $cur);
                if(mysqli_num_rows($msresults) > 0){
                    while ($row = mysqli_fetch_array($msresults)) {
                    echo "<div class='product-card'>";
                    include('./component/showPhotos.php');
                                echo "
                                <p class='product-name'>{$row['ProductName']}</p>
                                <p class='product-price'>ราคา {$row['Price']} บาท</p>
                                <form method='post' action='detailProduct.php'>
                                    <input type='hidden' name='id_product' value='{$row['proId']}'>
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
    </div>
</body>
</html>
