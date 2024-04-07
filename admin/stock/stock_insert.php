<?php 
header("location: ./stock_index.php");
include_once '../../dbConfig.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_FILES['files'])) {

        $a2 = $_POST['a2'];
        $a3 = $_POST['a3'];
        $a4 = $_POST['a4'];
        $a5 = $_POST['a5'];
        $a6 = $_POST['a6'];
        $a7 = $_POST['a7'];
        
        $targetDir = "../UploadImg/uploads/"; 
        $allowTypes = array('jpg','png','jpeg','gif'); 
        $fileNames = array_filter($_FILES['files']['name']); 
        $uploadedFiles = array();
        
        if(!empty($fileNames)) { 
            foreach($_FILES['files']['name'] as $key=>$val) {
                $fileName = basename($_FILES['files']['name'][$key]);
                $targetFilePath = $targetDir . $fileName; 
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                if(in_array($fileType, $allowTypes)) {
                    if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
                        $uploadedFiles[] = $targetFilePath; // Store filenames only
                    }
                }
            } 
        }

        // Prepare statement for non-file data
        $insertQuery = "INSERT INTO product(ProductName, Description, Price, Qty, cost, typeId, Photo) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sssssss", $a2, $a5, $a3, $a4, $a6, $a7, $photo);

            // Combine uploaded file names into a single string separated by commas
            $photo = implode(",", $uploadedFiles);

            // Execute statement
            if ($stmt->execute()) {
                echo 'Insert data is successful.';
            } else {
                echo 'Error inserting data: ' . $stmt->error;
            }

            // Close statement
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
