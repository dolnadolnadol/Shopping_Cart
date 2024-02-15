<?php include('./component/session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .order-container {
            max-width: 1150px;
            margin: 100px auto 50px auto;
            border: 2px solid #4CAF50;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            text-align: center;
            color: #4CAF50;
        }

        .order-details,
        .customer-details,
        .order-table,
        .order-total {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }

        .order-total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }

        h1, h2 {
            color: #333;
        }

        .buy-button-container {
            text-align: right;
        }

        .buy-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
        }

        .buy-button:hover {
            background-color: #2980b9;
        }

        .container_order {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }


        .item_order {
            width: 400px;
        }

        .item_order2 {
            align-self: flex-end;
            width: 280px;
            text-align: right;
        }
        
        center {
            margin-top: 100px;
        }

        #Status {
            font-weight:800;
            font-size: large;
        }

    </style>
</head>
<body>
<?php include('./component/accessNavbar.php')?>
<!-- <?php include('./component/backButton.php')?> -->

<?php    
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $uid = $_SESSION['id_username'];

    echo "<div class='order-container'>";
    echo "<div class='order-header'>
            <h1>Order details</h1>
          </div>";

    $customerDetailsQuery = mysqli_query($cx, "SELECT * FROM customer WHERE CusID = '$uid'");  
    $customerDetails = mysqli_fetch_array($customerDetailsQuery);

    echo "<div class='container_order'>
    <div class='item_order'>
        <h3>Company Name </h3>
        <p>Name: Fastwork ck🤔</p>
        <p>Address: ประเทศลาดกระบัง</p>
        <h3>Ship to </h3>
        <p>Name: {$customerDetails['CusName']}</p>
        <p>Email: {$customerDetails['Tel']}</p>
        <p>Address: {$customerDetails['Address']}</p>
    </div>";

    if(isset($_POST['id_order'])){
        $customerId = $customerDetails['CusID'];
        $RecId = $_POST['id_order'];
        $orderQuery = mysqli_query($cx, "SELECT Product.*, receive_detail.*  , receive.*
                    FROM receive_detail
                    INNER JOIN receive ON receive.RecID = receive_detail.RecID
                    INNER JOIN Product ON Product.ProID = receive_detail.ProID
                    WHERE receive_detail.RecID = '$RecId '");
                    
        $totalPriceAllItems = 0; 
        $detailsDisplayed = false; 

        while ($row = mysqli_fetch_array($orderQuery)) {
            $totalPrice = $row['PricePerUnit'] * $row['Qty'];
            $totalPriceAllItems += $totalPrice;

            if (!$detailsDisplayed) {
                echo "<div class='item_order2'>
                    <p id='Status'>Status : {$row['Status']}</p>
                    <p>Order #: {$row['RecID']}</p>
                    <p>Order Date: {$row['OrderDate']}</p>
                    <p>Delivery Date : {$row['DeliveryDate']}</p>
                    </div>
                </div>";
                
                echo "<table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>";

                $detailsDisplayed = true; 
            }

            echo "<tr>
                    <td>{$row['ProName']}</td>
                    <td>{$row['Qty']}</td>
                    <td>{$row['PricePerUnit']} ฿</td>
                    <td>$totalPrice</td>
                  </tr>";
        }

        echo "</table>";
        $tax = $totalPriceAllItems * 0.07;
        $totalAmount = $tax + $totalPriceAllItems;
 
        echo "<div class='order-total'>
                <p>SubTotal: $totalPriceAllItems ฿</p>
                <p>Tax: $tax ฿</p>
                <p>Discount: 0.00 ฿</p>
                <p>Total: $totalAmount ฿</p>
              </div>";  
        echo "</div>";
    }
    mysqli_close($cx);
?>
</body>
</html>