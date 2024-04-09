<?php
include_once '../dbConfig.php';

$sql = "SELECT MAX(typeid) AS max_id FROM product_type";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    
    if ($row['max_id'] !== null) {
        $max_id = $row['max_id'];
    } else {
        echo "No records found in the product_type table.";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

?>
