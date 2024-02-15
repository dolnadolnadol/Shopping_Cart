<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: rgba(255, 99, 71, 0.8);
            padding: 10px;
            position: fixed;
            width: 100%;
            top: 0;
            /* display: flex; */
            /* justify-content: space-between; */
            /* Added to align items to the left and right */
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            /* Add this line to make the navigation items flex containers */
        }

        li {
            width: 150px;
            height: 50px;
        }

        li:last-child {
            margin-left: auto;
        }

        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: background-color 0.25s ease;
            position: relative;
        }

        li a:hover {
            background-color: rgba(255, 99, 71, 1);
        }

        li a.active {
            background-color: #d9534f;
        }

        li a.active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background-color: white;
        }

        body {
            margin-top: 50px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Change this line to align items to the end */
            width: 80%;
            margin-right: 60px;
        }
        .nav-right a{
            padding-left: 40px;
            padding-right: 40px;
        }

        .cart-icon {
            width: 50%;
            height: 50%;
        }
    </style>
</head>

<body>
    <nav>
        <ul>
            <li><a class="a" href="../dashboard/dashboard.php">Dashboard</a></li>
            <li><a class="a" href="../customer/customer_index.php">Customer</a></li>
            <li><a class="a" href="../stock/stock_index.php">Stock</a></li>
            <li><a class="a" href="../invoice/invoice_index.php">Invoice</a></li>
            <li><a class="a" href="../order/order_index.php">Order</a></li>
            <li class="nav-right"><a class="a" href="../../Customer/login.php">Logout</a></li>
        </ul>
    </nav>
</body>

</html>
