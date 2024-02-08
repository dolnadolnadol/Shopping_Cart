<?php
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'true') {

        // Redirect to the login page
        header("Location: login.php");
        session_destroy();
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #3498db;
        }

        .order {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .order p {
            margin: 0;
            padding: 5px 0;
        }

        .order p:first-child {
            font-size: 1.2em;
            font-weight: bold;
        }

        .order p:last-child {
            color: #888;
        }
    </style>
</head>
<body>
    <a href='index.php'> <- กลับไปหน้าหลัก </a>
    <div class="container">
        <h1>Order History</h1>
        <?php
            $cx =  mysqli_connect("localhost", "root", "", "shopping");
            $cur = "SELECT * FROM invoice";
            $msresults = mysqli_query($cx, $cur);

            while ($row = mysqli_fetch_array($msresults)) {
                echo '<div class="order">';
                echo "<p>Invoice ID: {$row['InvID']}</p>";
                echo "<p>Order Date: {$row['Period']}</p>";
                echo "<p>Total Amount: {$row['TotalPrice']}฿</p>";
                echo '</div>';
            }
        ?>
    </div>
</body>
</html>
