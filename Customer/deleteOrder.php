<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php
include_once '../dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderId = $_POST["id_order"];
    echo $orderId;
    $stmt = $conn->prepare("update `ordervalue` set deleteStatus = ? WHERE orderId = ?");
    if (!$stmt) {
        die("Error in prepare statement: " . $conn->error);
    }
    $num = 1;
    $stmt->bind_param("ii", $num, $orderId);
    $success = $stmt->execute();

    $stmt = $conn->prepare("update `orderkey` set deleteStatus = ? WHERE orderId = ?");
    if (!$stmt) {
        die("Error in prepare statement: " . $conn->error);
    }
    $stmt->bind_param("ii", $num, $orderId);
    $success = $stmt->execute();

    if ($success) {
        echo "sucess";
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Order Cancelled!', 'Order cancellation was successful!', 'success')
                .then((value) => {
                    if (value) {
                        window.location.href = './history.php';
                    }
                })};
            </script>";
    } else {
        echo "failes";
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            swal('Error!', 'Failed to cancel order!', 'error')
                .then((value) => {
                    if (value) {
                        window.location.href = './history.php';
                    }
                })};
            </script>";
    }
}
?>