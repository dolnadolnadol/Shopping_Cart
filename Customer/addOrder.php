<?php
// Include session file for session management
include('./component/session.php');
// include('../logFolder/AccessLog.php');
// include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Connect to the database
    include_once '../dbConfig.php'; 
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $postalcode = $_POST['postalcode'];

    $totalPriceAllItems = 0;
    $recv_id = '';

    // Check if 'id_customer' is set in the POST data
    if (isset($_POST['id_customer'])) {

        // Retrieve customer ID from the POST data
        $cusID = $_POST['id_customer'];

        /* ------------------------------------------------------------------------- */
        /* ------------------------------------------------------------------------- */

        // Retrieve cart items for the specified customer
        $check_query = mysqli_query($conn, "SELECT product.proId AS proId, cart.Qty AS Qty, product.price AS price FROM cart 
            INNER JOIN product ON cart.proId = product.proId WHERE CusID = '$cusID'");

        // Check if there are items in the cart
        if (mysqli_num_rows($check_query) > 0) {

            $pending = "Pending";
            $stmt = $conn->prepare("INSERT INTO orderkey (PaymentStatus, cusId) VALUES (?,?)");
            $stmt->bind_param("si", $pending, $cusID);
            $stmt->execute();
            $lastId = mysqli_insert_id($conn);
            if($_POST['changeInfo'] == "value" || $_POST['changeaddress'] == "value"){
                echo $_POST['changeInfo'] . "wtfman ";
                echo $_POST['changeaddress'];
                $stmt = $conn->prepare("INSERT INTO address (fname, lname, tel, Address, Province, City, PostalCode, CusId) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->bind_param("sssssssi", $fname, $lname, $tel, $address, $province, $city, $postalcode, $cusID);
                $stmt->execute();
                $addrId = mysqli_insert_id($conn);
            }else{
                $addrId = $_POST['addrId'];
                echo $addrId . "wtfman2gg ";
            }
            

            // ACCESS LOG
            // $productId = "";
            // if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //     $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            // } else {
            //     $ipAddress = $_SERVER['REMOTE_ADDR'];
            // }
            // $callingFile = __FILE__;
            // $action = 'INSERT'; // Static Change Action
            // CallLog::callLog($ipAddress, $conn, $uid, $productId, $callingFile, $action);
            //END LOG

            // Iterate through each item in the cart
            while ($row = mysqli_fetch_array($check_query)) {

                $proId = $row['proId'];
                $Qty = $row['Qty'];

                // Calculate subtotal, tax, and total
                $subTotal = $row['price'] * $Qty;
                $totalPriceAllItems += $subTotal;
                $Tax = $totalPriceAllItems * 0.07;
                $Total = $Tax + $totalPriceAllItems;

                // Insert invoice_detail record
                $stmt = $conn->prepare("INSERT INTO ordervalue (orderId, ProId, Qty) VALUES (?,?,?)");
                $stmt->bind_param("iii", $lastId, $proId, $Qty);
                $stmt->execute();
            }

            $statusDeli = "PREPARE";
            $fullname = $fname . "  " . $lname;
            $stmt = $conn->prepare("INSERT INTO orderdelivery (statusDeli, addrId, Name, Tel, TotalPrice) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sissi", $statusDeli, $addrId, $fullname, $tel, $Total);
            $stmt->execute();
            $delilast = mysqli_insert_id($conn);

            
            $stmt = $conn->prepare("update orderkey set DeliId = ? where orderId = ?");
            $stmt->bind_param("ii", $delilast, $lastId);
            $stmt->execute();

            $deleteQuery = "DELETE FROM cart WHERE cusId = '$cusID'";
            $result = mysqli_query($conn, $deleteQuery);

            if ($result) {
                echo "Deletion successful!";
            } else {
                echo "Deletion failed: " . mysqli_error($conn);
            }
            echo "<form id='auto_submit_form' method='post' action='paymentForm.php'>
                <input type='hidden' name='id_invoice' value='$InvID'>
                <input type='hidden' name='id_receiver' value='$recv_id'>
                <input type='hidden' name='id_order' value='$lastId'>
                <input type='hidden' name='id_address' value='$addrId'>
            </form>";

            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('auto_submit_form').submit();
                });
            </script>";
            
        // header("Location: ./paymentForm.php");
        } else {
            echo "alert('No Item In Cart!')";
            // Redirect to cart page if the cart is empty
            echo header("Location: ./cart.php");;
            exit();
        }

    /* Guest */
    } else {
        header("Location: ./cart.php");
    }
}
?>