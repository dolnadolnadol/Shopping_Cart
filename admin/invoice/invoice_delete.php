<?php
// get connection
    include_once '../../dbConfig.php'; 

if (isset($_POST['total_id_order'])) {
    $code = $_POST['total_id_order'];

    // run delete
    $check_id = mysqli_query($conn, "UPDATE invoice SET deleteStatus = '1' WHERE invId ='$code'");

    // check for errors
    echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
    echo "<a href='invoice_index.php' 
        style='
        padding: 9px 14px;
        color: #ef476f;             
        text-decoration: none;
        margin-right: 5px;
        '>กลับหน้าหลัก</a>";
} else if (isset($_POST['list_id_order'])) {
    $list_ids = $_POST['list_id_order'];  
    $codesArray = explode(',', $list_ids);

    foreach ($codesArray as $code) {
        $code = mysqli_real_escape_string($conn, $code);

        // run delete
        $check_id = mysqli_query($conn, "UPDATE invoice SET deleteStatus = '1' WHERE invId ='$code'");
       
        // check for errors
        echo "Delete data with RecID = <font color=red> '$code' </font> is Successful.<br>";
    }
    echo "<a href='invoice_index.php' 
    style='
    padding: 9px 14px;
    color: #ef476f;             
    text-decoration: none;
    margin-right: 5px;
    '>กลับหน้าหลัก</a>";
}

// close connection
header("location: ./invoice_index.php");
mysqli_close($conn);
?>
