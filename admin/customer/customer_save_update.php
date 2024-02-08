<?php /* get connection */
    header( "location: ./customer_index.php");
    $conn = mysqli_connect("localhost", "root", "", "shopping");
    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a4 = $_POST['a4'];
    $a5 = $_POST['a5'];
    $a6 = $_POST['a6'];
    /* run update */
    $stmt = mysqli_query($conn, "UPDATE customer SET CusName = '$a2', sex='$a3', Address='$a4', tel='$a5' , Username='$a6'
        WHERE CusID='$a1'");

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