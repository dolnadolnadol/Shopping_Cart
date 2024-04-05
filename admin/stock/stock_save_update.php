<?php /* get connection */
    // header( "location: ./stock_index.php");
    include_once '../../dbConfig.php'; 
    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a15 = $_POST['a15'];
    $a4 = $_POST['a4'];
    $a6 = $_POST['a6'];
    $a7 = $_POST['a7'];

    $stmt = mysqli_query($conn, "UPDATE product SET ProductName = '$a2', Description = '$a15', Price ='$a3', Qty ='$a4', typeId ='$a6', cost ='$a7'
        WHERE proId ='$a1'");

    if (!$stmt) {
        echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Update Fail !');
                        window.location.href = './stock_index.php';
                    }, 100);
                  </script>";
    } else {
        echo "<script type='text/javascript'>
                    setTimeout(function(){
                        alert('Update Successfully');
                        window.location.href = './stock_index.php';
                    }, 100);
                  </script>";
    }
    mysqli_close($conn);
?>