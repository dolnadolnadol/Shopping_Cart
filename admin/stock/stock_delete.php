<?php /* get connection */
    // header( "location: ./stock_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['id_stock'])){
        try{
        $code = $_POST['id_stock'];
        $stmt = mysqli_query($conn,"DELETE FROM product WHERE proId ='$code'");
        if (!$stmt) { 
            echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Fail !');
                        window.location.href = './stock_index.php';
                    }, 100);
                  </script>";
        } else {
            echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Successfully');
                        window.location.href = './stock_index.php';
                    }, 100);
                  </script>";
        }
    }catch (mysqli_sql_exception $e) {
            echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete customer due to related records in the database.');
            window.location.href = './stock_index.php';</script>";
        }
    }
    else if (isset($_POST['list_id_stock'])){
        $list_ids = $_POST['list_id_stock'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            try{
            $code = mysqli_real_escape_string($conn, $code);
            $stmt = mysqli_query($conn, "DELETE FROM product WHERE proId ='$code'");
    
            /* check for errors */
            if (!$stmt) {
                echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Fail !');
                        window.location.href = './stock_index.php';
                    }, 100);
                  </script>";
            } else {
                echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Delete Successfully');
                        window.location.href = './stock_index.php';
                    }, 100);
                  </script>";
            }
            }catch (mysqli_sql_exception $e) {
                echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete customer due to related records in the database.');
                window.location.href = './stock_index.php';</script>";
            }
        }
    }
    /* close connection */
    mysqli_close($conn);
?>