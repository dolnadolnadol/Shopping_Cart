<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            font-family: Sans-serif;
        }

        .font-login {
            display: flex;
            justify-content: center;
            font-size: 50px;
            color: #333;
            /* margin-bottom: 20px; */
        }

        .BigDiv {
            background-color: #f4f4f4;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

        }

        .body-container {
            margin: 0;
            height: 100%;
            width: 100%;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            padding: 0px 6rem 2rem 6rem;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 8px;
            font-size: 18px;
            color: #333;
            /* เปลี่ยนสีข้อความ label */
            align-self: flex-start;
        }

        input {
            margin-bottom: 16px;
            padding: 15px;
            width: 25rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #3498db;
            /* เปลี่ยนสีปุ่ม submit */
            color: #fff;
            /* เปลี่ยนสีข้อความปุ่ม submit */
            cursor: pointer;
            font-size: 16px;
            width: 10rem;
            padding: 10px 10px;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #348adb;
        }

        input[type="submit"]:focus {
            background-color: #346edb;
        }

        .container-register {
            text-align: center;
            font-size: 15px;
        }

        .container-register a {
            text-decoration: none;
            color: blue;
        }

        .container-register a:hover {
            color: red;
        }
    </style>
</head>

<body>
    <div class="body-container">
        <div class="BigDiv">
            <?php include('./component/backLogin.php') ?>
            <!-- <a href="index.php">กลับหน้าหลัก</a> -->
            <div class="container">
                <div class="font-login">
                    <p>Log In</p>
                </div>
                <form method="post" id="form">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <input type="submit" value="เข้าสู่ระบบ">
                </form>
                <!-- <form method="post" action="callauth.php">
                <button type="submit">signin</button>
            </form> -->
                <div class='container-register'>
                    <a href="register.php">ยังไม่มีบัญชีใช่หรือไม่? คลิกเพื่อสมัคร</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent the default form submission

            const form = event.target;
            const formData = new FormData(form);

            const password = formData.get('password');
            const encoder = new TextEncoder();
            const passwordBuffer = encoder.encode(password);
            const hashBuffer = await crypto.subtle.digest('SHA-256', passwordBuffer);
            const hashedPassword = Array.from(new Uint8Array(hashBuffer)).map(b => b.toString(16).padStart(2, '0')).join('');

            formData.set('password', hashedPassword);

            try {
                const jsonObject = {};
                for (const [key, value] of formData.entries()) {
                    jsonObject[key] = value;
                }
                const jsonString = JSON.stringify(jsonObject);
                const response = await fetch('http://localhost:3001/api/users/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: jsonString
                });

                if (response.ok) {
                    response.json().then(data => {
                        const uid = data.user[0].CusID;
                        const auth = data.user[0].authority;
                        // console.log(auth);

                        // AJAX request to assign value to PHP variable
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'assign_user.php'); // Adjust the URL accordingly
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                // Redirect to home page after assigning value
                                swal('Login Successful!', 'Login is successful!', 'success')
                                    .then(() => {
                                        window.location.href = './';
                                    });
                            } else {
                                // Handle error
                                swal('Failed to Assign Value!', 'Failed to assign value to PHP variable', 'error');
                            }
                        };
                        xhr.onerror = function() {
                            // Handle error
                            swal('Failed to Assign Value!', 'Failed to assign value to PHP variable', 'error');
                        };
                        xhr.send(`uid=${uid}&auth=${auth}`);
                    });
                } else {
                    swal('Failed to Login!', 'Login failed!', 'error')
                        .then(() => {
                            // window.location.href = './login.php';
                        });
                }

            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>

</html>