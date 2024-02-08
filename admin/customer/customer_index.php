<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: space-between;
            background-color: #f3f6f9;
            padding: 20px;
        }

        .check-all-label {
            display: inline-block;
            margin-right: 5px;
        }

        .delete-form {
            display: inline-block;
            margin-right: 5px;
        }

        #deleteButton {
            background-color: #ef476f;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #deleteButton:hover {
            background-color: #d64161;
        }

        .add-user-form {
            display: inline-block;
            margin-right: 5px;
        }

        #addUserButton {
            background-color: #4b93ff;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #addUserButton:hover {
            background-color: #3a75c4;
        }

        table {
            width: 100%;
            margin: auto;
            text-align: center;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f5f6f6;
            padding: 10px;
        }

        td {
            padding: 5px;
            border-bottom: 1px solid #ccc;
        }

        .action-buttons {
            display: flex;
            justify-content: space-around;
        }

        .action-button {
            display: inline-block;
        }

        .action-button img {
            width: 20px;
            height: 20px;
        }
    </style>
</head>

<body>
    <h1>User List</h1>
    <div class="container">
        <div>
            <input type="checkbox" id="checkAll" onchange="checkAll()">
            <label class="check-all-label">Check All</label>
            <form  id='deleteForm' class="delete-form" action="customer_delete_confirm.php" method="post">
                <input type="hidden" name="list_id_customer" id="selectedValues" value="">
                <input type="hidden" name="total_id_customer" id="selectedTotal" value="">
                <input type="submit" id="deleteButton" value="Delete User" disabled>
            </form>
        </div>
        <div>
            <form class="add-user-form" action="customer_insert_form.html" method="post">
                <input type="submit" id="addUserButton" value="Add User">
            </form>
            <br>
        </div>
    </div>

    <?php
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $cur = "SELECT * FROM Customer";
    $msresults = mysqli_query($cx, $cur);

    echo "<center>";
    echo "<div>
        <table>
            <tr>
                <th><img src='http://localhost/phpmyadmin/themes/pmahomme/img/arrow_ltr.png'></th>
                <th>ID</th>
                <th>Name</th>
                <th>Sex</th>
                <th>Address</th>
                <th>Tel</th>
                <th>Username</th>
                <th>Action</th>
            </tr>";

    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['CusID']}'></td>
                    <td>{$row['CusID']}</td>
                    <td>{$row['CusName']}</td>
                    <td>{$row['Sex']}</td>
                    <td>{$row['Address']}</td>
                    <td>{$row['Tel']}</td>
                    <td>{$row['Username']}</td>
                    <td class='action-buttons'>
                        <form class='action-button' action='customer_update.php' method='post'>  
                            <input type='hidden' name='id_customer' value={$row['CusID']}>
                            <input type='image' alt='update' src='pen-solid.svg'>
                        </form>
                        <form class='action-button' action='customer_delete_confirm.php' method='post'>
                            <input type='hidden' name='id_customer' value={$row['CusID']}>
                            <input type='image' alt='delete' src='trash-solid.svg'>
                        </form>
                    </td>
                </tr>";
        }
    }
    echo "</table></div>";
    echo "</center>";
    mysqli_close($cx);
    ?>
<script>
        function updateDeleteButtonStatus() {
            var checkboxes = document.getElementsByName('checkbox[]');
            var deleteButton = document.getElementById('deleteButton');
            var selectedValuesInput = document.getElementById('selectedValues');

            var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
            var enableDeleteButton = checkedCheckboxes.length > 0;

            deleteButton.disabled = !enableDeleteButton;

            // Update the hidden input field with the values of checked checkboxes
            var checkboxValues = checkedCheckboxes.map(checkbox => checkbox.value);
            selectedValuesInput.value = checkboxValues.join(',');
        }

        var individualCheckboxes = document.getElementsByName('checkbox[]');
        for (var i = 0; i < individualCheckboxes.length; i++) {
            individualCheckboxes[i].addEventListener('change', function () {
                updateDeleteButtonStatus(); // Update deleteButton's status
            });
        }

        function checkAll() {
            var checkboxes = document.getElementsByName('checkbox[]');
            var checkAllCheckbox = document.getElementById('checkAll');
            var deleteButton = document.getElementById('deleteButton');
            var checkboxValues = [];

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = checkAllCheckbox.checked;
                if (checkboxes[i].checked) {
                    checkboxValues.push(checkboxes[i].value);
                }
            }

            // Join values into a comma-separated string
            document.getElementById('deleteForm').elements['selectedValues'].value = checkboxValues.join(',');
            var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
            var enableDeleteButton = checkedCheckboxes.length > 0;

            deleteButton.disabled = !enableDeleteButton;
        }
    </script>
</body>
</html>
