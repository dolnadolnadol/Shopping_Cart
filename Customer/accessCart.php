<?php
include('../logFolder/AccessLog.php');
include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');
include('./component/session.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!$_SESSION['uid']){
        header('Location: ./login.php');
    }
    /* Add product in cart */
    else if (isset($_POST['id_product']) && isset($_POST['amount'])) {
        $productId = $_POST['id_product'];
        $amount = $_POST['amount'];
        $uid = $_SESSION['uid'];
        include_once '../dbConfig.php'; 
        if (isset($_SESSION['id_username'])) {
            $check_query = mysqli_query($conn, "SELECT * FROM cart WHERE cusId = '$uid' AND ProId = '$productId'");
            if (mysqli_num_rows($check_query) > 0) {
                echo "Product already exists in the cart for the user.";
                $stmt = mysqli_query($conn, "UPDATE cart SET Qty = Qty +'$amount'
                WHERE cusId ='$uid' AND ProId = '$productId'");

                // ACCESS LOG
                if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ipAddress = $_SERVER['REMOTE_ADDR'];
                }
                $callingFile = __FILE__;
                $action = 'UPDATE'; // Static Change Action
                CallLog::callLog($ipAddress, $conn, $uid, $productId, $callingFile, $action);
                //END LOG

                header("Location: ./cart.php");
            } else {
                //insert in cart
                // echo $uid . " ";
                // echo $productId . " ";
                // echo $amount . " ";
                $stmt = "INSERT INTO cart(cusId, ProId, Qty) VALUES('$uid', '$productId', '$amount')";

                $msresults = mysqli_query($conn, $stmt);
                $stmt2 = mysqli_query($conn, "UPDATE product SET onHand = onHand + '$amount' WHERE proId = '$productId'");

                header("Location: ./");
                exit();
            }
            /* Guest */
        } else if (isset($_POST['add_to_cart'])) {
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] = $amount;

            } else {
                $_SESSION['cart'][$productId] = [
                    'quantity' => $amount
                ];
            }
            // header("Location: ./index.php");
        }
    }
    /* Delete product in cart */ 
    else if (isset($_POST['deleteID'])) {
        $productId = $_POST['deleteID'];
        if (isset($_POST['CusID'])) {
            include_once '../dbConfig.php'; 
            $cusID = $_POST['CusID'];
            $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE cusID = '$cusID' AND ProId = '$productId'");
            $cart_row = mysqli_fetch_assoc($cart_query);
            $cart_qty = (int)$cart_row['Qty'];
            if (mysqli_num_rows($cart_query) > 0) {
                $OnHand_update = mysqli_query($conn, "UPDATE product SET OnHand = OnHand - '$cart_qty' WHERE proId = '$productId'");

                $check_query = mysqli_query($conn, "DELETE FROM cart WHERE cusID = '$cusID' AND ProId = '$productId'");
            }
        } else if (isset($_SESSION['cart'][$productId])) {

            unset($_SESSION['cart'][$productId]);
        }

        header("Location: ./cart.php");
        exit();
    }
}
?>