<?php
    session_start();
    if($_SESSION['auth'] !== 'product-admin') {
        header("Location: ../notHavePage.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Info</title>

    <!-- Include necessary libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #cce0ff;
            /* Ocean-sky blue background color */
        }

        .invoice-form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            /* White background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Box shadow for a subtle lift */
        }

        .form-block {
            border: 1px solid #b3ccff;
            /* Light ocean-sky blue border color */
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #edf5ff;
            /* Light ocean-sky blue background color */
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-groupRow {
            margin-bottom: 20px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 20%;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #3366cc;
            /* Dark ocean-sky blue text color for labels */
        }

        input,textarea,
        select {
            color: #000000;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #99c2ff;
            /* Light ocean-sky blue border color for inputs and selects */
            border-radius: 4px;
            text-align: left;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 15%;
        }

        button.newStatus:hover {
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

    </style>
</head>

<body>
    <?php
        include_once '../../dbConfig.php';
        $orderId = $_POST['id_order'];
        $cur = "SELECT * FROM orderkey 
                INNER JOIN customer ON customer.CusID = orderkey.cusId 
                INNER JOIN orderdelivery ON orderdelivery.DeliId = orderkey.DeliId 
                WHERE orderId = '$orderId'";
        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
        $orderId = $row['orderId'];
        $deliId = $row['DeliId'];
    ?>
    <div class="invoice-form">
        <h2 style="color: #007bff; text-align: center;">Order Information</h2>

        <div class="form-block">
            <div class="form-group">
                <label>Order ID:</label>
                <?php echo"<input type='text' id='orderId' name='orderId' value='$orderId' readonly>"?>
                <label>Order Date:</label>
                <?php echo"<input type='text' id='orderDate' name='orderDate' value='{$row['orderCreate']}' readonly>"?>
                <label>Delivery ID:</label>
                <?php echo "<input type='text' id='DeliId' name='DeliId' value='{$row['DeliId']}' readonly>" ?> 
                <label>Delivery Date:</label>
                <?php echo "<input type='text' id='DeliDate' name='DeliDate' value='{$row['DeliDate']}' readonly>" ?>
                <label>Product list:</label>
            </div>
            <table>
                <tr style="color:black;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>  
                </tr>
                <?php
                    $cur = "SELECT ordervalue.*, ordervalue.Qty AS QtyO, product.*
                            FROM ordervalue
                            INNER JOIN product ON product.proId = ordervalue.ProId
                            WHERE orderId = '$orderId'";
                    $msresults = mysqli_query($conn, $cur);
                    while ($row = mysqli_fetch_assoc($msresults)) {
                        $total = (double)$row['Price'] * (double)$row['QtyO'];
                        echo "<tr>";
                        echo "<td>" . $row['proId'] . "</td>";
                        echo "<td>" . $row['ProductName'] . "</td>";
                        echo "<td>" . $row['Price'] . "</td>";
                        echo "<td>" . $row['QtyO'] . "</td>";
                        echo "<td>" . $total . "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
        
        <h2 style="color: #007bff; text-align: center;">Customer Information</h2>
        <div class="form-block">
            <div class="form-group">
                <?php
                    $result = mysqli_query($conn, "SELECT * FROM orderkey INNER JOIN customer ON customer.CusID = orderkey.cusID WHERE orderkey.orderId = '$orderId'");
                    $row = mysqli_fetch_assoc($result);
                    echo "<label for='RecID'>Customer ID:</label>";
                    echo "<input type='text' value='{$row['CusID']}' readonly>";
                    echo "<label for='customerName'>Customer Name:</label>";
                    echo "<input type='text' value='{$row['fname']} {$row['lname']}' readonly>";
                    echo "<label for='customerName'>Tel:</label>";
                    echo "<input type='text' value='{$row['Tel']}' readonly>";
                    echo "<label for='customerName'>Email:</label>";
                    echo "<input type='text' value='{$row['Email']}' readonly>";
                ?>
            </div>
            <div class="form-group" style="color: #007bff">
                <label style="color: #007bff" for="customerName">Customer Address:</label>
                <?php 
                $cur = "SELECT * FROM orderkey
                        INNER JOIN address ON address.CusID = orderkey.cusId
                        WHERE orderId = '$orderId'";
                $msresults = mysqli_query($conn, $cur);
                $row = mysqli_fetch_assoc($msresults);
                echo "<textarea readonly rows='5'>{$row['Address']}, {$row['Province']}, {$row['City']}, {$row['PostalCode']}</textarea>";
                ?>
            </div>
        </div>
        <h2 style="color: #007bff; text-align: center;">Receipt Information</h2>
        <div class="form-block">
            <?php if ($row['PaymentStatus'] === 'Pending'): ?>
                <p style="color: #ff3333; text-align: center;">Payment is pending</p>
                <form id="paramsForm" method="POST" action="order_update_status.php">
                    <div class="form-groupRow" style="color: #007bff">
                        <button type="submit" class="newStatus" name="newStatus" value="Canceled" style="background-color: #aa0000;">Canceled</button>
                        <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId; ?>">
                        <input type="hidden" name="deliId" id="deliId" value="<?php echo $deliId; ?>">
                    </div>
                </form>
            <?php else: ?>
                <div class="form-group">
                    <?php
                        $result = mysqli_query($conn, "SELECT * FROM orderkey 
                        INNER JOIN customer ON customer.CusID = orderkey.cusID
                        INNER JOIN receipt ON receipt.CusID = orderkey.cusID 
                        WHERE orderkey.orderId = '$orderId'");
                        $row = mysqli_fetch_assoc($result);
                        if(isset($row['slip'])){
                            $imageDataEncoded = base64_encode($row['slip']);
                            echo "<img src='data:image/jpg;base64,{$imageDataEncoded}' alt='imagy' style='width:100%; height:100%;'/>";
                        }
                        else{
                            echo "<img src='../img/no-image.png' alt='No Image' style='width:100%; height:100%;' />";
                        }
                    ?>
                </div>
                <form id="paramsForm" method="POST" action="order_update_status.php">
                    <div class="form-groupRow" style="color: #007bff">
                        <button type="submit" class="newStatus" name="newStatus" value="Canceled" style="background-color: #aa0000;">Canceled</button>
                        <button type="submit" class="newStatus" name="newStatus" value="Checking" style="background-color: #ff3333;">Not Approve</button>
                        <button type="submit" class="newStatus" name="newStatus" value="Success" style="background-color: #007bff;">Approve</button>
                        <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId; ?>">
                        <input type="hidden" name="deliId" id="deliId" value="<?php echo $deliId; ?>">
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>