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
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .container {
            margin-top: 100px;
            margin-bottom: 100px;
            width: 380mm;
            height: 537.43mm;
            background-color: white;
            overflow: hidden;
        }

        .dashboard-heading {
            color: black;
            text-align: center;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            margin-left: 40px;
            margin-right: 40px;
        }

        .dashboard-inheading {
            color: black;
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-top: 20px;
        }

        .dashboard-inheading2 {
            color: black;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 30px;
            width: 60%;
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

        .convertToPDF {
            background-color: red;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }
        .convertToPDF:hover {
            background-color: #aa0000;
        }

    </style>
</head>
<body> 
    <?php   include('../navbar/navbarAdmin.php');
            include_once '../../dbConfig.php';        
    ?>
    <div class="container">
        <div class="dashboard-heading">
            <div class="dashboard-inheading2">
                <button id="convertToPDF" class="convertToPDF">Convert to PDF</button>
            </div>
            <div class="dashboard-inheading">
                <h1 style="margin-bottom: 0px">Summary Report</h1>
                <h2 style="margin-bottom: 0px">E-Commerce Co., Ltd.</h2>
                <h2 style="margin-bottom: 0px; font-weight: normal;"><?php echo date("F Y"); ?></h2>
            </div>
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
                    INNER JOIN orderkey ON orderkey.orderId = ordervalue.orderId
                    INNER JOIN receipt ON receipt.orderId = ordervalue.orderId
                    WHERE orderkey.PaymentStatus = 'Success' 
                    AND DATE_FORMAT(receipt.timestamp, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
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
                        <th>Type</th>
                        <th>Sales</th>
                        <th>Profit</th>
                    </tr>
                    <?php
                    $cur = "SELECT product.proId, product.typeId, product.Price, product.cost, product_type.typeName, SUM(ordervalue.Qty) AS TotalQty
                            FROM product
                            INNER JOIN ordervalue ON ordervalue.ProId = product.proId
                            INNER JOIN orderkey ON orderkey.orderId = ordervalue.orderId
                            INNER JOIN receipt ON receipt.orderId = ordervalue.orderId
                            INNER JOIN product_type ON product_type.typeId = product.typeId
                            WHERE orderkey.PaymentStatus = 'Success' 
                            AND DATE_FORMAT(receipt.timestamp, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
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
                                $previousTypeId = $row['typeName'];
                            }
                            if ($row['typeName'] !== $previousTypeId) {
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
                                $previousTypeId = $row['typeName'];
                            } else {
                                $Sales += (double)$row['Price'] * (double)$row['TotalQty'];
                                $Cost = (double)$row['cost'] * (double)$row['TotalQty'];
                                $Profit += $Sales - $Cost;
                                $TotalSales = $Sales;
                                $TotalProfit = $Profit;
                                $previousTypeId = $row['typeName'];
                            }
                        } if ($Sales != 0 && $Profit != 0 && $previousTypeId !== null) {
                            echo "<tr>";
                            echo "<td>{$previousTypeId}</td>";
                            echo "<td>฿" . $Sales . "</td>";
                            echo "<td>฿" . $Profit . "</td>";
                            echo "</tr>";
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
                    <?php
                    $cur = "SELECT customer.CusID,
                            customer.fname, customer.lname,
                            customer.Sex,
                            SUM(product.Price * ordervalue.Qty) AS TotalPricePaid
                            FROM customer
                            INNER JOIN orderkey ON customer.CusID = orderkey.cusId
                            INNER JOIN ordervalue ON orderkey.orderId = ordervalue.orderId
                            INNER JOIN product ON ordervalue.ProId = product.proId
                            INNER JOIN receipt ON receipt.orderId = ordervalue.orderId
                            WHERE customer.deleteStatus = '0' AND orderkey.PaymentStatus = 'Success'
                            AND DATE_FORMAT(receipt.timestamp, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                            GROUP BY customer.CusID, customer.fname, customer.lname, customer.Sex";
                    $msresults = mysqli_query($conn, $cur);
                    $TotalPricePaid = 0;
                    if (mysqli_num_rows($msresults) > 0) {
                        while ($row = mysqli_fetch_array($msresults)) {
                            echo "<tr>";
                            echo "<td>{$row['CusID']}</td>";
                            echo "<td>{$row['fname']} {$row['lname']}</td>";
                            echo "<td>{$row['Sex']}</td>";
                            echo "<td>฿{$row['TotalPricePaid']}</td>";
                            echo "</tr>";
                            $TotalPricePaid += $row['TotalPricePaid'];
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right">฿<?php echo number_format($TotalPricePaid, 2); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById('convertToPDF').addEventListener('click', function() {
            document.getElementById("convertToPDF").style.display = "none";
            const element = document.querySelector('.container');
            html2canvas(element).then(function(canvas) {
                const imageData = canvas.toDataURL('image/png');
                var doc = new jspdf.jsPDF();
                doc.addImage(imageData, 'PNG', 0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight());
                doc.save('Summary_Report.pdf');
                document.getElementById("convertToPDF").style.display = "block";
            });
        });
    </script>
</body>
</html>
