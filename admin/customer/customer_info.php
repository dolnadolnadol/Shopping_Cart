<?php
    session_start();
    if($_SESSION['auth'] !== 'permissions-admin') {
        header("Location: ../notHavePage.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Info</title>

    <!-- Include necessary libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #cce0ff;
            /* Ocean-sky blue background color */
        }

        .invoice-form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            /* White background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Box shadow for a subtle lift */
        }

        .form-block {
            border: 1px solid #b3ccff;
            /* Light ocean-sky blue border color */
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #edf5ff;
            /* Light ocean-sky blue background color */
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #3366cc;
            /* Dark ocean-sky blue text color for labels */
        }

        input,textarea,
        select {
            color: #000;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #99c2ff;
            /* Light ocean-sky blue border color for inputs and selects */
            border-radius: 4px;
            text-align: left;
        }

        button {
            background-color: #007bff;
            /* Darker ocean-sky blue button color */
            color: #fff;
            /* White text color for the button */
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
            /* Slightly darker color on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

    </style>
</head>

<body>
    <?php  include_once '../../dbConfig.php';
        $cusId = $_POST['id_customer'];
        $cur = "SELECT * FROM customer
        WHERE CusID = '$cusId'";
        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
    ?>
    <div class="invoice-form">
        <h2 style="color: #007bff; text-align: center;">Customer Information</h2>
        <div class="form-block">
            <div class="form-group">
                <?php
                    echo "<label for='RecID'>Customer ID:</label>";
                    echo "<input type='text' value='{$row['CusID']}' readonly>";
                    echo "<label for='customerName'>Customer Name:</label>";
                    echo "<input type='text' value='{$row['fname']} {$row['lname']}' readonly>";
                    echo "<label for='customerName'>Tel:</label>";
                    echo "<input type='text' value='{$row['Tel']}' readonly>";
                    echo "<label for='customerName'>Email:</label>";
                    echo "<input type='text' value='{$row['Email']}' readonly>";
                    echo "<label for='customerName'>Username:</label>";
                    echo "<input type='text' value='{$row['Username']}' readonly>";
                ?>
            </div>
            <div class="form-group" style="color: #007bff">
                <label style="color: #007bff" for="customerName">Customer Address:</label>
                <?php 
                $cur = "SELECT *
                        FROM customer
                        INNER JOIN address ON address.CusId = customer.CusID
                        WHERE customer.CusID = '$cusId'";
                $msresults = mysqli_query($conn, $cur);
                if(mysqli_num_rows($msresults) > 0) {
                    echo "<select id='addressSelect' readonly onchange='updateAddress()'>";
                    $count = 1;
                    while($row = mysqli_fetch_assoc($msresults)) {
                        echo "<option value='{$row['Address']}, {$row['Province']}, {$row['City']}, {$row['PostalCode']}'";
                        if ($count === 1) {
                            echo " selected";
                        }
                        echo ">ที่อยู่ $count</option>";
                        $count++;
                    }
                    echo "</select>";
                } else {
                    echo "No address found for this customer.";
                }
                ?>
            </div>
            <div class="form-group" style="color: #007bff">
                <textarea id="selectedAddress" readonly rows="5"></textarea>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        updateAddress();
    });

    function updateAddress() {
        var select = document.getElementById("addressSelect");
        var selectedOption = select.options[select.selectedIndex];
        var addressInfo = selectedOption.value;
        document.getElementById("selectedAddress").value = addressInfo;
    }
</script>