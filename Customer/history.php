<?php include('./component/session.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 100px auto auto auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 100px;
        }

        h1 {
            text-align: center;
            color: #3498db;
        }

        .order {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .order p {
            padding: 5px 0;
        }

        .order pf {
            font-size: 1.2em;
            font-weight: bold;
        }

        .order pl {
            font-size: 1.2em;
            color: #ffff;
            border-radius: 10px;
        }

        #Paid {
            padding: 3px 8px;
            background-color: #06D6B1;
        }

        #Unpaid {
            padding: 3px 8px;
            background-color: #F0476F;
        }

        #Pending {
            padding: 3px 8px;
            background-color: #FFA500;
            margin-top: 5px;
        }

        #prepare {
            padding: 3px 8px;
            background-color: #ccc;
            margin-top: 5px;
        }

        #Inprogress {
            padding: 3px 8px;
            background-color: #7c6bff;
            margin-top: 5px;
        }

        #Delivered {
            padding: 3px 8px;
            background-color: #06D6B1;
            margin-top: 5px;
        }

        #Canceled {
            padding: 3px 8px;
            background-color: #F0476F;
            margin-top: 5px;
        }



        .icon-container {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }


        .tab {
            overflow: hidden;
            border: 1px solid #fff;
            /* Added border bottom */
            margin-bottom: 10px;
        }

        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            border-bottom: 2px solid transparent;
            /* Added transparent border */
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
            border-bottom: 2px solid #3498db;
            /* Underline color */
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
    </style>
</head>

