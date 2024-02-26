<?php
$cx = mysqli_connect("localhost", "root", "", "shopping");




$result = mysqli_query($cx, $query);

$row = mysqli_fetch_assoc($result);

$countZero = $row['count_zero'];
$countNonZero = $row['count_non_zero'];

mysqli_close($cx);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Product Summary</h1>
        <p><?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
    <div>
    <h1>Product Sale</h1>
    </div>
    <table>
        <tr>
            <th>ProductName</th>
            <th>TotalSold</th>
            <th>Revenue</th>
        </tr>
        <tr>
            <!-- Populate this row with data -->
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div>
        <h1>Best Product</h1>
    </div>
    <table>
        <tr>
            <th>ProductName</th>
            <th>Sold</th>
            <th>Revenue</th>
        </tr>
        <tr>
            <!-- Populate this row with data -->
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</body>
</html>
