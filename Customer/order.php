<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #ef476f;
        }

        .product {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }

        .customer-details {
            margin-top: 20px;
            text-align: right;
            border-bottom: 1px solid #ddd;
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
    </style>
</head>
<body>
<?php 
    session_start();
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $user = $_SESSION['username'];
    $uid_query = mysqli_query($cx, "SELECT * FROM customer WHERE Username = '$user'");  
    $row = mysqli_fetch_array($uid_query);
    echo "<div class='container'>
            <h1>Order</h1>
            <div class='customer-details'>
                <p>Customer Name: {$row['CusName']}</p>
                <p>Tel: {$row['Tel']}</p>
                <p>Address: {$row['Address']}</p>
            </div>";

    $uid_results = $row['CusID'];
    echo $_POST['id_invoice'];

    if(isset($_POST['id_invoice'])){
        $uid = $uid_results;
        $InvID = $_POST['id_invoice'];
       
        $msresults = mysqli_query($cx, "SELECT Product.*, invoice.InvID, invoice.CusID, invoice_detail.Qty
        FROM 
            invoice
        INNER JOIN 
            invoice_detail ON invoice.InvID = invoice_detail.InvID
        INNER JOIN 
            Product ON invoice_detail.ProID = Product.ProID   
        WHERE 
            invoice.CusID = '$uid' AND invoice.InvID = '$InvID'");

        $totalPriceAllItems = 0; 
        
        while ($row = mysqli_fetch_array($msresults)) {
            $totalPrice = $row['PricePerUnit'] * $row['Qty'];
            $totalPriceAllItems += $totalPrice;
            
            echo "<div class='product'>
                    <span>{$row['ProName']}</span>
                    <span>{$row['PricePerUnit']} ฿</span>
                    <span>Quantity: {$row['Qty']}</span>
                    <span>Total: $totalPrice ฿</span>
                </div>";
        }
        $Tax = $totalPriceAllItems * 0.07;
        $Total = $Tax + $totalPriceAllItems;

        echo "<div class='total'>
                <p>SubTotal : $totalPriceAllItems ฿</p>
                <p>Tax : $Tax ฿</p>
                <p>Discount : 0.00 ฿</p>
                <p>Total : $Total ฿</p>
            </div>
            <form method='post' action='accessOrder.php' classname='buy-button'>
                <input type='hidden' name='id_invoice' value='".$InvID."'>  
                <input type='hidden' name='id_customer' value='". $uid ."'> 
                <input class='buy-button' type='submit' value='ชำระเงิน'>           
            </form>
        </div>";

        
    }
?>
</body>
</html>
