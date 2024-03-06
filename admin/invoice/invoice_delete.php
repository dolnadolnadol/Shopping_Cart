<?php /* get connection */
    // header( "location: ./invoice_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['total_id_invoice'])){
        $code = $_POST['total_id_invoice'];
        /* run delete */
        try{
        $stmt1 = mysqli_query($conn, "DELETE FROM invoice_detail WHERE InvID ='$code'");
        $stmt2 = mysqli_query($conn, "DELETE FROM invoice WHERE InvID ='$code'");
        /* check for errors */
        if (!$stmt) { 
            echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Fail !');
                        window.location.href = './invoice_index.php';
                    }, 100);
                  </script>";
        } else {
            echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Successfully');
                        window.location.href = './invoice_index.php';
                    }, 100);
                  </script>";
        }}catch (mysqli_sql_exception $e) {
            echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete customer due to related records in the database.');
            window.location.href = './invoice_index.php';</script>";
        }
    }
    else if (isset($_POST['list_id_invoice'])){
        $list_ids = $_POST['list_id_invoice'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            try{
            $code = mysqli_real_escape_string($conn, $code);
            $stmt1 = mysqli_query($conn, "DELETE FROM invoice_detail WHERE InvID ='$code'");
            $stmt2 = mysqli_query($conn, "DELETE FROM invoice WHERE InvID ='$code'");
    
            /* check for errors */
            if (!$stmt1 && !$stmt2) {
                echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Fail ! This customer have order or invoice that does not complete.');
                        window.location.href = './invoice_index.php';
                    }, 100);
                  </script>";
            } else {
                echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Successfully');
                        window.location.href = './invoice_index.php';
                    }, 100);
                  </script>";
            }}catch (mysqli_sql_exception $e) {
                echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete customer due to related records in the database.');
                window.location.href = './invoice_index.php';</script>";
            }
        }
    }
    /* close connection */
    mysqli_close($conn);
?>