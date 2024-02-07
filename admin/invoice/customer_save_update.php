<?php /* get connection */
    header( "location: ./customer_index.php");
    $conn = mysqli_connect("localhost", "root", "", "mydb");
    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a4 = $_POST['a4'];
    $a5 = $_POST['a5'];
    $a6 = $_POST['a6'];
    /* run update */
    $stmt = mysqli_query($conn, "UPDATE customer SET custname = '$a2', sex='$a3', address='$a4', tel='$a5' , UserName='$a6'
        WHERE IDCust='$a1'");

    /* check for errors */
    if (!$stmt) {
        /* error */
        echo "Error";
    } else {
        echo "Update data = <font color=red> '$a1' </font> is Successful.";
    }

    /* close connection */
    mysqli_close($conn);
?>