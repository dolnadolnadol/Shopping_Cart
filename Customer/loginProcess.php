<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cx = mysqli_connect("localhost", "root", "", "shopping");

    $select_user = "SELECT * FROM customer WHERE UserName = '$username'";
    $run_qry = mysqli_query($cx, $select_user);
    if (mysqli_num_rows($run_qry) > 0) {
        while ($row = mysqli_fetch_assoc($run_qry)) {
            if (password_verify($password, $row['PassWord'])) {
                echo "Password match!";
                $user = $row['UserName'];
                session_start();
                $_SESSION['status'] = 'true';
                $_SESSION['username'] = $user;
                header("Location: index.php?login_success=true");
                exit(); 

            } else {
                echo "Password Not match!";
            }
        }
    }
    mysqli_close($cx);
}
?>

