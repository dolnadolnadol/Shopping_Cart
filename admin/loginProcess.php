<?php
    include_once '../dbConfig.php'; 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // $select_user = "SELECT * FROM account WHERE Username = '$username'";
        // $run_qry = mysqli_query($conn, $select_user);
        // if (mysqli_num_rows($run_qry) > 0) {
        //     while ($row = mysqli_fetch_assoc($run_qry)) {
        //         if (password_verify($password, $row['Password'])) {
        //             echo "Password match!";
        //             $user = $row['Username'];
        //             $uid = $row['CusID'];
        //             $_SESSION['status'] = true;
        //             $_SESSION['id_username'] = $uid;
        //             unset($_SESSION['cart']);
        //             header("Location: ./index.php");

        //         } 
        //     }
        // }
        // else 
        if($username == 'admin' && $password == '123456'){
            $_SESSION['status'] = true;
            header("Location: ../admin/dashboard/dashboard.php");
        }
        else {
            header("Location: ./login.php");
        }
        mysqli_close($conn);
    }
?>

