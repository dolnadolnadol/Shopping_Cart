<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            margin: 20px;
            background: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
        }

        .invoice {
            padding: 30px;
            width: 380mm;
            height: 537.43mm;
            background-color: white;
            margin: 0px auto;
        }

        .invoice h2 {
            margin-top: 0px;
            line-height: 0.8em;
        }

        .invoice .small {
            font-weight: 300;
        }

        .invoice hr {
            margin-top: 10px;
            border-color: #ddd;
        }

        .invoice .table tr.line {
            border-bottom: 1px solid #ccc;
        }

        .invoice .table td {
            border: none;
        }

        .invoice .identity {
            margin-top: 10px;
            font-size: 1.1em;
            font-weight: 300;
        }

        .invoice .identity strong {
            font-weight: 600;
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
            margin-right: 40px;
            cursor: pointer;
            border-radius: 8px;
            width: 20%;
            height: 20%;
        }

        .convertToPDF:hover {
            background-color: #aa0000;
        }

        .row {
            display: flex;
            flex-direction: row;
        }

    </style>
</head>
<body>
    <?php 
        include_once '../../dbConfig.php';
        $invid = $_POST['id_order'];
        $cur = "SELECT * FROM invoice 
        INNER JOIN orderdelivery ON orderdelivery.DeliId = invoice.DeliId
        INNER JOIN address ON address.AddrId = orderdelivery.addrId
        INNER JOIN customer ON customer.CusID = address.CusId 
        WHERE invId = '$invid'";
        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="invoice">
                    <div class="grid-body">
                        <div class="invoice-title">
                            <div class="row">
                                <div class="col-xs-12">
                                <h2>Invoice<br>
                                <span class="small">Invoice ID : <?php echo $row['invId']; ?></span></h2>
                                </div>
                                <button id="convertToPDF" class="convertToPDF">Convert to PDF</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-6">
                                <address>
                                    <strong>Billed To:</strong><br>
                                    <?php 
                                        echo $row['fname']." ". $row['lname'] . "<br>";
                                        echo $row['Address'] . "<br>";
                                        echo $row['City'] . "<br>";
                                        echo $row['Province'] ." ".$row['PostalCode']. "<br>";
                                        echo "Tel: " . $row['Tel'] . "<br>";
                                    ?>
                                </address>
                            </div>
                            <div class="col-xs-6 text-right">
                                <address>
                                    <strong>Shipped To:</strong><br>
                                    <?php 
                                        echo $row['fname']." ".$row['lname']."<br>";
                                        echo $row['Address']."<br>";
                                        echo $row['City']."<br>";
                                        echo $row['Province']." ".$row['PostalCode']."<br>";
                                        echo "Tel: ".$row['Tel']."<br>";
                                    ?>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <!-- <address>
                                    <strong>Payment Method:</strong><br>
                                    Visa ending **** 1234<br>
                                    <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="4f27612a232e26212a0f28222e2623612c2022">[email&#160;protected]</a><br>
                                </address> -->
                            </div>
                            <div class="col-xs-6 text-right">
                                <address>
                                    <strong>Order Date:</strong><br>
                                    <?php 
                                        echo $row['timestamp']."<br>";
                                    ?>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>ORDER SUMMARY</h3>
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="line">
                                            <td><strong>ID</strong></td>
                                            <td><strong>Product</strong></td>
                                            <td class="text-right"><strong>Quantity</strong></td>
                                            <td class="text-right"><strong>Price</strong></td>
                                            <td class="text-right"><strong>Total Price</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $cur = "SELECT *, ordervalue.Qty AS QtyO
                                                    FROM invoice
                                                    INNER JOIN ordervalue ON ordervalue.orderId = invoice.orderId
                                                    INNER JOIN product ON product.proId = ordervalue.ProId
                                                    WHERE invId = '$invid'";
                                            $msresults = mysqli_query($conn, $cur);
                                            $total_amount = 0;
                                            $tax_percentage = 7;
                                            while ($row = mysqli_fetch_assoc($msresults)) {
                                                $total_amount += (double)$row['Price'] * (double)$row['QtyO'];
                                                $total = (double)$row['Price'] * (double)$row['QtyO'];
                                                echo "<tr>";
                                                echo "<td>" . $row['proId'] . "</td>";
                                                echo "<td>" . $row['ProductName'] . "</td>";
                                                echo "<td class='text-right'>" . $row['QtyO']. "</td>";
                                                echo "<td class='text-right'>" .'฿ ' . $row['Price']. "</td>";
                                                echo "<td class='text-right'>" .'฿ ' . $total ."</td>";
                                                echo "</tr>";
                                            }
                                            $tax_amount = ($total_amount * $tax_percentage) / 100;
                                            $total_amount_with_tax = $total_amount + $tax_amount;
                                        ?>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-right"><strong>Taxes (<?php echo $tax_percentage; ?>%)</strong></td>
                                            <td class="text-right"><strong><?php echo '฿ '.number_format($tax_amount, 2); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-right"><strong>Total</strong></td>
                                            <td class="text-right"><strong><?php echo '฿ '.number_format($total_amount_with_tax, 2); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right identity">
                                <h3>ผู้รับรอง<br><strong><br>__________________</strong></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById('convertToPDF').addEventListener('click', function() {
            document.getElementById("convertToPDF").style.display = "none";
            const element = document.querySelector('.invoice');
            html2canvas(element).then(function(canvas) {
                const imageData = canvas.toDataURL('image/png');
                var doc = new jspdf.jsPDF();
                doc.addImage(imageData, 'PNG', 0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight());
                doc.save('Invoice.pdf');
                document.getElementById("convertToPDF").style.display = "block";
            });
        });
    </script>
</body>
</html>