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
            /* Darker ocean-sky blue button color */
            color: #fff;
            /* White text color for the button */
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
            /* Slightly darker color on hover */
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
    <?php  include_once '../../dbConfig.php';
        $orderId = $_POST['id_order'];
        $cur = "SELECT * FROM orderkey 
        INNER JOIN customer ON customer.CusID = orderkey.cusId 
        INNER JOIN orderdelivery ON orderdelivery.DeliId = orderkey.DeliId 
        WHERE orderId = '$orderId'";
        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
    ?>
    <div class="invoice-form">
        <h2 style="color: #007bff; text-align: center;">Order Information</h2>

        <div class="form-block">
            <div class="form-group">
                <label>Order ID:</label>
                <?php echo"<input type='text' id='orderId' name='orderId' value='$orderId' readonly>"?>
                <label>Order Date:</label>
                <?php echo"<input type='text' id='orderDate' name='orderDate' value='{$row['orderCreate']}' readonly>"?>
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
    </div>
</body>
</html>