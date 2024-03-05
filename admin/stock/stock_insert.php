<?php 
header( "location: ./stock_index.php");
include_once '../../dbConfig.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_FILES['files'])) {

        $a2 = $_POST['a2'];
        $a3 = $_POST['a3'];
        $a4 = $_POST['a4'];
        $a5 = $_POST['a5'];
        
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
                        $uploadedFiles[] = $targetFilePath;
                    }
                }
            } 
        }
        
        $insertValuesSQL = implode(", ", array_map(function($file) use ($conn) {
            return "('".mysqli_real_escape_string($conn, $file)."')";
        }, $uploadedFiles));

        
        $insertQuery = "INSERT INTO product(ProName, Description ,PricePerUnit, StockQty, Photo) 
                        VALUES ('$a2', '$a5', '$a3', '$a4', $insertValuesSQL)";

        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            echo 'Insert data is successful.';
        } else {
            echo 'Error inserting data: ' . mysqli_error($conn);
        }

        echo "<a href='stock_index.php'>Back to Main Page</a>";
    } else {
        echo "No files uploaded.";
    }
}
?>
