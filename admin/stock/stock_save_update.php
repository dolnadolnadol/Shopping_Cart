<?php
include_once '../../dbConfig.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stockId = $_POST['a1'];
    $productName = $_POST['a2'];
    $description = $_POST['a5'];
    $typeId = $_POST['a7'];
    $cost = $_POST['a6'];
    $price = $_POST['a3'];
    $qty = $_POST['a4'];
    $onHand = $_POST['on'];

    // Initialize photo variable
    $photo = '';

    // Check if either files or oldPhoto is set
    if (isset($_FILES['files']) || isset($_POST['OldPhoto'])) {
        $targetDir = "../UploadImg/uploads/";
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        $uploadedFiles = array();
        
        // Check if there are new files uploaded
        if (isset($_FILES['files'])) {
            foreach ($_FILES['files']['name'] as $key => $val) {
                $fileName = basename($_FILES['files']['name'][$key]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
                        $uploadedFiles[] = $targetFilePath;
                    }
                }
            }
        }

        // Set photo based on whether new files are uploaded or not
        if (!empty($uploadedFiles)) {
            $photo = implode(",", $uploadedFiles);
        } else {
            // Use oldPhoto if no new files uploaded
            $photo = $_POST['OldPhoto'];
        }
    }

    // Update query
    $updateQuery = "UPDATE product 
                    SET ProductName = ?, 
                        Description = ?, 
                        Price = ?, 
                        Qty = ?, 
                        Photo = ?, 
                        cost = ?, 
                        typeId = ?,
                    WHERE proId = ?";

    // Prepare statement
    $stmt = $conn->prepare($updateQuery);
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("sssissii", $productName, $description, $price, $qty, $photo, $cost, $typeId, $stockId);

        // Execute statement
        if ($stmt->execute()) {
            echo 'Update data is successful.';
        } else {
            echo 'Error updating data: ' . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo 'Error preparing statement: ' . $conn->error;
    }

    // Redirect to the main page
    header("Location: ./stock_index.php");
}
?>