<body>
    <?php include('./component/accessNavbar.php') ?>
    <div class="container">
        <h1>History</h1>
        <!-- Tab buttons -->
        <div class="tab">
            <!-- <button id="invoiceTab" class="tablinks" onclick="openTab(event, 'invoice')">Invoice</button> -->
            <!-- <button id="invoiceTab" class="tablinks" onclick="openTab(event, 'orders')">All Orders</button> -->
            <button class="tablinks" onclick="openTab(event, 'pay')">รอชำระ</button>
            <button class="tablinks" onclick="openTab(event, 'delivery')">รอจัดส่ง</button>
            <button class="tablinks" onclick="openTab(event, 'success')">สำเร็จ</button>
        </div>
        <?php
        $uid = $_SESSION['id_username'];
        ?>

        <!-- Tab content -->
        <div id="invoice" class="tabcontent">
            <?php includeInvoice("SELECT * FROM invoice WHERE CusID = '$uid'"); ?>
        </div>
        <div id="pay" class="tabcontent">
            <?php includePay("SELECT
    orderkey.orderId,
    GROUP_CONCAT(ordervalue.ProId) AS ProId,
    GROUP_CONCAT(ordervalue.Qty) AS Qty,
    orderkey.DeliId,
    orderdelivery.DeliDate,
    orderdelivery.statusDeli,
    orderdelivery.addrId,
    orderdelivery.fname,
    orderdelivery.lname,
    orderdelivery.Tel,
    orderdelivery.TotalPrice,
    orderkey.orderCreate,
    orderkey.PaymentStatus
FROM 
    orderkey 
LEFT JOIN 
    ordervalue ON orderkey.orderId = ordervalue.orderId
JOIN 
    orderdelivery ON orderkey.DeliId = orderdelivery.DeliId 
WHERE 
    CusID = '$uid' and PaymentStatus = 'Pending'
GROUP BY 
    orderkey.orderId;"); ?>
        </div>

        <div id="delivery" class="tabcontent">
            <?php includeOrders("SELECT 
    orderkey.orderId,
    GROUP_CONCAT(ordervalue.ProId) AS ProId,
    GROUP_CONCAT(ordervalue.Qty) AS Qty,
    orderkey.DeliId,
    orderdelivery.DeliDate,
    orderdelivery.statusDeli,
    orderdelivery.addrId,
    orderdelivery.fname,
    orderdelivery.lname,
    orderdelivery.Tel,
    orderdelivery.TotalPrice,
    orderkey.orderCreate,
    orderkey.PaymentStatus
FROM 
    orderkey 
LEFT JOIN 
    ordervalue ON orderkey.orderId = ordervalue.orderId
JOIN 
    orderdelivery ON orderkey.DeliId = orderdelivery.DeliId 
WHERE 
    CusID = '$uid' and PaymentStatus != 'Pending' and statusDeli = 'Inprogress'
GROUP BY 
    orderkey.orderId;"); ?>
        </div>

        <div id="orders" class="tabcontent" style="display:none;">
            <?php includeOrders("SELECT 
    orderkey.orderId,
    GROUP_CONCAT(ordervalue.ProId) AS ProId,
    GROUP_CONCAT(ordervalue.Qty) AS Qty,
    orderkey.DeliId,
    orderdelivery.DeliDate,
    orderdelivery.statusDeli,
    orderdelivery.addrId,
    orderdelivery.fname,
    orderdelivery.lname,
    orderdelivery.Tel,
    orderdelivery.TotalPrice,
    orderkey.orderCreate,
    orderkey.PaymentStatus
FROM 
    orderkey 
LEFT JOIN 
    ordervalue ON orderkey.orderId = ordervalue.orderId 
JOIN 
    orderdelivery ON orderkey.DeliId = orderdelivery.DeliId 
WHERE 
    CusID = '$uid'
GROUP BY 
    orderkey.orderId;"); ?>
        </div>

            <div id="success" class="tabcontent">
                <?php includeSuccess("SELECT 
    orderkey.orderId,
    GROUP_CONCAT(ordervalue.ProId) AS ProId,
    GROUP_CONCAT(ordervalue.Qty) AS Qty,
    orderkey.DeliId,
    orderdelivery.DeliDate,
    orderdelivery.statusDeli,
    orderdelivery.addrId,
    orderdelivery.fname,
    orderdelivery.lname,
    orderdelivery.Tel,
    orderdelivery.TotalPrice,
    orderkey.orderCreate,
    orderkey.PaymentStatus
FROM 
    orderkey 
LEFT JOIN 
    ordervalue ON orderkey.orderId = ordervalue.orderId 
JOIN 
    orderdelivery ON orderkey.DeliId = orderdelivery.DeliId 
WHERE 
    CusID = '$uid' and PaymentStatus != 'Pending' and statusDeli = 'Delivered'
GROUP BY 
    orderkey.orderId;"); ?>
            </div>
        </div>
        <script>
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }
            document.getElementById("invoiceTab").click();
        </script>
</body>

</html>

<?php
function includeInvoice($query)
{
    include '../dbConfig.php';
    $msresults = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($msresults)) {

        if ($row['Status'] == 'Unpaid') {
            echo '<div class="order">';
            echo "<div class='icon-container'>
                    <form method='post' action='paymentForm.php'>
                        <input type='hidden' name='id_invoice' value='{$row['InvID']}'>
                        <input type='hidden' name='id_receiver' value='{$row['RecvID']}'>
                        <button type='submit'>
                            <img src='./image/search-alt.png' alt='Invoice Icon' width='20'>
                        </button>
                    </form>
                </div>";
            echo "<pf>Invoice ID: {$row['InvID']}</pf>";
            echo "<p>Order Date: {$row['Period']}</p>";
            echo "<p>Total Amount: {$row['TotalPrice']} ฿</p>";
            if ($row['Status'] == 'Paid') {
                echo "<pl id='Paid'>Status: {$row['Status']}</pl>";
            } else {
                echo "<pl id='Unpaid'>Status: {$row['Status']}</pl>";
            }
            echo '</div>';
        }
    }
}

function includeOrders($query)
{
    include '../dbConfig.php';
    $msresults = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($msresults)) {
        echo '<div class="order">';
        echo "<div class='icon-container'>
                <form method='post' action='order.php'>
                    <input type='hidden' name='id_order' value='{$row['orderId']}'>
                    <button type='submit'>
                        <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                    </button>
                </form>
            </div>";
        echo "<pf>Order ID: {$row['orderId']}</pf>";
        echo "<p>Total Amount: {$row['TotalPrice']} ฿</p>";
        echo "<p>Order Date: {$row['orderCreate']}</p>";
        if ($row['statusDeli'] != null) {
            echo "<p>Delivery Date: {$row['DeliDate']}</p>";
        }
        if ($row['PaymentStatus'] == 'Pending') {
            echo "<pl id='Pending'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        } else if ($row['PaymentStatus'] == 'Inprogress') {
            echo "<pl id='Inprogress'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        } else if ($row['PaymentStatus'] == 'Success') {
            echo "<pl id='Delivered'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        } else if ($row['PaymentStatus'] == 'Canceled') {
            echo "<pl id='Canceled'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        }
        echo " ";
        if ($row['statusDeli'] == 'Prepare') {
            // echo "<pl id='prepare'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Inprogress') {
            echo "<pl id='Inprogress'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Delivered') {
            echo "<pl id='Delivered'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Canceled') {
            echo "<pl id='Canceled'>Status Delivery: {$row['statusDeli']}</pl>";
        }
        echo '</div>';
    }
}

function includePay($query)
{
    include '../dbConfig.php';
    $msresults = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($msresults)) {
        echo '<div class="order">';
        echo "<div class='icon-container'>
                <form method='post' action='deleteOrder.php'>
                    <input type='hidden' name='id_order' value='{$row['orderId']}'>
                    <button type='submit'>
                        <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                    </button>
                </form>
                <form method='post' action='paymentForm.php'>
                    <input type='hidden' name='id_order' value='{$row['orderId']}'>
                    <input type='hidden' name='id_deli' value='{$row['DeliId']}'>
                    <input type='hidden' name='id_address' value='{$row['addrId']}'>
                    <button type='submit'>
                        <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                    </button>
                </form>
            </div>";
        echo "<pf>Order ID: {$row['orderId']}</pf>";
        echo "<p>Total Amount: {$row['TotalPrice']} ฿</p>";
        echo "<p>Order Date: {$row['orderCreate']}</p>";
        if ($row['statusDeli'] != null) {
            echo "<p>Delivery Date: {$row['DeliDate']}</p>";
        }
        if ($row['PaymentStatus'] == 'Pending') {
            echo "<pl id='Pending'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        } else if ($row['PaymentStatus'] == 'Inprogress') {
            echo "<pl id='Inprogress'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        } else if ($row['PaymentStatus'] == 'Success') {
            echo "<pl id='Delivered'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        } else if ($row['PaymentStatus'] == 'Canceled') {
            echo "<pl id='Canceled'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        }

        echo " ";
        if ($row['statusDeli'] == 'Prepare') {
            // echo "<pl id='prepare'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Inprogress') {
            echo "<pl id='Inprogress'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Delivered') {
            echo "<pl id='Delivered'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Canceled') {
            echo "<pl id='Canceled'>Status Delivery: {$row['statusDeli']}</pl>";
        }
        echo '</div>';
    }
}
function includeSuccess($query)
{
    include '../dbConfig.php';
    $msresults = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($msresults)) {
        echo '<div class="order">';
        echo "<div class='icon-container'>
                <form method='post' action='order.php'>
                    <input type='hidden' name='id_order' value='{$row['orderId']}'>
                    <button type='submit'>
                        <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                    </button>
                </form>
            </div>";
        echo "<pf>Order ID: {$row['orderId']}</pf>";
        echo "<p>Total Amount: {$row['TotalPrice']} ฿</p>";
        echo "<p>Order Date: {$row['orderCreate']}</p>";
        if ($row['statusDeli'] != null) {
            echo "<p>Delivery Date: {$row['DeliDate']}</p>";
        }
        // if ($row['PaymentStatus'] == 'Pending') {
        //     echo "<pl id='Pending'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        // } else if ($row['PaymentStatus'] == 'Inprogress') {
        //     echo "<pl id='Inprogress'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        // } else if ($row['PaymentStatus'] == 'Success') {
        //     echo "<pl id='Delivered'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        // } else if ($row['PaymentStatus'] == 'Canceled') {
        //     echo "<pl id='Canceled'>PaymentStatus: {$row['PaymentStatus']}</pl>";
        // }

        if ($row['statusDeli'] == 'Prepare') {
            // echo "<pl id='prepare'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Inprogress') {
            echo "<pl id='Inprogress'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Delivered') {
            echo "<pl id='Delivered'>Status Delivery: {$row['statusDeli']}</pl>";
        } else if ($row['statusDeli'] == 'Canceled') {
            echo "<pl id='Canceled'>Status Delivery: {$row['statusDeli']}</pl>";
        }
        echo '</div>';
    }
}
?>