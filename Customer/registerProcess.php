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

<<<<<<< HEAD
        $select_user = "SELECT * FROM account WHERE Username = '$username'";
        $run_qry = mysqli_query($conn, $select_user);
        if (mysqli_num_rows($run_qry) == 0) {
            $stmt_1 = mysqli_query($conn, "INSERT INTO customer(fname, lname, Sex ,Tel, deleteStatus)
            VALUES('$fname','$lname', '$sex','$tel', '1');");
=======
    $select_user = "SELECT * FROM customer WHERE Username = '$username'";
    $run_qry = mysqli_query($conn, $select_user);
    if (mysqli_num_rows($run_qry) == 0) {
        $stmt_1 = mysqli_query($conn, "INSERT INTO customer(fname, lname, Sex ,Tel, Email, Username , Password ,authority)
            VALUES('$fname','$lname', '$sex','$tel', '$email', '$username' , '$password', 'users');");
>>>>>>> 6286c0cfdf54875795f08f5831881c4e40c250e8

        if (!$stmt_1) {
            // echo "<script>
            //     setTimeout(function(){
            //         alert('Error!');
            //         window.location.href = './login.php';
            //     }, 1000);</script>";

<<<<<<< HEAD
            // $findByID = mysqli_query($conn, "SELECT CusID FROM customer WHERE Email = '$email'");
            // $row = mysqli_fetch_assoc($findByID);
            // $cusID = $row['CusID'];
            $cusID = mysqli_insert_id($conn);

            $stmt_2 = mysqli_query($conn, "INSERT INTO account (Email, Username , Password ,authority, CusID, deleteStatus)
            VALUES('$email', '$username' , '$password', 'users' , ' $cusID', '1' );");

            if (!$stmt_1 && !$stmt_2) {
                echo "<script>
                setTimeout(function(){
                    alert('Error!');
                    window.location.href = './login.php';
                }, 1000);</script>";
            } else {
                echo "<script>
                setTimeout(function(){
                    alert('Insert data is successful.');
                    window.location.href = './';
                }, 1000);</script>";
            }
            header("Location: login.php");

        }
        else {
=======
>>>>>>> 6286c0cfdf54875795f08f5831881c4e40c250e8
            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Failed to Register!', 'Register is Failed!', 'error')
                .then(() => {
                    window.location.href = './';
                });
        });
      </script>";
        } else {
            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Registeration Successful!', 'Register is successful!', 'success')
                .then(() => {
                    window.location.href = './';
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
                    window.location.href = './';
                });
        });
      </script>";
    }
}
?>