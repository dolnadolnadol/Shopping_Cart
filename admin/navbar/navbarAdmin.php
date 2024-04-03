<?php
    if(!isset($_SESSION['auth']) || ($_SESSION['auth'] !== 'product-admin' && $_SESSION['auth'] !== 'permissions-admin' && $_SESSION['auth'] !== 'super-admin')) {
        header("Location: ../notHavePage.php");
        exit;
    }
?>

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
            background-color: rgba(27, 27, 27, 0.8);;
            padding: 10px;
            position: fixed;
            width: 100%;
            top: 0;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            width: 100%;
        }

        li {
            width: 150px;
            height: 50px;
        }

        li:last-child {
            margin-left: auto;
        }

        li a {
            display: none;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: background-color 0.25s ease;
            position: relative;
        }

        li a:hover {
            background-color: rgba(50, 50, 50, 0.8);
            border-radius:10%;
        }

        li a:focus {
            background-color: rgba(70, 70, 70, 0.8);
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
            justify-content: flex-end;
            width: 20%;
            margin-right: 5%;
            margin-left: 65%;
        }
        .nav-right a {
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
            <!-- <div class="nav-left"> -->
                <li><a class="a" id="stock" href="../stock/stock_index.php">Stock</a></li>
                <li><a class="a" id="invoice" href="../invoice/invoice_index.php">Invoice</a></li>
                <li><a class="a" id="order" href="../order/order_index.php">Order</a></li>
                <li><a class="a" id="customer" href="../customer/customer_index.php">Customer</a></li>
                <li><a class="a" id="dashboard" href="../dashboard/dashboard.php">Dashboard</a></li>
                <li><a class="a" id="summary" href="../summary/summaryReport.php">Summary</a></li>
                <li><a class="a" id="log" href="../log/log_index.php?page=1">AccessLog</a></li>
            <!-- </div> -->
            <div class="nav-right">
                <li><a class="a" id="home" href="../../Customer/index.php">Home</a></li>
                <li><a class="a" id="logout" href="../logoutProcess.php">Logout</a></li>
            </div> 
        </ul>
    </nav>
</body>
</html>
<script>
    <?php if ($_SESSION['auth'] == 'product-admin') : ?>
        document.getElementById("stock").style.display = "inline-block";
        document.getElementById("invoice").style.display = "inline-block";
        document.getElementById("order").style.display = "inline-block";
    <?php elseif ($_SESSION['auth'] == 'permissions-admin') : ?>
        document.getElementById("customer").style.display = "inline-block";
    <?php elseif ($_SESSION['auth'] == 'super-admin') : ?>
        document.getElementById("dashboard").style.display = "inline-block";
        document.getElementById("summary").style.display = "inline-block";
        document.getElementById("log").style.display = "inline-block";
    <?php endif; ?>
    document.getElementById("home").style.display = "inline-block";
    document.getElementById("logout").style.display = "inline-block";
</script>
