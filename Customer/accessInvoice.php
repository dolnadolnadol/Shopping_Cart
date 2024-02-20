<?php
// Include session file for session management
include('./component/session.php');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Connect to the database
    $cx = mysqli_connect("localhost", "root", "", "shopping");
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    $totalPriceAllItems = 0;
    $recv_id = '';

    echo $tel;
    echo $address;

    // Check if 'id_customer' is set in the POST data
    if (isset($_POST['id_customer'])) {

        // Retrieve customer ID from the POST data
        $cusID = $_POST['id_customer'];

        /* ------------------------------------------------------------------------- */
        /* ------------------------------------------------------------------------- */
            
        //Manage user for receive
        $uid = $_POST['id_customer'];
        $new_fname = $_POST['fname'];
        $new_lname = $_POST['lname'];
        $new_tel = $_POST['tel'];
        $new_address = $_POST['address'];
        $select_query_head = "SELECT * FROM receiver 
        INNER JOIN receiver_detail ON receiver_detail.RecvID = receiver.RecvID 
        WHERE receiver_detail.CusID = '$uid' 
        AND receiver.RecvFName = 'new_fname' 
        AND receiver.RecvLName = 'new_lname'
        AND receiver.Tel = 'new_tel'
        AND receiver.Address = 'new_address'";
        $result = mysqli_query($cx ,$select_query_head);

        if(mysqli_num_rows($result) < 0){
            $insert_query_head = "INSERT INTO receiver (RecvFName , RecvLName  , Tel , Address) 
            VALUES('$new_fname', '$new_lname', '$new_tel' , '$new_address')";
            $insert_result_head = mysqli_query($cx, $insert_query_head);
            if ($insert_result_head) {
                $recv_id = mysqli_insert_id($cx);
            } else {
                die("Error inserting into receiver: " . mysqli_error($cx));
            }

            // Generate new NumID
            $resultDetail = mysqli_query($cx, "SELECT MAX(CAST(SUBSTRING(NumID, 4) AS UNSIGNED)) AS num_id FROM receiver_detail WHERE CusID = '$uid'");
            $latestID = mysqli_fetch_assoc($resultDetail);
            $lastID = $latestID['num_id'];

            // Increment the numeric part
            $newNumericPart = $lastID + 1;

            // Format the complete NumID
            $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            echo $NumID;
            echo $recv_id;

            $insert_query_detail = "INSERT INTO receiver_detail (CusID, RecvID, NumID) VALUES('$uid', '$recv_id', '$NumID')";
            $insert_result_detail = mysqli_query($cx, $insert_query_detail);

            if (!$insert_result_detail) {
                die("Error inserting receiver_detail: " . mysqli_error($cx));
            }
        }

   
        /* ------------------------------------------------------------------------- */
        /* ------------------------------------------------------------------------- */

        // Retrieve cart items for the specified customer
        $check_query = mysqli_query($cx, "SELECT Product.ProID, Qty, Product.PricePerUnit FROM cart 
            INNER JOIN Product ON Cart.ProID = Product.ProID WHERE CusID = '$cusID'");

        // Check if there are items in the cart
        if (mysqli_num_rows($check_query) > 0) {

            // Generate a new InvoiceID
            $result = mysqli_query($cx, "SELECT MAX(InvID) AS inv_id FROM invoice");
            $row = mysqli_fetch_assoc($result);
            $lastID = $row['inv_id'];
            $numericPart = intval(substr($lastID, 3));
            $newNumericPart = $numericPart + 1;
            $InvID = 'INV' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            // Insert a new invoice record
            $stmt = mysqli_query($cx, "INSERT INTO invoice (InvID, Period, CusID, Status)
                VALUES ('$InvID', NOW(), '$cusID', 'Unpaid');");

            // Iterate through each item in the cart
            while ($row = mysqli_fetch_array($check_query)) {

                // Generate a new NumID for invoice_detail
                $resultDetail = mysqli_query($cx, "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'");
                $row2 = mysqli_fetch_assoc($resultDetail);
                $lastID = $row2['num_id'];
                $numericPart = intval(substr($lastID, 3));
                $newNumericPart = $numericPart + 1;
                $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

                $proID = $row['ProID'];
                $Qty = $row['Qty'];

                // Calculate subtotal, tax, and total
                $subTotal = $row['PricePerUnit'] * $Qty;
                $totalPriceAllItems += $subTotal;
                $Tax = $totalPriceAllItems * 0.07;
                $Total = $Tax + $totalPriceAllItems;

                // Insert invoice_detail record
                $stmt = mysqli_query($cx, "INSERT INTO invoice_detail (NumID, InvID, ProID, Qty)
                    VALUES ('$NumID', '$InvID', '$proID', '$Qty');");
            }

            // Update the total price in the invoice
            if (isset($cusID) && !empty($cusID)) {
                $stmt = mysqli_query($cx, "UPDATE invoice SET TotalPrice = '$Total' WHERE InvID ='$InvID'");

                // Delete items from the cart after successful invoice creation
                $deleteQuery = "DELETE FROM cart WHERE CusID = '$cusID'";
                $result = mysqli_query($cx, $deleteQuery);

                // Check if deletion was successful
                if ($result) {
                    echo "Deletion successful!";
                } else {
                    echo "Deletion failed: " . mysqli_error($cx);
                }

                // echo $recv_id;

                // Auto-submit form to redirect to invoice page
                echo "<form id='auto_submit_form' method='post' action='invoice.php'>
                    <input type='hidden' name='id_invoice' value='$InvID'>
                    <input type='hidden' name='id_receiver' value='$recv_id'>
                </form>";

                
                // Use JavaScript to trigger form submission
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('auto_submit_form').submit();
                    });
                </script>";
            }
        } else {
            // Redirect to cart page if the cart is empty
            header("Location: ./cart.php");
            exit();
        }
    } elseif (isset($_POST['cart'])) {

        // Check if the session cart is set and not empty
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            //Manage new Guest for receive
            
     
            // Insert a customer record for guests
            $stmt_customer = mysqli_query($cx, "INSERT INTO customer(CusFName , CusLName , Tel , Address )
                VALUES('$fname', '$lname' , '$tel' ,'$address' );");
            $cusID = mysqli_insert_id($cx);

            /* ------------------------------------------------------------------------- */
            /* ------------------------------------------------------------------------- */
                  

            $select_query_head = "SELECT * FROM receiver 
            INNER JOIN receiver_detail ON receiver_detail.RecvID = receiver.RecvID 
            WHERE receiver_detail.CusID = '$cusID' 
            AND receiver.RecvFName = '$fname' 
            AND receiver.RecvLName = '$lname'
            AND receiver.Tel = '$tel'
            AND receiver.Address = '$address'";
            $result = mysqli_query($cx ,$select_query_head);
            

            if(mysqli_num_rows($result) == 0){

                $insert_query_head = "INSERT INTO receiver (RecvFName , RecvLName  , Tel , Address) 
                VALUES('$fname', '$lname', '$tel' , '$address')";
                $insert_result_head = mysqli_query($cx, $insert_query_head);

                if ($insert_result_head) {
                    $recv_id = mysqli_insert_id($cx);
                } else {
                    die("Error inserting into receiver: " . mysqli_error($cx));
                }

                // Generate new NumID
                $resultDetail = mysqli_query($cx, "SELECT MAX(CAST(SUBSTRING(NumID, 4) AS UNSIGNED)) AS num_id FROM receiver_detail WHERE CusID = '$cusID'");
                $latestID = mysqli_fetch_assoc($resultDetail);
                $lastID = $latestID['num_id'];

                // Increment the numeric part
                $newNumericPart = $lastID + 1;

                // Format the complete NumID
                $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

                echo $NumID;
                echo $recv_id;

                $insert_query_detail = "INSERT INTO receiver_detail (CusID, RecvID, NumID) VALUES('$cusID', '$recv_id', '$NumID')";
                $insert_result_detail = mysqli_query($cx, $insert_query_detail);

                if (!$insert_result_detail) {
                    die("Error inserting receiver_detail: " . mysqli_error($cx));
                }
            }
            /* ------------------------------------------------------------------------- */
            /* ------------------------------------------------------------------------- */
           
            // Generate a new InvoiceID
            $result = mysqli_query($cx, "SELECT MAX(InvID) AS inv_id FROM invoice");
            $row = mysqli_fetch_assoc($result);
            $lastID = $row['inv_id'];
            $numericPart = intval(substr($lastID, 3));
            $newNumericPart = $numericPart + 1;
            $InvID = 'INV' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            // Insert a new invoice record for guests
            $stmt = mysqli_query($cx, "INSERT INTO invoice (InvID, Period, CusID, Status)
                VALUES ('$InvID', NOW(), $cusID, 'Unpaid');");

            // Iterate through each item in the session cart
            foreach ($_SESSION['cart'] as $product_id => $product) {

                // Generate a new NumID for invoice_detail
                $resultDetail = mysqli_query($cx, "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'");
                $row2 = mysqli_fetch_assoc($resultDetail);
                $lastID = $row2['num_id'];
                $numericPart = intval(substr($lastID, 3));
                $newNumericPart = $numericPart + 1;
                $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

                $Qty = $product['quantity'];

                // Fetch product details from the database
                $cur = "SELECT ProID, ProName, PricePerUnit FROM product WHERE ProID = '$product_id'";
                $msresults = mysqli_query($cx, $cur);
                $row = mysqli_fetch_array($msresults);

                // Calculate subtotal, tax, and total
                $subTotal = $row['PricePerUnit'] * $Qty;
                $totalPriceAllItems += $subTotal;
                $Tax = $totalPriceAllItems * 0.07;
                $Total = $Tax + $totalPriceAllItems;

                // Insert invoice_detail record
                $stmt = mysqli_query($cx, "INSERT INTO invoice_detail (NumID, InvID, ProID, Qty)
                    VALUES ('$NumID', '$InvID', '$product_id', '$Qty');");
            }

            // Update the total price in the invoice
            $stmt = mysqli_query($cx, "UPDATE invoice SET TotalPrice ='$Total' WHERE InvID ='$InvID'");

            // Check if customer ID is set and not empty
            if (isset($cusID) && !empty($cusID)) {
                // Set the customer ID in the session
                $_SESSION['id_username'] = $cusID;

                // Auto-submit form to redirect to invoice page
                echo "<form id='auto_submit_form' method='post' action='invoice.php'>
                    <input type='hidden' name='id_invoice' value='$InvID'>
                    <input type='hidden' name='id_receiver' value='$recv_id'>
                </form>";

                // Use JavaScript to trigger form submission
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('auto_submit_form').submit();
                    });
                </script>";
            } else {  
                header("Location: ./cart.php");
            }
        }
    } else {
        header("Location: ./cart.php");
    }
}
?>
