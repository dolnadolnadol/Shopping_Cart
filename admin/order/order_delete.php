<?php /* get connection */
    header( "location: ./order_index.php");
    $conn = mysqli_connect("localhost", "root", "", "shopping");
    if (isset($_POST['total_id_order'])){
        $code = $_POST['total_id_order'];
        /* run delete */
        $stmt1 = mysqli_query($conn, "DELETE FROM receive_detail WHERE RecID ='$code'");
        $stmt2 = mysqli_query($conn, "DELETE FROM receive WHERE RecID ='$code'");
        /* check for errors */
        if (!$stmt) { 
            /* error */
            echo "Error deleting data with RecID = '$code'";
        } else {
            echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
            echo "<a href='order_index.php' 
                style='
                padding: 9px 14px;
                color: #ef476f;             
                text-decoration: none;
                margin-right: 5px;
                '>กลับหน้าหลัก</a>";
        }
    }
    else if (isset($_POST['list_id_order'])){
        $list_ids = $_POST['list_id_order'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            $code = mysqli_real_escape_string($conn, $code);
            $stmt1 = mysqli_query($conn, "DELETE FROM receive_detail WHERE RecID ='$code'");
            $stmt2 = mysqli_query($conn, "DELETE FROM receive WHERE RecID ='$code'");
    
            /* check for errors */
            if (!$stmt1 && !$stmt2) {
                /* error */
                echo "Error deleting data with RecID = '$code'";
            } else {
                echo "Delete data with RecID = <font color=red> '$code' </font> is Successful.<br>";
            }
        }
        echo "<a href='order_index.php' 
        style='
        padding: 9px 14px;
        color: #ef476f;             
        text-decoration: none;
        margin-right: 5px;
        '>กลับหน้าหลัก</a>";
    }
    /* close connection */
    mysqli_close($conn);
?>