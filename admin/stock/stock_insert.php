<?php 
header("location: ./stock_index.php");
include_once '../../dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0) {
        $a2 = $_POST['a2'];
        $a3 = $_POST['a3'];
        $a4 = $_POST['a4'];
        $a5 = $_POST['a5'];
        $a6 = $_POST['a6'];
        $a7 = $_POST['a7'];
        $photo = file_get_contents($_FILES["photo"]["tmp_name"]);

        $insertQuery = "INSERT INTO product(ProductName, Description, Price, Qty, cost, typeId, Photo) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        if ($stmt) {
            $stmt->bind_param("sssssss", $a2, $a5, $a3, $a4, $a6, $a7, $photo);

            if ($stmt->execute()) {
                echo 'Insert data is successful.';
            } else {
                echo 'Error inserting data: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            echo 'Error preparing statement: ' . $conn->error;
        }
        
        echo "<a href='stock_index.php'>Back to Main Page</a>";
    } else {
        echo "No files uploaded.";
    }
}
?>
