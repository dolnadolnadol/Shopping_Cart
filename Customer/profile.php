<?php
include('./component/session.php');

$cx = mysqli_connect("localhost", "root", "", "shopping");

$username = $_SESSION['username'];

$query = "SELECT * FROM customer WHERE UserName = '$username'";
$result = mysqli_query($cx, $query);

if (!$result) {
    die("Error fetching user data: " . mysqli_error($cx));
}

$user_data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {;
    $uid = $_POST['id_customer'];
    $new_username = $_POST['username'];
    $new_tel = $_POST['tel'];
    $new_address = $_POST['address'];

    $update_query = "UPDATE customer SET UserName = '$new_username' ,Tel = '$new_tel', Address = '$new_address' WHERE CusID = '$uid'";
    $update_result = mysqli_query($cx, $update_query);

    if (!$update_result) {
        die("Error updating user data: " . mysqli_error($cx));
    }

    $_SESSION['username'] = $new_username;
    $_SESSION['tel'] = $new_tel;

}

mysqli_close($cx);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setting</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .body-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60vh;
        }

        .container {
            padding: 20px 60px 70px 60px;
            text-align: left;
            width: 800px;
            background-color: #fff; /* Added background color */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Added box-shadow */
            border-radius: 8px; /* Added border-radius for a rounded appearance */
            margin-top: 140px;
        }

        #head-text {
            font-size: 40px;
            margin-bottom: 10px;
        }

        #text-1 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3498db;
        }
         /* Overlay styles */
         .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 999;
        }

        .overlay-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        span {
            color: red;
        }
    </style>
</head>

<body>
    <?php include('./component/accessNavbar.php')?>
    <!-- <?php include('./component/backButton.php')?> -->
    <div class="body-container">
        <div class="container">
            <p id='head-text'>Edit Profile</p>
            <p id='text-1'>Change your basic account here. You may also want to edit your profile</p>

            <form id="profileForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <input type="hidden" name="id_customer" value="<?php echo $user_data['CusID'] ?>">
                
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $user_data['Username'] ?>" readonly>

                <label for="password">Password:</label>
                <input type="password" name="password" value="<?php echo $user_data['Password'] ?>" readonly>

                <label for="tel">Tel:<span>*</span></label>
                <input type="tel" name="tel" value="<?php echo $user_data['Tel']?>" required>

                <label for="address">Address:<span>*</span></label>
                <textarea name="address" rows="3" required><?php echo $user_data['Address']; ?></textarea>

                <button type="submit" onclick="showOverlay()">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" >
        <div class="overlay-content">
            <p>Save successful!</p>
            <button onclick="hideOverlay()">OK</button>
        </div>
    </div>

    <script>
        document.getElementById('profileForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.action, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Form submitted successfully
                    showOverlay();
                } else {
                    // Handle error
                    console.error('Error submitting form');
                }
            };
            xhr.send(formData);
        });

        function showOverlay() {
            document.getElementById('overlay').style.display = 'flex';
            // Delay the hideOverlay function
            setTimeout(hideOverlay, 10000); // Adjust the time (in milliseconds) as needed
        }

        function hideOverlay() {
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</body>

</html>
