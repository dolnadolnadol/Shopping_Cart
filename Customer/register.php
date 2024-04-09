<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
        }

        .container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #555;
        }

        input {
            margin-bottom: 20px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            padding: 12px 0;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        /* Styles for radio buttons and labels */
        .radio-group {
            margin-bottom: 20px;
            display: flex !important;
        }

        .radio-label {
            display: inline-block;
            margin-right: 20px;
            color: #555;
            cursor: pointer;
        }

        .radio-input {
            margin-right: 5px;
            background-color: blue;
        }

        .radio-input:checked+.radio-label {
            color: #333;
        }

        /* Responsive styles */
        @media screen and (max-width: 480px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include('./component/backLogin.php') ?>
    <div class="container">
        <h2>Register</h2>
        <form id="form" method="post">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div id="passwordWarning" style="color: red; margin-bottom: 12px;"></div>

            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" required>

            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" required>

            <label for="tel">Tel</label>
            <input type="text" id="tel" name="tel" required>

            <label>Gender</label>
            <div class="radio-group">
                <input type="radio" id="male" class="radio-input" name="sex" value="M" required>
                <label for="male" class="radio-label">Male</label>

                <input type="radio" id="female" class="radio-input" name="sex" value="F">
                <label for="female" class="radio-label">Female</label>
            </div>
            <input type="submit" value="Register">
        </form>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent the default form submission

            const form = event.target;
            const formData = new FormData(form);

            // console.log(form); // Check if the form is correctly selected
            // console.log([...formData.entries()]); // Log FormData entries to see if any data is captured
            // console.log(formData); 
            const password = formData.get('password');

            const encoder = new TextEncoder();
            const passwordBuffer = encoder.encode(password);
            const hashBuffer = await crypto.subtle.digest('SHA-256', passwordBuffer);
            const hashedPassword = Array.from(new Uint8Array(hashBuffer)).map(b => b.toString(16).padStart(2, '0')).join('');
            // Hash the password
            // const hashedPassword = await bcrypt.hash(password, 10); // Adjust the salt rounds as needed

            // // Set the hashed password back to the FormData object
            formData.set('password', hashedPassword);


            try {
                const jsonObject = {};
                for (const [key, value] of formData.entries()) {
                    jsonObject[key] = value;
                }
                const jsonString = JSON.stringify(jsonObject);
                const response = await fetch('http://localhost:3001/api/users/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: jsonString
                });

                if (response.ok) {
                    swal('Registration Successful!', 'Registration is successful!', 'success')
                        .then(() => {
                            window.location.href = './login.php';
                        });
                } else {
                    swal('Failed to Register!', 'Registration failed!', 'error')
                        .then(() => {
                            // window.location.href = './login.php';
                        });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
        const passwordInput = document.getElementById('password');
        const passwordWarning = document.getElementById('passwordWarning');
        const submitButton = document.querySelector('input[type="submit"]');

        passwordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const containsUppercase = /[A-Z]/.test(password);
            const containsLowercase = /[a-z]/.test(password);
            const containsNumber = /\d/.test(password);
            const isLengthValid = password.length >= 8;

            let warningMessage = '';

            if (!containsUppercase) {
                warningMessage += 'Password must contain at least one uppercase letter.<br>';
            }
            if (!containsLowercase) {
                warningMessage += 'Password must contain at least one lowercase letter.<br>';
            }
            if (!containsNumber) {
                warningMessage += 'Password must contain at least one number.<br>';
            }
            if (!isLengthValid) {
                warningMessage += 'Password must be at least 8 characters long.<br>';
            }

            passwordWarning.innerHTML = warningMessage;
            if (warningMessage) {
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        });
    </script>
</body>

</html>