<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
include_once '../dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = $_POST["id_order"];
    $stmt = $conn->prepare("DELETE FROM `ordervalue` WHERE orderId = ?");
    if (!$stmt) {
        die("Error in prepare statement: " . $conn->error);
    }
    $stmt->bind_param("i", $orderId);
    $success = $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM `orderkey` WHERE orderId = ?");
    if (!$stmt) {
        die("Error in prepare statement: " . $conn->error);
    }
    $stmt->bind_param("i", $orderId);
    $success = $stmt->execute();

    if ($success) {
        echo "<script>
            swal('Order Cancelled!', 'Order cancellation was successful!', 'success')
                .then((value) => {
                    if (value) {
                        window.location.href = './history.php';
                    }
                });
            </script>";
    } else {
        echo "<script>
            swal('Error!', 'Failed to cancel order!', 'error')
                .then((value) => {
                    if (value) {
                        window.location.href = './history.php';
                    }
                });
            </script>";
    }
}
?>
