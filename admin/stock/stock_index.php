<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock List</title>
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
    <h1>Stock List</h1>
    <div class="container">
        <div>
            <input type='checkbox' id='checkAll' onchange='checkAll()'>
            <label class="check-all-label">Check All</label>
            <form id='deleteForm' class="delete-form" action='stock_delete_confirm.php' method='post'>
                <input type='hidden' name='list_id_stock' id='selectedValues' value=''>
                <input type='hidden' name='total_id_stock' id='selectedTotal' value=''>
                <input type='submit' id='deleteButton' value='Delete Product' disabled />
            </form>
        </div>
        <div>
            <form class="add-user-form" action='stock_insert_form.html' method='post'>
                <input type='submit' id="addUserButton" value='Add Product'/>
            </form>
            <br>
        </div>
    </div>

    <?php
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $cur = "SELECT * FROM product";
    $msresults = mysqli_query($cx, $cur);

    echo "<center>";
    echo "<div>
        <table>
            <tr>  
                <th></th>                   
                <th>ID</th>
                <th>ProductName</th>
                <th>ProductPerUnit</th>
                <th>StockQty</th>
                <th>Action</th>
            </tr>";

    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['ProID']}'></td>
                    <td>{$row['ProID']}</td>
                    <td>{$row['ProName']}</td>
                    <td>{$row['PricePerUnit']}</td>
                    <td>{$row['StockQty']}</td>
                    <td>
                        <form action='stock_update.php' method='post' style='display: inline-block;'>  
                            <input type='hidden' name='id_stock' value={$row['ProID']}>
                            <input type='image' alt='update' src='pen-solid.svg'/>
                        </form>
                        <form action='stock_delete_confirm.php' method='post' style='display: inline-block;'>
                            <input type='hidden' name='id_stock' value={$row['ProID']}>
                            <input type='image' alt='delete' src='trash-solid.svg'/>
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
</script>
<script>
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
