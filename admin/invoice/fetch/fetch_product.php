<?php
    include_once '../dbConfig.php'; 
$productName = $Price = "";
$productID = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productID'])) {
    $productID = mysqli_real_escape_string($conn, $_POST['productID']);

    $query = "SELECT ProductName, Price FROM product WHERE proId = '$productID'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Return product information as JSON
        echo json_encode([
            'productName' => $row['ProductName'],
            'Price' => $row['Price']
        ]);
    } else {
        // Handle the case where the query fails
        echo json_encode([
            'productName' => 'Error',
            'Price' => 'Error'
        ]);
    }
}
?>
