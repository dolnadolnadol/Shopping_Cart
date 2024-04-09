<?php
function getProductByTypeId($typeid) {
    include '../dbConfig.php';
    $sql = "SELECT * FROM product WHERE deleteStatus != 1 AND typeId = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $typeid);
    mysqli_stmt_execute($stmt);
    $msresults = mysqli_stmt_get_result($stmt);

    // Display products
    echo "<div class='product-container'>";
    echo "<a>type". $typeid . "</a>";
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

    // Close the prepared statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
