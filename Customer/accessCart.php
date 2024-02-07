<!-- accessCart.php -->
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['id_product']) && isset($_POST['amount'])){
        $productId = $_POST['id_product'];
        $amount = $_POST['amount'];

        // Initialize the cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Add or update the quantity in the cart
        $_SESSION['cart'][$productId] = $amount;

        // Redirect the user to a confirmation page
        header("Location: ./index.php");
    }
    else if(isset($_POST['newID']) && isset($_POST['newQuantity'])){
        $productId = $_POST['newID'];
        $newQuantity = $_POST['newQuantity'];

        // Update the quantity in the session
        $_SESSION['cart'][$productId] = $newQuantity;
        header("Location: cart.php");
    }
    else if(isset($_POST['deleteID'])){
        $productIdToRemove = $_POST['deleteID'];
        unset($_SESSION['cart'][$productIdToRemove]);
        header("Location: cart.php");
    }
    exit();
}
?>