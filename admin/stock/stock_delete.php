<?php /* get connection */
    // header( "location: ./stock_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['id_stock'])){
        try{
        $code = $_POST['id_stock'];
        $stmt = mysqli_query($conn,"UPDATE product SET deleteStatus = '1' WHERE proId ='$code'");

        // check for errors
        echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
        echo "<a href='stock_index.php' 
            style='
            padding: 9px 14px;
            color: #ef476f;             
            text-decoration: none;
            margin-right: 5px;
            '>กลับหน้าหลัก</a>";
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
            $stmt = mysqli_query($conn, "UPDATE product SET deleteStatus = '1' WHERE proId ='$code'");

            // check for errors
            echo "Delete data with RecID = <font color=red> '$code' </font> is Successful.<br>";

            }catch (mysqli_sql_exception $e) {
                echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete customer due to related records in the database.');
                window.location.href = './stock_index.php';</script>";
            }
        }
        echo "<a href='stock_index.php' 
        style='
        padding: 9px 14px;
        color: #ef476f;             
        text-decoration: none;
        margin-right: 5px;
        '>กลับหน้าหลัก</a>";
    }
    /* close connection */
    header("location: ./stock_index.php");
    mysqli_close($conn);
?>