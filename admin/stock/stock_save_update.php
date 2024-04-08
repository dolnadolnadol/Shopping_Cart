<?php
    include_once '../../dbConfig.php';
    header("Location: ./stock_index.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0) {
            $stockId = $_POST['a1'];
            $productName = $_POST['a2'];
            $description = $_POST['a5'];
            $typeId = $_POST['a7'];
            $cost = $_POST['a6'];
            $price = $_POST['a3'];
            $qty = $_POST['a4'];
            $onHand = $_POST['on'];
            $photo = file_get_contents($_FILES["photo"]["tmp_name"]);
            $updateQuery = "UPDATE product 
                            SET ProductName = ?, 
                                Description = ?, 
                                Price = ?, 
                                Qty = ?, 
                                Photo = ?, 
                                cost = ?, 
                                typeId = ?
                            WHERE proId = ?";

            $stmt = $conn->prepare($updateQuery);
            if ($stmt) {
                $stmt->bind_param("ssssssii", $productName, $description, $price, $qty, $photo, $cost, $typeId, $stockId);

                if ($stmt->execute()) {
                    echo 'Update data is successful.';
                } else {
                    echo 'Error updating data: ' . $stmt->error;
                }

                $stmt->close();
            } else {
                echo 'Error preparing statement: ' . $conn->error;
            }
        } else {
            echo "No file";
        }
    }
?>