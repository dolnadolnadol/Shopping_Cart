<?php 
header("location: ./type_index.php");
include_once '../../dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];

    $insertQuery = "UPDATE product_type SET typeName = ? WHERE typeId = $a1";
    $stmt = $conn->prepare($insertQuery);
    if ($stmt) {
        $stmt->bind_param("s", $a2);
        if ($stmt->execute()) {
            echo 'Insert data is successful.';
        } else {
            echo 'Error inserting data: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        echo 'Error preparing statement: ' . $conn->error;
    }
    echo "<a href='type_index.php'>Back to Main Page</a>";
}
?>
