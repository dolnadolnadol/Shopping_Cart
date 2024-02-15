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
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }

        .container {
            max-width: 100%;
            height: 90vh;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
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
            flex: 1;
            margin: 0 10px 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .data-card:hover {
            transform: scale(1.05);
        }

        .data-card h2,
        .data-card h3,
        .data-card p {
            margin: 0;
            color: #343a40;
        }
        #card-1 {
            background-color: rgb(229, 225, 255);
        }
        #card-2 {
            background-color: rgb(210, 237, 246);
        }
        #card-3 {
            background-color: rgb(252, 242, 218);
        }
        #card-4 {
            background-color: rgb(219, 233, 255);
        }
        #card-5 {
            background-color: rgb(205, 247, 236);
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
            color:rgb(124, 107, 255) ;
        }
        #PR {
            color:rgb(30, 166, 211) ;
        }
        #BSP {
            color:rgb(250, 190, 70) ;
        }
        #Re {
            color: rgb(75, 147, 255);
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
                    $cx =  mysqli_connect("localhost", "root", "", "shopping");
                    $ProductQuery = mysqli_query($cx, "SELECT COUNT(*) AS total_products FROM product");  
                    $ProductDetails = mysqli_fetch_assoc($ProductQuery);
                    echo "<h3>Total Products: " . $ProductDetails['total_products'] . "</h3>";
                ?>
              
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price Per Unit</th>
                        <th>Total Price</th>
                        <th>Remaining Quantity</th>
                    </tr>
                    <?php 
                        $ProductQuery = mysqli_query($cx, "SELECT * FROM product");  
                        while($row = mysqli_fetch_assoc($ProductQuery)) {
                            $total = (double)$row['PricePerUnit'] * (double)$row['StockQty'];
                            echo "<tr>";
                            echo "<td>" . $row['ProID'] . "</td>";
                            echo "<td>" . $row['ProName'] . "</td>";
                            echo "<td>" . $row['PricePerUnit'] . "</td>";
                            echo "<td>" . $total . "</td>";
                            echo "<td>" . $row['StockQty'] . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="data-card" id='card-2'>
                <h2 id='PR'>Products with Reservations</h2>
                <?php 
                    $OnhandsQuery = mysqli_query($cx, "SELECT COUNT(*) AS total_onHands FROM product WHERE OnHands != '0'"); 
                    $OnhandsDetails = mysqli_fetch_assoc($OnhandsQuery);
                    echo "<h3>Total Products on hands: " . $OnhandsDetails['total_onHands'] . "</h3>";
                ?>
         
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Total Price</th>
                        <th>Reserved Quantity</th>
                    </tr>
                    <?php 
                        $ProductQuery = mysqli_query($cx, "SELECT * FROM product WHERE OnHands != '0'");  
                        while($row = mysqli_fetch_assoc($ProductQuery)) {
                            $total = (double)$row['PricePerUnit'] * (double)$row['OnHands'];
                            echo "<tr>";
                            echo "<td>" . $row['ProID'] . "</td>";
                            echo "<td>" . $row['ProName'] . "</td>";
              
                            echo "<td>" . $total . "</td>";
                            echo "<td>" . $row['OnHands'] . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="data-card" id='card-3'>
                <h2 id='BSP'>Best Selling Products</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Sold Quantity</th>
                    </tr>
                    <?php
                        $bestSell_Query = mysqli_query($cx, "SELECT product.ProID, product.ProName, SUM(receive_detail.Qty) AS TotalQty
                        FROM product
                        INNER JOIN receive_detail ON product.ProID = receive_detail.ProID
                        GROUP BY product.ProID
                        ORDER BY TotalQty DESC");
                        while($row = mysqli_fetch_assoc($bestSell_Query)) {
                            echo "<tr>";
                            echo "<td>" . $row['ProID'] . "</td>";
                            echo "<td>" . $row['ProName'] . "</td>";
                            echo "<td>" . $row['TotalQty'] . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <div class="data-card" id='card-4'>
                <h2 id='Re'>Revenue</h2>
                <?php 
                    $income_Query = mysqli_query($cx, "SELECT * FROM product INNER JOIN receive_detail ON product.ProID = receive_detail.ProID");
                    (double)$total_income = 0;
                    while($row = mysqli_fetch_assoc($income_Query)) {
                        $total_income += (double)$row['PricePerUnit'] * (double)$row['Qty'];
                    }
                    echo "<h3>Total Income: ฿" . number_format($total_income, 2) . "</h3>";
                ?>
            
                <!-- <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price Per Unit</th>
                        <th>Total Price</th>
                        <th>Sold Quantity</th>
                    </tr>
                    <?php
                        $income_Query = mysqli_query($cx, "SELECT * FROM product INNER JOIN receive_detail ON product.ProID = receive_detail.ProID");
                        while($row = mysqli_fetch_assoc($income_Query)) {
                            $total_pro = (double)$row['PricePerUnit'] * (double)$row['Qty'];
                            echo "<tr>";
                            echo "<td>" . $row['ProID'] . "</td>";
                            echo "<td>" . $row['ProName'] . "</td>";
                            echo "<td>" . number_format($row['PricePerUnit'], 2) . "</td>";
                            echo "<td>" . number_format($total_pro, 2) . "</td>";
                            echo "<td>" . $row['Qty'] . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table> -->
            </div>
        </div>
    </div>
</body>
</html>
