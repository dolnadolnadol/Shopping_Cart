<?php 
    $conn = mysqli_connect("localhost", "root", "", "shopping");   
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a4 = $_POST['a4'];
    $a5 = $_POST['a5'];
    $a6 = $_POST['a6'];
    $a7 = $_POST['a7'];


    $password  = password_hash($_POST['a7'], PASSWORD_DEFAULT);

    /* run insert */
    $stmt = mysqli_query($conn, "INSERT INTO customer(CusName, sex, Address, Tel , UserName , PassWord)
        VALUES('$a2', '$a3', '$a4', '$a5' , '$a6' , '$password');");

    if (!$stmt) {
        echo "Error";
    } else {
        echo 'Insert data = is Successful.';
    }
    echo "<a href='customer_index.php' 
    style='
    padding: 9px 14px;
    color: #ef476f;             
    text-decoration: none;
    margin-right: 5px;
    '>กลับหน้าหลัก</a>";
    mysqli_close($conn);
?>