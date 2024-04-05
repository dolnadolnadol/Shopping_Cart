<?php
    session_start();
    if($_SESSION['auth'] !== 'super-admin') {
        header("Location: ../notHavePage.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: black;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
        }

        .container {
            max-width: 100%;
            height: 90vh;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
        }

        .navbar {
            /* Add your styles for the navbar here */
            margin-top: 20px;
        }

        .dashboard-heading {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
        }

        .data-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .data-card {
            flex-grow: 1;
            flex-basis: calc(33.33% - 20px); /* 33.33% for each card with a margin of 20px */
            margin: 0 10px 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .data-card:hover {
            transform: scale(1.0);
        }

        .data-card h2,
        .data-card h3,
        .data-card p {
            margin: 0;
            color: #343a40;
        }
        #card-1 {
            background-color: rgb(129, 125, 155);
        }
        #card-2 {
            background-color: rgb(110, 137, 146);
        }
        #card-3 {
            background-color: rgb(152, 142, 118);
        }
        #card-4 {
            background-color: rgb(119, 133, 155);
        }
        #card-5 {
            background-color: rgb(105, 147, 136);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
        }
        #PQ {
            color:rgb(224, 207, 255) ;
        }
        #PR {
            color:rgb(130, 266, 211) ;
        }
        #BSP {
            color:rgb(250, 190, 70) ;
        }
        #Re {
            color: rgb(175, 247, 255);
        }

        #card-4 h1 {
            text-align: center;
            padding: auto;
            margin-top: 35px;
            margin-bottom: 35px;
        }

    </style>
</head>
<body> 
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>  
    <div class="container"> 
        <h1 class="dashboard-heading">Dashboard</h1>
        <div class="data-container">
            <div class="data-card" id='card-1'>
                <h2 id='PQ'>Product Quantity</h2>
                <?php 
                    include_once '../../dbConfig.php'; 
                    $ProductQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_products FROM product");  
                    $ProductDetails = mysqli_fetch_assoc($ProductQuery);
                    echo "<h3>Total Products: " . $ProductDetails['total_products'] . "</h3>";
                ?>
              
                <table>
                    <tr style="color:black;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price Per Unit</th>
                        <th>Total Price</th>
                        <th>Remaining Quantity</th>
                    </tr>
                    <?php 
                        $ProductQuery = mysqli_query($conn, "SELECT * FROM product");  
                        while($row = mysqli_fetch_assoc($ProductQuery)) {
                            $total = (double)$row['Price'] * (double)$row['Qty'];
                            echo "<tr>";
                            echo "<td>" . $row['proId'] . "</td>";
                            echo "<td>" . $row['ProductName'] . "</td>";
                            echo "<td>" . $row['Price'] . "</td>";
                            echo "<td>" . $total . "</td>";
                            echo "<td>" . $row['Qty'] . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="data-card" id='card-2'>
                <h2 id='PR'>Products with Reservations</h2>
                <?php 
                    $OnHandQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_OnHand FROM product WHERE OnHand != '0'"); 
                    $OnHandDetails = mysqli_fetch_assoc($OnHandQuery);
                    echo "<h3>Total Products on hands: " . $OnHandDetails['total_OnHand'] . "</h3>";
                ?>
         
                <table>
                    <tr style="color:black;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Total Price</th>
                        <th>Reserved Quantity</th>
                    </tr>
                    <?php 
                        $ProductQuery = mysqli_query($conn, "SELECT * FROM product WHERE OnHand != '0'");  
                        while($row = mysqli_fetch_assoc($ProductQuery)) {
                            $total = (double)$row['Price'] * (double)$row['onHand'];
                            echo "<tr>";
                            echo "<td>" . $row['proId'] . "</td>";
                            echo "<td>" . $row['ProductName'] . "</td>";
              
                            echo "<td>" . $total . "</td>";
                            echo "<td>" . $row['onHand'] . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="data-card" id='card-3'>
                <h2 id='BSP'>Best Selling Products</h2>
                <table>
                    <tr style="color:black;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Sold Quantity</th>
                    </tr>
                    <?php
                        $bestSell_Query = mysqli_query($conn, "SELECT product.proId, product.ProductName, SUM(ordervalue.Qty) AS TotalQty
                        FROM product
                        INNER JOIN ordervalue ON product.proId = ordervalue.ProId
                        GROUP BY product.proId
                        ORDER BY TotalQty DESC");
                        while($row = mysqli_fetch_assoc($bestSell_Query)) {
                            echo "<tr>";
                            echo "<td>" . $row['proId'] . "</td>";
                            echo "<td>" . $row['ProductName'] . "</td>";
                            echo "<td>" . $row['TotalQty'] . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="data-card" id='card-4'>
                <h2 id='Re'>Revenue</h2>
                <?php 
                    $income_Query = mysqli_query($conn, "SELECT * FROM product INNER JOIN ordervalue ON product.ProID = ordervalue.ProID");
                    (double)$total_income = 0;
                    while($row = mysqli_fetch_assoc($income_Query)) {
                        $total_income += (double)$row['Price'] * (double)$row['Qty'];
                    }
                    echo "<h1>Total Income: ฿" . number_format($total_income, 2) . "</h1>";
                ?>
            </div>
        </div>
    </div>
</body>
</html>
