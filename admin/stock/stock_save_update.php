<?php /* get connection */
    // header( "location: ./stock_index.php");
    include_once '../../dbConfig.php'; 
    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a15 = $_POST['a15'];
    $a4 = $_POST['a4'];

    $stmt = mysqli_query($conn, "UPDATE product SET ProName = '$a2', Description = '$a15', PricePerUnit ='$a3', StockQty ='$a4'
        WHERE ProID ='$a1'");

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