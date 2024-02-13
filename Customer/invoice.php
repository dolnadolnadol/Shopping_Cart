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
    </style>
</head>
<body>

<?php include('backButton.php')?>

<?php    
    session_start();
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $user = $_SESSION['username'];

    echo "<div class='invoice-container'>";
    echo "<div class='invoice-header'>
            <h1>Invoice</h1>
          </div>";

    $customerDetailsQuery = mysqli_query($cx, "SELECT * FROM customer WHERE Username = '$user'");  
    $customerDetails = mysqli_fetch_array($customerDetailsQuery);

    if(isset($_POST['id_invoice'])){
        $customerId = $customerDetails['CusID'];
        $invoiceId = $_POST['id_invoice'];
        $invoiceQuery = mysqli_query($cx, "SELECT invoice.*, invoice_detail.*, product.*
                                           FROM invoice
                                           INNER JOIN invoice_detail ON invoice.InvID = invoice_detail.InvID
                                           INNER JOIN Product ON invoice_detail.ProID = Product.ProID
                                           WHERE invoice.CusID = '$customerId' AND invoice_detail.InvID = '$invoiceId'");
        
        $totalPriceAllItems = 0; 

        while ($row = mysqli_fetch_array($invoiceQuery)) {
            $totalPrice = $row['PricePerUnit'] * $row['Qty'];
            $totalPriceAllItems += $totalPrice;

            echo "<div class='invoice-details'>
                    <p>Invoice #: {$row['InvID']}</p>
                    <p>Date: {$row['Period']}</p>
                </div>";

            echo "<div class='customer-details'>
                    <h2>Customer Details</h2>
                    <p>Name: {$customerDetails['CusName']}</p>
                    <p>Email: {$customerDetails['Tel']}</p>
                    <p>Address: {$customerDetails['Address']}</p>
                  </div>";

            echo "<table>
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
                    <td>{$row['ProName']}</td>
                    <td>{$row['Qty']}</td>
                    <td>{$row['PricePerUnit']} ฿</td>
                    <td>$totalPrice</td>
                  </tr>
                </tbody>
              </table>";
        }

        $tax = $totalPriceAllItems * 0.07;
        $totalAmount = $tax + $totalPriceAllItems;
 
        echo "<div class='invoice-total'>
                <p>SubTotal: $totalPriceAllItems ฿</p>
                <p>Tax: $tax ฿</p>
                <p>Discount: 0.00 ฿</p>
                <p>Total: $totalAmount ฿</p>
              </div>";

        echo "<div class='buy-button-container'>
                <form method='post' action='accessOrder.php'>
                    <input type='hidden' name='id_invoice' value='".$invoiceId."'>  
                    <input type='hidden' name='id_customer' value='". $customerId ."'> 
                    <input class='buy-button' type='submit' value='ชำระเงิน'>           
                </form>
              </div>";
        
        echo "</div>";
    }
?>

</body>
</html>
