<?php
include('./component/session.php');
include_once '../dbConfig.php';
// include('../logFolder/AccessLog.php');
// include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Manage Order to send */
    if (isset($_POST['id_order']) && isset($_POST['id_customer'])) {
        $cusID = $_POST['id_customer'];
        $orderId = $_POST['id_order'];
        $deli = $_POST['id_deli'];

        $stmt = $conn->prepare("UPDATE product
        JOIN ordervalue ON ordervalue.proId = product.proId
        SET product.qty = product.qty - 1
        WHERE ordervalue.orderId = ?");
        $stmt->bind_param("i", $orderId);
        $success = $stmt->execute();


        $payer_fname = $_POST['fname'];
        $payer_lname = $_POST['lname'];
        $payer_time = $_POST['time'];
        $slip = file_get_contents($_FILES["slip"]["tmp_name"]);

        $stmt = $conn->prepare("INSERT INTO receipt(cusId, orderId, fname, lname, time, slip)  VALUES(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $cusID, $orderId, $payer_fname, $payer_lname, $payer_time, $slip);
        $success = $stmt->execute();
        $lastID = mysqli_insert_id($conn);

        if (!$success) {
            die("Error inserting into payer: " . mysqli_error($conn));
        } else {
            if (isset($_POST['invoice-taxid']) && $_POST['invoice-taxid'] != '') {
                $taxid = $_POST['invoice-taxid'];
                $taxname = $_POST['invoice-name'];
                $taxaddr = $_POST['invoice-address'];

                $insert_invoice = "INSERT INTO invoice(cusId, orderId, DeliId, taxId, name, address)
                                    VALUES('$cusID', '$orderId', '$deli', '$taxid', '$taxname', '$taxaddr')";
                $insert_invoice_result = mysqli_query($conn, $insert_invoice);
                $invId = mysqli_insert_id($conn);
            }

            $statusde = "inprogress";
            $stmt = $conn->prepare("update orderdelivery set statusDeli = ? where DeliId = ?");
            $stmt->bind_param("si", $statusde, $deli);
            $success = $stmt->execute();

            $statusde = "Checking";
            $stmt = $conn->prepare("update orderkey set PaymentStatus = ? where orderid = ?");
            $stmt->bind_param("si", $statusde,  $orderId);
            $success = $stmt->execute();
            if ($success) {
                echo "<form id='auto_submit_form' method='post' action='bill.php'>
                <input type='hidden' name='id_order' value='$orderId'>
                <input type='hidden' name='id_deli' value='$deli'>
                <input type='hidden' name='id_inv' value='$invId'>
            </form>";

                // Use JavaScript to trigger form submission
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('auto_submit_form').submit();
                    });
                    </script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}
