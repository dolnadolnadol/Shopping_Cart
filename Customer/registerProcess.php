<?php
include_once '../dbConfig.php'; 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];

        $username = $_POST['username'];
        $sex = $_POST['sex'];
        $tel = $_POST['tel'];
        $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $select_user = "SELECT * FROM customer_account WHERE Username = '$username'";
        $run_qry = mysqli_query($conn, $select_user);
        echo mysqli_num_rows($run_qry);
        if (mysqli_num_rows($run_qry) == 0) {
            $stmt_1 = mysqli_query($conn, "INSERT INTO customer(CusFName , CusLName, Sex ,Tel )
            VALUES('$fname', '$lname' ,'$sex','$tel');");


            $findByID = mysqli_query($conn, "SELECT CusID FROM customer WHERE CusFName = '$fname' AND CusLName = '$lname' ");
            $row = mysqli_fetch_assoc($findByID);
            $cusID = $row['CusID'];


            $stmt_2 = mysqli_query($conn, "INSERT INTO customer_account (UserName , PassWord , CusID)
            VALUES('$username' , '$password' , ' $cusID' );");

            if (!$stmt_1 && !$stmt_2) {
                echo "Error";
            } else {
                echo 'Insert data = is Successful.';
            }
            header("Location: login.php");
            exit(); 

        }
        else {
            echo "User Have Already!";
        }
         
    }
?>

