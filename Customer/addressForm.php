<?php include('./component/session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address</title>
</head>
<body>
    <form id="profileForm" method="post" action="accessInvoice.php">
    <?php 
        if(isset($_POST['id_customer'])) {
            $uid = $_POST['id_customer'];
            
            $cx =  mysqli_connect("localhost", "root", "", "shopping");
            $query_address = "SELECT * FROM receiver 
            INNER JOIN receiver_detail ON receiver.RecvID = receiver_detail.RecvID  
            WHERE receiver_detail.CusID = '$uid'";
            $result_address = mysqli_query($cx, $query_address);
            if(mysqli_num_rows($result_address) > 0){
                // Fetch a single row from the result set
                $row = mysqli_fetch_assoc($result_address);
                
            }            
        }
       
        ?>

        <label for="username">Fullname :</label>
        <input type="text" name="fname" value="<?php echo $row['RecvFName'] ?? ''; ?>">
        <input type="text" name="lname" value="<?php echo $row['RecvLName'] ?? ''; ?>">

        <label for="tel">Tel:<span>*</span></label>
        <input type="tel" name="tel" value="<?php echo $row['Tel'] ?? ''; ?>">
        <label for="address">Address:<span>*</span></label>
        <textarea name="address" rows="3"><?php echo $row['Address'] ?? ''; ?></textarea>


    <!-- สำหรับ Guest จะมีเเค่ Session cart -->
    <?php if(isset($_POST['cart'])) :?>

        <input type='hidden' name='cart' value='<?php echo json_encode($_SESSION['cart']); ?>'>
            <input class='buy-button' type='submit' value='ชำระเงิน'>
        </form>

    
    <!-- สำหรับ User  -->
    <?php elseif(isset($_POST['id_customer'])):?>
            <input type='hidden' name='id_customer' value='<?php echo $uid; ?>'>
            <input class='buy-button' type='submit' value='ชำระเงิน'>
        </form>

    <?php else:?>
        <p>Oops Something went wrong</p>
        <?php echo 'header("Location: ./cart.php")'; ?>
    <?php endif?>
</body>
</html>

