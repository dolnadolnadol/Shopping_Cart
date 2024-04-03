<?php /* POST connection */
    // header( "location: ./customer_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['id_customer'])){
        $code = $_POST['id_customer'];
        $recvID = $_POST['id_receiver'];
        try{
        $stmt_receiver_detail = mysqli_query($conn, "DELETE FROM receiver_detail WHERE CusID='$code'");
        $stmt_receiver = mysqli_query($conn, "DELETE FROM receiver WHERE RecvID='$recvID'");
        $stmt_account_customer = mysqli_query($conn, "DELETE FROM account WHERE CusID='$code'");
        $stmt_customer = mysqli_query($conn, "DELETE FROM customer WHERE CusID='$code'");
        /* check for errors */
        if (!$stmt_receiver_detail || !$stmt_receiver || !$stmt_customer) {
            echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Fail !');
                        window.location.href = './customer_index.php';
                    }, 100);
                  </script>";
        } else {
            echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Successfully');
                        window.location.href = './customer_index.php';
                    }, 100);
                  </script>";
        }
        }catch (mysqli_sql_exception $e) {
            echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete customer due to related records in the database.');
            window.location.href = './customer_index.php';</script>";
        }
    }   
    else if (isset($_POST['list_id_customer'])){
        $list_ids = $_POST['list_id_customer'];  
        $recvID = $_POST['id_receiver'];
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            $code = mysqli_real_escape_string($conn, $code);
            try{
            $stmt_receiver_detail = mysqli_query($conn, "DELETE FROM receiver_detail WHERE CusID='$code'");
            $stmt_receiver = mysqli_query($conn, "DELETE FROM receiver WHERE RecvID='$recvID'");
            $stmt_customer = mysqli_query($conn, "DELETE FROM customer WHERE CusID='$code'");

            /* check for errors */
            if (!$stmt_receiver_detail || !$stmt_receiver || !$stmt_customer) {
                echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Fail ! This customer have order or invoice that does not complete.');
                        window.location.href = './customer_index.php';
                    }, 100);
                  </script>";
            }

            /* check for errors */
            else {
                    echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Successfully');
                        window.location.href = './customer_index.php';
                    }, 100);
                  </script>";
                }
            }catch (mysqli_sql_exception $e) {
                echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete customer due to related records in the database.');
                window.location.href = './customer_index.php';</script>";
            }
        }
    }
    /* close connection */
    mysqli_close($conn);
?>