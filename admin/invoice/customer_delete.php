<?php /* POST connection */
    // header( "location: ./index.php");
    $conn = mysqli_connect("localhost", "root", "", "shopping");
    if (isset($_POST['id_customer'])){
        $code = $_POST['id_customer'];
        /* run delete */
        // $stmt = odbc_prepra($conn, "DELETE FROM customer WHERE IDCust='$code'");
        $stmt = mysqli_query($conn,"DELETE FROM customer WHERE CusID='$code'");
        /* check for errors */
        if (!$stmt) { 
            /* error */
            echo "Error deleting data with CusID = '$code'";
        } else {
            echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
            echo "<a href='customer_index.php' 
                style='
                padding: 9px 14px;
                color: #ef476f;             
                text-decoration: none;
                margin-right: 5px;
                '>กลับหน้าหลัก</a>";
        }
    }
    else if (isset($_POST['list_id_customer'])){
        $list_ids = $_POST['list_id_customer'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            $code = mysqli_real_escape_string($conn, $code);
            $stmt = mysqli_query($conn,"DELETE FROM customer WHERE CusID='$code'");
    
            /* check for errors */
            if (!$stmt) {
                /* error */
                echo "Error deleting data with CusID = '$code'";
            } else {
                echo "Delete data with CusID = <font color=red> '$code' </font> is Successful.<br>";
            }
        }
        echo "<a href='customer_index.php' 
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