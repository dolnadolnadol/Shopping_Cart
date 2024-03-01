<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .font-login {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            color: #333;
            margin-bottom: -10px;
        }

        .body-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background-color: #f4f4f4;
            padding: 50px 150px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 8px;
            color: #333;
            align-self: flex-start;
        }

        input {
            margin-bottom: 16px;
            padding: 8px;
            width: 250px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            width: 150px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .radio-input {
            margin-right: 10px;
            appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #333;
            border-radius: 50%;
            outline: none;
            cursor: pointer;
        }

        .radio-input:checked {
            background-color: #06D6B1;
            border-color: #06D6B1;
        }

        .main-container {
            margin-top:-50px;
        }
    </style>
</head>
<body>
    <?php include('./component/backLogin.php')?>
    <div class="main-container">
    <div class="font-login">
        <p>Register</p>
    </div>
    <div class="body-container">
        <div class="container">
            <form method="post" action="registerProcess.php">
                <label for="frist name">Frist name</label>
                <input type="text" id="frist name" name="fname" required>
                <label for="last name">Last name</label>
                <input type="text" id="last name" name="lname" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <label for="tel">Tel</label>
                <input type="tel" id="tel" name="tel" required>
                
                <label class="radio-label">
                    <input type="radio" class="radio-input" name="sex" value="M"> Male
                </label>   
                <label class="radio-label">
                    <input type="radio" class="radio-input" name="sex" value="F"> Female
                </label>

                <input type="submit" value="ลงทะเบียน">
            </form>          
        </div>
    </div>
    </div>
</body>
</html>
