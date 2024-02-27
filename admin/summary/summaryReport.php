<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }

        .navbar {
            margin-top: 20px;
        }

        .dashboard-heading {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
        }

        .data-container {
            width: 60%;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }

        .data-card {
            flex-grow: 1;
            flex-basis: calc(50% - 20px);
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
    <h1 class="dashboard-heading">Summary Report</h1>
    <div class="data-container">
        <div class="data-card" id='card-1'>
            <h2 id='PQ'>Product Summary</h2>
            <?php 
                $cx =  mysqli_connect("localhost", "root", "", "shopping");
                $ProductQuery = mysqli_query($cx, "SELECT COUNT(*) AS total_products FROM receive_detail");  
                $ProductDetails = mysqli_fetch_assoc($ProductQuery);
                echo "<h3>Total Products sold: " . $ProductDetails['total_products'] . "</h3>";
            ?>
          
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price Per Unit</th>
                    <th>Total Unit Sold</th>
                    <th>Total Price</th>
                </tr>
                <?php
                    $bestSell_Query = mysqli_query($cx, "SELECT product.ProID, product.ProName, product.PricePerUnit, SUM(receive_detail.Qty) AS TotalQty
                    FROM product
                    INNER JOIN receive_detail ON product.ProID = receive_detail.ProID
                    GROUP BY product.ProID");
                    while($row = mysqli_fetch_assoc($bestSell_Query)) {
                        $totalSum = $row['PricePerUnit']*$row['TotalQty'];
                        echo "<tr>";
                        echo "<td>" . $row['ProID'] . "</td>";
                        echo "<td>" . $row['ProName'] . "</td>";
                        echo "<td>" . $row['PricePerUnit'] . "</td>";
                        echo "<td>" . $row['TotalQty'] . "</td>";
                        echo "<td>" . $totalSum . "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            <h1 id='Re'>Revenue</h1>
                <?php 
                    $income_Query = mysqli_query($cx, "SELECT * FROM product INNER JOIN receive_detail ON product.ProID = receive_detail.ProID");
                    (double)$total_income = 0;
                    while($row = mysqli_fetch_assoc($income_Query)) {
                        $total_income += (double)$row['PricePerUnit'] * (double)$row['Qty'];
                    }
                    echo "<h2>Total Income: ฿" . number_format($total_income, 2) . "</h2>";
                ?>
        </div>
    </div>
</body>
</html>
