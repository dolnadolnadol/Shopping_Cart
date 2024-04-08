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
    <?php
        include_once '../../dbConfig.php'; 
        $typeId = $_POST['id_type'];
        $cur = "SELECT * FROM product_type 
                WHERE typeId = $typeId
                ORDER BY hide";
        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
    ?>
    <form method="post" action="type_save_update.php">
        <h1>เปลี่ยนข้อมูลประเภทสินค้าที่ต้องการ</h1>

        <input type="hidden" id="a1" name="a1" value="<?php echo $row['typeId']; ?>">

        <label for="a2">ชื่อประเภทสินค้า:</label>
        <input type="text" id="a2" name="a2" maxlength="20" required value="<?php echo $row['typeName']; ?>">
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
                const numberAndTextRegex = /^[a-zA-Zก-๙เแโใไะาีึืุูเแโใไ็่้๊๋0-9\s]+$/;
                const errors = [];
                if (!numberAndTextRegex.test(productName)) {
                    errors.push('ชื่อประเภทสินค้า: ต้องเป็นตัวอักษรและตัวเลขเท่านั้น');
                }
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