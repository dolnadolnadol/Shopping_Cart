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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_user_query = "SELECT * FROM customer WHERE Username = ?";
    $stmt_check_user = mysqli_prepare($conn, $check_user_query);
    mysqli_stmt_bind_param($stmt_check_user, "s", $username);
    mysqli_stmt_execute($stmt_check_user);
    mysqli_stmt_store_result($stmt_check_user);

    if (mysqli_stmt_num_rows($stmt_check_user) == 0) {
        $insert_user_query = "INSERT INTO customer (fname, lname, Sex, Tel, Email, Username, Password, authority) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, 'users')";
        $stmt_insert_user = mysqli_prepare($conn, $insert_user_query);
        mysqli_stmt_bind_param($stmt_insert_user, "sssssss", $fname, $lname, $sex, $tel, $email, $username, $password);

        if (mysqli_stmt_execute($stmt_insert_user)) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        swal('Registration Successful!', 'Registration is successful!', 'success')
                            .then(() => {
                                window.location.href = './login.php';
                            });
                    });
                </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        swal('Failed to Register!', 'Registration failed!', 'error')
                            .then(() => {
                                window.location.href = './login.php';
                            });
                    });
                </script>";
        }

        mysqli_stmt_close($stmt_insert_user);
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    swal('Failed to Register!', 'User already exists!', 'error')
                        .then(() => {
                            window.location.href = './login.php';
                        });
                });
            </script>";
    }
    mysqli_stmt_close($stmt_check_user);
}

mysqli_close($conn);
?>
