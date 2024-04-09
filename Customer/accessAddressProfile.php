<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include('./component/session.php');

include_once '../dbConfig.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id_customer']) && isset($_POST['addrId'])) {
        // Delete address
        $uid = $_POST['delete_id_customer'];
        $addrId = $_POST['addrId'];
        
        $delete_query = "DELETE FROM address WHERE AddrId = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $addrId);
        mysqli_stmt_execute($stmt);
        
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>
                swal('Delete successful!', 'Address deleted!', 'success')
                    .then(() => {
                        window.location.href = './profile.php';
                    });
            </script>";
        } else {
            echo "<script>
                swal('Failed to Delete!', 'Address is in use!', 'error')
                    .then(() => {
                        window.location.href = './profile.php';
                    });
            </script>";
        }
    }
    else {
        // Insert or update address
        $recv_id = $_POST['id_receiver'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $tel = $_POST['tel'];

        if ($recv_id === null || $recv_id === '' && !isset($_POST['delete_id_customer'])) {
            $province = $_POST['province'];
            $city = $_POST['city'];
            $postalCode = $_POST['postal'];
            $new_address = $_POST['addr'];

            $insert_query = "INSERT INTO address (fname, lname, tel, Address , Province  , City , PostalCode, CusId) 
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sssssssi", $fname, $lname, $tel, $new_address, $province, $city, $postalCode, $uid);
            $insert_result = mysqli_stmt_execute($stmt);

            if ($insert_result) {
                $recv_id = mysqli_insert_id($conn);
                header("Location: ./profile.php");
            } else {
                echo "<script>
                    swal('Failed to Insert!', 'Error inserting into database!', 'error')
                        .then(() => {
                            window.location.href = './profile.php';
                        });
                </script>";
            }
        }
    }
}

 
?>
