<?php
include_once '../dbConfig.php';
$cur = "SELECT * FROM product where deleteStatus != 1";
$msresults = mysqli_query($conn, $cur);
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
