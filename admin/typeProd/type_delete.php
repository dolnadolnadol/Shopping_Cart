<?php
    include_once '../../dbConfig.php'; 

    if (isset($_POST['id_type'])) {
        try {
            $code = $_POST['id_type'];
            $stmt = mysqli_query($conn, "UPDATE product_type SET hide = CASE WHEN hide = 0 THEN 1 ELSE 0 END WHERE typeId = '$code'");
            echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
        } catch (mysqli_sql_exception $e) {
            echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete type due to related records in the database.');
            window.location.href = './type_index.php';</script>";
        }
    }
    else if (isset($_POST['list_id_type'])) {
        $list_ids = $_POST['list_id_type'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            try {
                $code = mysqli_real_escape_string($conn, $code);
                $stmt = mysqli_query($conn, "UPDATE product_type SET hide = CASE WHEN hide = 0 THEN 1 ELSE 0 END WHERE typeId = '$code'");
                echo "Delete data with Type ID = <font color=red> '$code' </font> is Successful.<br>";
            } catch (mysqli_sql_exception $e) {
                echo "<script type='text/javascript'>alert('Deletion failed: Cannot delete type due to related records in the database.');
                window.location.href = './type_index.php';</script>";
            }
        }
    }

    mysqli_close($conn);

    header("location: ./type_index.php");
?>
