<?php
    session_start();
    if($_SESSION['auth'] !== 'product-admin') {
        header("Location: ../notHavePage.php");
        exit;
    }
?>

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
        textarea, select {
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
    <form method="post" action="stock_insert.php" enctype="multipart/form-data">
        <h1>ใส่ข้อมูลสินค้าที่ต้องการ</h1>
        <label for="a2">ชื่อสินค้า:</label>
        <input type="text" id="a2" name="a2" maxlength="20" required>

        <label for="a5">รายละเอียดสินค้า:</label>
        <textarea id="a5" name="a5" rows="4" cols="50" maxlength="300" required></textarea>

        <label for="a7">ประเภทสินค้า:</label>
        <select id="a7" name="a7" maxlength="6" required>
            <option value="">กรุณาเลือกประเภทสินค้า</option>
            <?php
            include_once '../../dbConfig.php';
            $query2 = "SELECT * FROM product_type WHERE product_type.hide != '1'";
            $result2 = mysqli_query($conn, $query2);
            if (mysqli_num_rows($result2) > 0) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    echo "<option value='" . $row2['typeId'] . "'>" . $row2['typeName'] . "</option>";
                }
            }
            ?>
        </select>

        <label for="a6">ต้นทุน:</label>
        <input type="text" id="a6" name="a6" size="1" maxlength="6" required>

        <label for="a3">ราคาต่อหน่วย:</label>
        <input type="text" id="a3" name="a3" size="1" maxlength="6" required>

        <label for="a4">จำนวนสินค้า:</label>
        <input type="text" id="a4" name="a4" size="1" maxlength="6" required>
        
        <label for="files[]">อัพโหลดรูปภาพสินค้า:</label>
        <input type='file' name='files[]' required>
        
        <div >
            <center>
                <input type='button' value='ยกเลิก' onclick='window.history.back();' style='background-color: gray; color: #fff; padding: 10px 20px; border: none; cursor: pointer; margin-right:2rem; border-radius: 4px;'>
                <input type='submit' value='ยืนยัน' style='background-color: blue; color: #fff; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; margin-right:1rem;'>
            </center>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const productName = document.getElementById('a2').value;
                const productDescription = document.getElementById('a5').value;
                const productType = document.getElementById('a7').value;
                const productCost = document.getElementById('a6').value;
                const productPrice = document.getElementById('a3').value;
                const productQuantity = document.getElementById('a4').value;

                const textRegex = /^[a-zA-Zก-๙เแโใไะาีึืุูเแโใไ็่้๊๋\s]+$/;
                const numberRegex = /^[0-9]+$/;
                const numberAndtextRegex = /^[a-zA-Zก-๙เแโใไะาีึืุูเแโใไ็่้๊๋0-9\s]+$/;

                const maxLengthProductName = 20;
                const maxLengthProductDescription = 50;
                const maxLengthProductNum = 6;

                const errors = [];

                function checkMaxLength(value, maxLength, fieldName) {
                    if (value.length > maxLength) {
                        errors.push(`${fieldName}: ต้องไม่เกิน ${maxLength} ตัวอักษร`);
                    }
                }

                if (!numberAndtextRegex.test(productName)) {
                    errors.push('ชื่อสินค้า: ต้องเป็นตัวอักษรและตัวเลขเท่านั้น');
                }
                checkMaxLength(productName, maxLengthProductName, 'ชื่อสินค้า');

                if (!numberAndtextRegex.test(productDescription)) {
                    errors.push('รายละเอียดสินค้า: ต้องเป็นตัวอักษรและตัวเลขเท่านั้น');
                }
                checkMaxLength(productDescription, maxLengthProductDescription, 'รายละเอียดสินค้า');

                if (!numberRegex.test(productType)) {
                    errors.push('ประเภทสินค้า: ต้องกดเลือกเท่านั้น');
                }

                function validateNumericField(value, fieldName) {
                    if (!numberRegex.test(value)) {
                        errors.push(`${fieldName}: ต้องเป็นตัวเลขเท่านั้น`);
                    } else {
                        checkMaxLength(value, maxLengthProductNum, fieldName);
                    }
                }

                validateNumericField(productCost, 'ต้นทุน');
                validateNumericField(productPrice, 'ราคาต่อหน่วย');
                validateNumericField(productQuantity, 'จำนวนสินค้า');

                if (errors.length > 0) {
                    alert(errors.join('\n'));
                } else {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>