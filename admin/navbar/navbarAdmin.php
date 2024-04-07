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
            gap: 72%;
        }

        li {
            width: 150px;
            height: 50px;
            display: flex;
        }

        li a {
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            transition: background-color 0.25s ease;
            position: relative;
            border-radius: 10px;
            width: 40px;
        }

        li a:hover {
            background-color: rgba(50, 50, 50, 0.8);
            border-radius:10px;
        }

        li a:focus {
            background-color: rgba(70, 70, 70, 0.8);
        }

        li a.active::after {
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
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

        .left {
            width: 5%;
            display: flex;
            flex-direction: row;
            gap:20%;
        }

        .right {
            width: 20%;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
        }

        .nav-text {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

    </style>
</head>
        
<body>
<nav>
        <ul>
            <div class="left">
                <li><a class="a" id="stock" href="../stock/stock_index.php"><span class="nav-text">Stock</span></a></li>
                <li><a class="a" id="invoice" href="../invoice/invoice_index.php"><span class="nav-text">Invoice</span></a></li>
                <li><a class="a" id="order" href="../order/order_index.php"><span class="nav-text">Order</span></a></li>
                <li><a class="a" id="customer" href="../customer/customer_index.php"><span class="nav-text">Customer</span></a></li>
                <li><a class="a" id="admin" href="../admin/admin_index.php"><span class="nav-text">Admin</span></a></li>
                <li><a class="a" id="log" href="../log/log_index.php?page=1"><span class="nav-text">AccessLog</span></a></li>
                <li><a class="a" id="dashboard" href="../dashboard/dashboard.php"><span class="nav-text">Dashboard</span></a></li>
                <li><a class="a" id="summary" href="../summary/summaryReport.php"><span class="nav-text">Summary</span></a></li>
            </div>
            <div class="right">
                <li><a class="a" id="home" href="../../Customer/index.php"><span class="nav-text">Home</span></a></li>
                <li><a class="a" id="logout" href="../logoutProcess.php"><span class="nav-text">Logout</span></a></li>
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
        document.getElementById("admin").style.display = "inline-block";
        // document.getElementById("log").style.display = "inline-block";
    <?php elseif ($_SESSION['auth'] == 'super-admin') : ?>
        document.getElementById("dashboard").style.display = "inline-block";
        document.getElementById("summary").style.display = "inline-block";
    <?php endif; ?>
    document.getElementById("home").style.display = "inline-block";
    document.getElementById("logout").style.display = "inline-block";
</script>
