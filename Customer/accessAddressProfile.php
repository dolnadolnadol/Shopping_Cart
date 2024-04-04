<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include('./component/session.php');

include_once '../dbConfig.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id_customer']) && isset($_POST['addrId'])) {
        $uid = $_POST['delete_id_customer'];
        $addrId = $_POST['addrId'];
    
        $delete_result_detail_head = mysqli_query($conn, "DELETE FROM address WHERE AddrId = '$addrId'");
        
        if ($delete_result_detail_head) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    swal('Delete successful!', 'Address deleted!', 'success')
                        .then(() => {
                            window.location.href = './profile.php';
                        });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    swal('Failed to Delete!', 'Address is in use!', 'error')
                        .then(() => {
                            window.location.href = './profile.php';
                        });
                });
            </script>";
        }
    }
    
    else {
        $recv_id = $_POST['id_receiver'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $tel = $_POST['tel'];

        if ($recv_id === null || $recv_id === '' && !isset($_POST['delete_id_customer'])) {
            $province = $_POST['province'];
            $city = $_POST['city'];
            $postalCode = $_POST['postal'];
            $new_address = $_POST['addr'];

            $insert_query_head = "INSERT INTO address (fname, lname, tel, Address , Province  , City , PostalCode, CusId) 
                VALUES('$fname','$lname','$tel','$new_address', '$province', '$city' , '$postalCode' , '$uid')";
            $insert_result_head = mysqli_query($conn, $insert_query_head);

            if ($insert_result_head) {
                $recv_id = mysqli_insert_id($conn);
            } else {
                die("Error inserting into receiver: " . mysqli_error($conn));
            }

            header("Location: ./profile.php");
        }
    }
}

 
?>
