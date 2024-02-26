<?php
include('../logFolder/AccessLog.php');
include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');
?>

<?php
include('./component/session.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* Add product in cart */
    if (isset($_POST['id_product']) && isset($_POST['amount'])) {

        $productId = $_POST['id_product'];
        $amount = $_POST['amount'];

        $cx =  mysqli_connect("localhost", "root", "", "shopping");
        if (isset($_SESSION['id_username'])) {
            $check_query = mysqli_query($cx, "SELECT * FROM cart WHERE CusID = '$uid' AND ProID = '$productId'");
            if (mysqli_num_rows($check_query) > 0) {
                echo "Product already exists in the cart for the user.";
                $stmt = mysqli_query($cx, "UPDATE cart SET Qty ='$amount'
                WHERE CusID ='$uid' AND ProID = '$productId'");
                header("Location: ./cart.php");
            } else {
                //insert in cart
                $stmt = "INSERT INTO cart(CusID, ProID, Qty) VALUES('$uid', '$productId', '$amount')";

                // ACCESS LOG
                if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ipAddress = $_SERVER['REMOTE_ADDR'];
                }
                $callingFile = __FILE__;
                $action = 'INSERT'; // Static Change Action
                CallLog::callLog($ipAddress, $cx, $uid, $productId, $callingFile, $action);
                //END LOG

                $msresults = mysqli_query($cx, $stmt);
                $stmt2 = mysqli_query($cx, "UPDATE product SET OnHands = OnHands + '$amount' WHERE ProID = '$productId'");
                header("Location: ./index.php");
                exit();
            }
        } else if (isset($_POST['add_to_cart'])) {
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] = $amount;
            } else {
                $_SESSION['cart'][$productId] = [
                    'quantity' => 1
                ];
            }
            header("Location: ./index.php");
        }
    }
    /* Delete product in cart */ else if (isset($_POST['deleteID'])) {
        $proID = $_POST['deleteID'];
        if (isset($_POST['CusID'])) {
            $cx =  mysqli_connect("localhost", "root", "", "shopping");
            $cusID = $_POST['CusID'];
            $cart_query = mysqli_query($cx, "SELECT * FROM cart WHERE CusID = '$cusID' AND ProID = '$proID'");
            $cart_row = mysqli_fetch_assoc($cart_query);
            $cart_qty = (int)$cart_row['Qty'];
            if (mysqli_num_rows($cart_query) > 0) {
                $OnHands_update = mysqli_query($cx, "UPDATE product SET OnHands = OnHands - '$cart_qty' WHERE ProID = '$proID'");
                $check_query = mysqli_query($cx, "DELETE FROM cart WHERE CusID = '$cusID' AND ProID = '$proID'");
            }
        } else if (isset($_SESSION['cart'][$proID])) {
            unset($_SESSION['cart'][$proID]);
        }

        header("Location: ./cart.php");
        exit();
    }
}
?>