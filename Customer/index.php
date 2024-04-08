<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .navbar {
            background-color: #fff;
            padding: 10px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid;
        }

        .navbar-brand {
            margin-left: 20px;
            font-weight: bold;
            font-size: 1.5rem;
            color: #333;
            text-decoration: none;
        }

        .navbar-nav {
            margin-right: 20px;
            list-style-type: none;
            display: flex;
        }

        .nav-item {
            margin-right: 15px;
        }

        .nav-link {
            color: #333;
            text-decoration: none;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #007bff;
        }

        .container {
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
        }

        /* Product Styles */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .product-card {
            width: 250px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.3s;
            margin-bottom: 30px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-image {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-name {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .product-price {
            color: #27ae60;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .buy-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .buy-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <?php include('./component/session.php'); ?>
    <?php include('./component/accessNavbar.php'); ?>
    <div class="container">
        <div class="header">
            <h1>Welcome to PAMULAMO SHOP</h1>
            <p>This is our New Product</p>
        </div>
        <div class="product-container">
            <?php include './product/getAllProduct.php' ?>
        </div>
        <?php
        include_once('./typeproduct/getmaxtypeId.php');
        include_once('./product/getProductBytypeId.php');
        for ($typeid = 1; $typeid <= $max_id; $typeid++) {
            getProductByTypeId($typeid);
        }        
        ?>
    </div>
</body>

</html>