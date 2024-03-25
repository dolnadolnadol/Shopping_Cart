<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
}
    .invoice-container {
        max-width: 800px;
        margin: 50px auto;
        border: 2px solid #4CAF50;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .invoice-header {
        text-align: center;
        color: #4CAF50;
    }

    .invoice-details,
    .customer-details,
    .invoice-table,
    .invoice-total {
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

    .invoice-total {
        text-align: right;
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
        color: #4CAF50;
    }

    h1, h2 {
        color: #333;
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
<?php include('backButton.php')?>
<div class="invoice-container">
    <div class="invoice-header">
        <h1>Invoice</h1>
    </div>
    <div class="invoice-details">
        <p>Invoice #: <?php echo $invoiceNumber; ?></p>
        <p>Date: <?php echo $invoiceDate; ?></p>
    </div>
    <div class="customer-details">
        <h2>Customer Details</h2>
        <p>Name: <?php echo $customerName; ?></p>
        <p>Email: <?php echo $customerEmail; ?></p>
    </div>
    <div class="invoice-table">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoiceItems as $item): ?>
                    <tr>
                        <td><?php echo $item['product']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo '$' . number_format($item['unitPrice'], 2); ?></td>
                        <td><?php echo '$' . number_format($item['total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="invoice-total">
        <p>Total: <?php echo '$' . number_format($invoiceTotal, 2); ?></p>
    </div>
</div>
<?php    
    session_start();
    include_once '../dbConfig.php'; 
    $user = $_SESSION['username'];
    $uid_query = mysqli_query($conn, "SELECT * FROM customer WHERE Username = '$user'");  
    $row = mysqli_fetch_array($uid_query);

    echo "<div class='invoice-container'>'>
        <div class='invoice-header'>
        <h1>Invoice</h1>
        </div>";

    echo "<div class='customer-details'>
        <h2>Customer Details</h2>
        <p>Name: {$row['CusName']}</p>
        <p>Email: {$row['Tel']}</p>
        <p>Address: {$row['Address']}</p>
        </div>";

    $uid_results = $row['CusID'];
    echo $_POST['id_invoice'];

    if(isset($_POST['id_invoice'])){
        $uid = $uid_results;
        $InvID = $_POST['id_invoice'];
        $msresults = mysqli_query($conn, "SELECT invoice.*, invoice_detail.* , product.*
                                        FROM invoice
                                        INNER JOIN invoice_detail ON invoice.InvID = invoice_detail.InvID
                                        INNER JOIN Product ON invoice_detail.proId = Product.proId
                                        WHERE invoice.CusID = '$uid_results' AND invoice_detail.InvID = '$InvID'");
        
        $totalPriceAllItems = 0; 
        

        while ($row = mysqli_fetch_array($msresults)) {
            $totalPrice = $row['Price'] * $row['Qty'];
            $totalPriceAllItems += $totalPrice;
            echo "<div class='invoice-details'>
                    <p>Invoice #: {$row['InvID']}</p>
                    <p>Date: {$row['Period']}</p>
                    </div>"; 

            echo " <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>";

            echo "<tr>
                    <td>{$row['ProductName']}</td>
                    <td>{$row['Qty']}</td>
                    <td>{$row['Price']} ฿</td>
                    <td>$totalPrice</td>
                </tr>
            </tbody>
            </table>";
         
        }
        $Tax = $totalPriceAllItems * 0.07;
        $Total = $Tax + $totalPriceAllItems;

       
        echo "<div class='invoice-total'>
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
