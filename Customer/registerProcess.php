<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $sex = $_POST['sex'];
        $tel = $_POST['tel'];
        $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $cx = mysqli_connect("localhost", "root", "", "shopping");
        $select_user = "SELECT * FROM customer WHERE Username = '$username'";
        $run_qry = mysqli_query($cx, $select_user);
        echo mysqli_num_rows($run_qry);
        if (mysqli_num_rows($run_qry) == 0) {
            echo '2222';
            $stmt = mysqli_query($cx, "INSERT INTO customer(CusName, Sex ,Tel , UserName , PassWord)
            VALUES('$fullname', '$sex','$tel' , '$username' , '$password');");
            echo '2233';
            if (!$stmt) {
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
        mysqli_close($cx);
    }
?>

