<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include_once '../dbConfig.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $sex = $_POST['sex'];
    $tel = $_POST['tel'];

    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $select_user = "SELECT * FROM customer WHERE Username = '$username'";
    $run_qry = mysqli_query($conn, $select_user);
    if (mysqli_num_rows($run_qry) == 0) {
        $stmt_1 = mysqli_query($conn, "INSERT INTO customer(fname, lname, Sex ,Tel, Email, Username , Password ,authority, deleteStatus)
            VALUES('$fname','$lname', '$sex','$tel', '$email', '$username' , '$password', 'users', '1');");

        if (!$stmt_1) {
            // echo "<script>
            //     setTimeout(function(){
            //         alert('Error!');
            //         window.location.href = './login.php';
            //     }, 1000);</script>";

            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Failed to Register!', 'Register is Failed!', 'error')
                .then(() => {
                    window.location.href = './login.php';
                });
        });
      </script>";
        } else {
            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Registeration Successful!', 'Register is successful!', 'success')
                .then(() => {
                    window.location.href = './login.php';
                });
        });
      </script>";
        }
        // header("Location: login.php");

    } else {
        // echo "<script>
        //     setTimeout(function(){
        //         alert('User Have Already!');
        //         window.location.href = './login.php';
        //     }, 1000);
        //     </script>";
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Failed to Register!', 'User Have Already!', 'error')
                .then(() => {
                    window.location.href = './login.php';
                });
        });
      </script>";
    }
}
?>