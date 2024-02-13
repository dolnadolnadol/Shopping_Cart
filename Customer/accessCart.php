<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* Add product in cart */
    if(isset($_POST['id_product']) && isset($_POST['amount'])){

        $productId = $_POST['id_product'];
        $amount = $_POST['amount'];
        $user = $_SESSION['username'];

        $cx =  mysqli_connect("localhost", "root", "", "shopping");


        $uid_query = mysqli_query($cx, "SELECT CusID FROM customer WHERE Username = '$user'");
        $uid_row = mysqli_fetch_assoc($uid_query);
        $uid_results = $uid_row['CusID'];

        // Check if the record already exists in the cart table for the given CusID and ProID
        $check_query = mysqli_query($cx, "SELECT * FROM cart WHERE CusID = '$uid_results' AND ProID = '$productId'");
        if(mysqli_num_rows($check_query) > 0){
            echo "Product already exists in the cart for the user.";
            $stmt = mysqli_query($cx, "UPDATE cart SET Qty ='$amount'
            WHERE CusID ='$uid_results' AND ProID = '$productId'");
            header("Location: ./cart.php");
        }
        else {
            $stmt = "INSERT INTO cart(CusID, ProID, Qty) VALUES('$uid_results', '$productId', '$amount')";
            $msresults = mysqli_query($cx, $stmt);
            $stmt2 = mysqli_query($cx, "UPDATE product SET OnHands = OnHands + '$amount' WHERE ProID = '$productId'");
            header("Location: ./index.php");
            exit(); 
        }
    }
    /* Delete product in cart */
    else if(isset($_POST['CusID']) && isset($_POST['deleteID'])){
        $cusID = $_POST['CusID'];
        $proID = $_POST['deleteID'];
        $cx =  mysqli_connect("localhost", "root", "", "shopping");
        $cart_query = mysqli_query($cx, "SELECT * FROM cart WHERE CusID = '$cusID' AND ProID = '$proID'");
        // $cart_result = mysqli_query($cx, "SELECT Qty FROM cart WHERE CusID = '$cusID' AND ProID = '$proID'");
        $cart_row = mysqli_fetch_assoc($cart_query);
        $cart_qty = (int)$cart_row['Qty'];

        if(mysqli_num_rows($cart_query) > 0){
            $OnHands_update = mysqli_query($cx, "UPDATE product SET OnHands = OnHands - '$cart_qty' WHERE ProID = '$proID'");
            $check_query = mysqli_query($cx, "DELETE FROM cart WHERE CusID = '$cusID' AND ProID = '$proID'");
            
        }
        header("Location: ./cart.php");
        exit(); 
    }
}
?>