<?php
    include_once '../../../dbConfig.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CusID'])) {
    $cusID = mysqli_real_escape_string($conn, $_POST['CusID']);

    $query = "SELECT CusName FROM customer WHERE CusID = '$cusID'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Return product information as JSON
        echo json_encode([
            'cusName' => $row['CusName'],
        ]);
    } else {
        // Handle the case where the query fails
        echo json_encode([
            'cusName' => 'Error',
        ]);
    }
}
?>
