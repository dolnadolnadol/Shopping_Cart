<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            /* height:100%; */
        }

        h1 {
            text-align: center;
            color: #3498db;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="tel"],
        input[type="file"],
        textarea {
            resize:none;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"],
        input[type="reset"],
        #back {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 12px;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover,
        #back:hover {
            background-color: #2980b9;
        }
        
    </style>
</head>
<body>
    <?php
        include_once '../../dbConfig.php';
        $stockId = $_POST['id_stock'];
        $cur = "SELECT * FROM product WHERE proId = '$stockId'";
        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
    ?>
    <form method="post" action="stock_save_update.php" enctype="multipart/form-data">
        <h1>ใส่ข้อมูลสินค้าที่ต้องการเปลี่ยนแปลง</h1>

        <input type='hidden' id="a1" name="a1" value="<?php echo $stockId; ?>">

        <label for="a2">ชื่อสินค้า:</label>
        <input type="text" id="a2" name="a2" value="<?php echo $row['ProductName']; ?>" maxlength="20" required>
        
        <label for="a5">รายละเอียดสินค้า:</label>
        <textarea id="a5" name="a5" rows="4" cols="50" required><?php echo $row['Description']; ?></textarea>  

        <label for="a7">ประเภทสินค้า:</label>
        <input type="text" id="a7" name="a7" value="<?php echo $row['typeId']; ?>" size="1" required>

        <label for="a6">ต้นทุน:</label>
        <input type="text" id="a6" name="a6" value="<?php echo $row['cost']; ?>" size="1" required>

        <label for="a3">ราคาต่อหน่วย:</label>
        <input type="text" id="a3" name="a3" value="<?php echo $row['Price']; ?>" size="1" required>

        <label for="a4">จำนวนสินค้า:</label>
        <input type="text" id="a4" name="a4" value="<?php echo $row['Qty']; ?>" size="1" required>

        <label>จำนวนสินค้า On Hand:</label>
        <input type="text" id="on" name="on" value="<?php echo $row['onHand']; ?>" size="1" readonly>
        
        <label for="files[]">อัพโหลดรูปภาพสินค้าเก่า:</label>
        <input type="text" name="OldPhoto" value="<?php echo $row['Photo']; ?>" readonly>
        <label for="files[]">อัพโหลดรูปภาพสินค้าใหม่:</label>
        <input type="file" name="files[]">
        
        <div >
            <center>
                <input type='button' value='ยกเลิก' onclick='window.history.back();' style='background-color: gray; color: #fff; padding: 10px 20px; border: none; cursor: pointer; margin-right:2rem; border-radius: 4px;'>
                <input type='submit' value='ยืนยัน' style='background-color: blue; color: #fff; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; margin-right:1rem;'>
            </center>
        </div>
    </form>
</body>
</html>
