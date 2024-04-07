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
    <title>Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: black;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            margin-top: 100px;
            width: 75%;
            background-color: white;
        }

        .dashboard-heading {
            color: black;
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-top: 20px;
            margin-left: 40px;
            margin-right: 40px;
        }

        .data-container {
            width: 97%;
            margin: auto;
            background-color: white;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        
        .data-card {
            width: 60%;
            background-color: #fff;
            color: #000;
            padding: 20px;
            margin-right: 10px;
        }
        .data-card2 {
            width: 40%;
            background-color: #fff;
            color: #000;
            padding: 20px;
            margin-left: 10px;
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
            background-color: #c0c0c0;
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
    <?php   include('../navbar/navbarAdmin.php');
            include_once '../../dbConfig.php';        
    ?>
    <div class="container">
        <div class="dashboard-heading">
            <h1 style="margin-bottom: 0px">Summary Report</h1>
            <h2 style="margin-bottom: 0px">E-Commerce Co., Ltd.</h2>
            <h2 style="margin-bottom: 0px; font-weight: normal;"><?php echo date("F Y"); ?></h2>
        </div>
        <div class="data-container">
            <div class="data-card" id='card-1'>
                <h2 id='PQ'>Product Summary</h2>
                <table>                     
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity sold</th>
                        <th>Sales</th>
                        <th>Profit</th>
                    </tr>               
                    <?php
                    $cur = "SELECT product.proId, product.ProductName, product.Price, product.cost, SUM(ordervalue.Qty) AS TotalQty
                    FROM product
                    INNER JOIN ordervalue ON ordervalue.ProId = product.proId
                    GROUP BY product.proId";
                    $msresults = mysqli_query($conn, $cur);
                    $TotalSales = 0;
                    $TotalProfit = 0;
                    if (mysqli_num_rows($msresults) > 0) {
                        while ($row = mysqli_fetch_array($msresults)) {
                            $Sales = (double)$row['Price'] * (double)$row['TotalQty'];
                            $Cost = (double)$row['cost'] * (double)$row['TotalQty'];
                            $Profit = $Sales - $Cost;
                            $TotalSales += $Sales;
                            $TotalProfit += $Profit;
                            echo "<tr>";
                            echo "<td>{$row['proId']}</td>";
                            echo "<td>{$row['ProductName']}</td>";
                            echo "<td>฿" . $row['Price'] . "</td>";
                            echo "<td>{$row['TotalQty']}</td>";
                            echo "<td>฿" . $Sales . "</td>";
                            echo "<td>฿" . $Profit . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right">฿<?php echo number_format($TotalSales, 2); ?></td>
                        <td class="text-right">฿<?php echo number_format($TotalProfit, 2); ?></td>
                    </tr>
                </table>
            </div>
            <div class="data-card2" id='card-2'>
                <h2 id='PQ'>Summary of product types</h2>
                <table>
                    <tr>
                        <th>Type ID</th>
                        <th>Sales</th>
                        <th>Profit</th>
                    </tr>
                    <?php
                    $cur = "SELECT product.proId, product.typeId, product.Price, product.cost, SUM(ordervalue.Qty) AS TotalQty
                            FROM product
                            INNER JOIN ordervalue ON ordervalue.ProId = product.proId
                            GROUP BY product.proId, product.typeId
                            ORDER BY product.typeId ASC";
                    $msresults = mysqli_query($conn, $cur);
                    $TotalSales = 0;
                    $TotalProfit = 0;
                    $Sales = 0;
                    $Profit = 0;
                    $previousTypeId = null;
                    if (mysqli_num_rows($msresults) > 0) {
                        while ($row = mysqli_fetch_array($msresults)) {
                            if ($previousTypeId == null) {
                                $Sales += (double)$row['Price'] * (double)$row['TotalQty'];
                                $Cost = (double)$row['cost'] * (double)$row['TotalQty'];
                                $Profit += $Sales - $Cost;
                                $TotalSales += $Sales;
                                $TotalProfit += $Profit;
                                $previousTypeId = $row['typeId'];
                            }
                            else if ($row['typeId'] !== $previousTypeId) {
                                echo "<tr>";
                                echo "<td>{$previousTypeId}</td>";
                                echo "<td>฿" . $Sales . "</td>";
                                echo "<td>฿" . $Profit . "</td>";
                                echo "</tr>";
                                $Sales = 0;
                                $Profit = 0;
                                $Sales += (double)$row['Price'] * (double)$row['TotalQty'];
                                $Cost = (double)$row['cost'] * (double)$row['TotalQty'];
                                $Profit += $Sales - $Cost;
                                $TotalSales += $Sales;
                                $TotalProfit += $Profit;
                                $previousTypeId = $row['typeId'];
                            } else {
                                $Sales += (double)$row['Price'] * (double)$row['TotalQty'];
                                $Cost = (double)$row['cost'] * (double)$row['TotalQty'];
                                $Profit += $Sales - $Cost;
                                $TotalSales = $Sales;
                                $TotalProfit = $Profit;
                                $previousTypeId = $row['typeId'];
                            }
                        } if ($Sales != 0 && $Profit != 0 && $previousTypeId !== null) {
                            echo "<tr>";
                            echo "<td>{$previousTypeId}</td>";
                            echo "<td>฿" . $Sales . "</td>";
                            echo "<td>฿" . $Profit . "</td>";
                            echo "</tr>";
                            $Sales = 0;
                            $Profit = 0;
                        }
                    }
                    ?>
                    <tr>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right">฿<?php echo number_format($TotalSales, 2); ?></td>
                        <td class="text-right">฿<?php echo number_format($TotalProfit, 2); ?></td>
                    </tr>
                </table>
                <h2 id='PQ'>Customer Summary</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Price Paid</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>Male</td>
                        <td>฿5000</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right">฿5000</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
