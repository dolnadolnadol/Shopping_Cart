<?php
include('./component/session.php');

include_once '../dbConfig.php'; 

// echo $_POST['id_receiver'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_customer'])) {
        $id_customer = $_POST['id_customer'];
        $query_address = "SELECT * FROM address 
        INNER JOIN customer ON address.CusId = customer.CusId  
        WHERE address.CusId = '$id_customer'";
        $result_address = mysqli_query($conn, $query_address);

        // Check if there are rows returned
        if ($result_address && mysqli_num_rows($result_address) > 0) {
            $user_data = mysqli_fetch_assoc($result_address);
        } else {
            $user_data = null;  
        }

        if (!$result_address) {
            die("Error fetching user data: " . mysqli_error($conn));
        }
    }
}

 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProfileAddress</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="tel"]:focus {
            border-color: #3498db;
        }

        button[type="submit"] {
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }

        span {
            color: red;
        }
    </style>
</head>
<body>
    <form id="profileForm" method="post" action="./accessAddressProfile.php">
        <input type="hidden" name="id_customer" value="<?php echo $uid ?>">

        <label for="fname">First Name:<span>*</span></label>
        <input type="text" name="fname" value="">
        <label for="lname">Last Name:<span>*</span></label>
        <input type="text" name="lname" value="">
        <label for="tel">Tel:<span>*</span></label>
        <input type="text" name="tel" value="">

        <label for="addr">Address:<span>*</span></label>
        <input type="text" name="addr" value="">

        <label for="province">Province:<span>*</span></label>
        <input type="text" name="province" value="">

        <label for="city">City:<span>*</span></label>
        <input type="tel" name="city" value="">
        
        <label for="postal">Postal Code:<span>*</span></label>
        <input type="tel" name="postal" value="">

        <button type="submit">save</button>
        <button type="button" onclick="href:'./profile.php'"> ยกเลิก </button>
    </form>
</body>
</html>