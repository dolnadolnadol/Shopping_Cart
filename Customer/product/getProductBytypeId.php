<?php
function getProductByTypeId($typeid) {
    include '../dbConfig.php';
    $sql = "SELECT * FROM product join product_type on product.typeId = product_type.typeId WHERE deleteStatus != 1 AND product.typeId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $typeid);
    mysqli_stmt_execute($stmt);
    $msresults = mysqli_stmt_get_result($stmt);

    $sql = "SELECT * FROM product_type WHERE typeId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $typeid);
    mysqli_stmt_execute($stmt);
    $msresults2 = mysqli_stmt_get_result($stmt);
    $row2 = mysqli_fetch_assoc($msresults2);

    // Display products
    echo "<div style='border-top:1px solid; margin-top:50px;'>";
    echo "<div style='display:flex; justify-content:center; font-size:1.5rem; padding:10px; margin-top:30px;'>";
    echo "<a>"."ประเภท ". $row2['typeName'] . "</a>";
    echo "</div>";
    echo "<div class='product-container'>";
    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<div class='product-card'>";
            include('./component/showPhotos.php');
            echo "
                    <p class='product-name'>{$row['ProductName']}</p>
                    <p class='product-price'>Price: {$row['Price']} บาท</p>
                    <form method='post' action='detailProduct.php'>
                        <input type='hidden' name='id_product' value='{$row['proId']}'>
                        <button class='buy-button' type='submit'>Add to Cart</button>
                    </form>
            </div>";
        }
    } else {
        echo "<p>No products available.</p>";
    }
    echo "</div>";
    echo "</div>";

    // Close the prepared statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
