<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    include_once '../dbConfig.php';

    $select_user = "SELECT * FROM customer where Username = '$username';";
    $run_qry = mysqli_query($conn, $select_user);
    if (mysqli_num_rows($run_qry) > 0) {
        while ($row = mysqli_fetch_assoc($run_qry)) {
            if (password_verify($password, $row['Password'])) {
                // echo "Password match!";
                $user = $row['Username'];
                $uid = $row['CusID'];
                $_SESSION['status'] = true;
                $_SESSION['id_username'] = $uid;
                $_SESSION['uid'] = $uid;
                unset($_SESSION['cart']);
                $_SESSION['auth'] = 'users';
                if ($row['authority'] == 'product-admin') {
                    $_SESSION['auth'] = 'product-admin';
                }
                if ($row['authority'] == 'permissions-admin') {
                    $_SESSION['auth'] = 'permissions-admin';
                }
                if ($row['authority'] == 'super-admin') {
                    $_SESSION['auth'] = 'super-admin';
                }
                header("Location: ./");
                exit();
            } else {
                // echo "<script>setTimeout(function(){
                //         alert('password does not match.');
                //         window.location.href = './login.php';
                //     }, 100);</script>";
                echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Failed to Login!', 'password does not match!', 'error')
                .then(() => {
                    window.location.href = './login.php';
                });
        });
      </script>";
            }
        }
    } else {
        header("Location: ./login.php");
    }
}
