<?php
    include_once '../../dbConfig.php';
    header("Location: ./stock_index.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0 && !empty($_FILES["photo"]["tmp_name"])) {
            // Photo has been uploaded, proceed with updating including the photo
            $photo = file_get_contents($_FILES["photo"]["tmp_name"]);
        } else {
            // No photo uploaded, set $photo to NULL or any other default value
            $photo = NULL; // Change this to any default value you want
        }
        
        $stockId = $_POST['a1'];
        $productName = $_POST['a2'];
        $description = $_POST['a5'];
        $typeId = $_POST['a7'];
        $cost = $_POST['a6'];
        $price = $_POST['a3'];
        $qty = $_POST['a4'];
        $onHand = $_POST['on'];
        
        $updateQuery = "UPDATE product 
                        SET ProductName = ?, 
                            Description = ?, 
                            Price = ?, 
                            Qty = ?,
                            cost = ?, 
                            typeId = ?";
        
        // Only add the Photo field to the update query if a photo has been uploaded
        if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0 && !empty($_FILES["photo"]["tmp_name"])) {
            $updateQuery .= ", Photo = ?";
        }
        
        $updateQuery .= " WHERE proId = ?";
        
        $stmt = $conn->prepare($updateQuery);
        if ($stmt) {
            if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0 && !empty($_FILES["photo"]["tmp_name"])) {
                // Bind photo parameter if a photo has been uploaded
                $stmt->bind_param("ssssssi", $productName, $description, $price, $qty, $cost, $typeId, $photo, $stockId);
            } else {
                // No photo uploaded, bind parameters without photo
                $stmt->bind_param("ssssssi", $productName, $description, $price, $qty, $cost, $typeId, $stockId);
            }
        
            if ($stmt->execute()) {
                echo 'Update data is successful.';
            } else {
                echo 'Error updating data: ' . $stmt->error;
            }
        
            $stmt->close();
        } else {
            echo 'Error preparing statement: ' . $conn->error;
        }        
    }
?>