<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock List</title>
</head>

<body>
    <h1>Stock List</h1>
    <div style='display: flex; justify-content: space-between; background-color: red; padding: 20px; border: 5px solid blue;'>
        <div>
            <input type='checkbox' id='checkAll' onchange='checkAll()'>
            <label class="container">Check All</label>
            <form id='deleteForm' action='stock_delete_confirm.php' method='get' style='display: inline-block; margin-right: 5px;'>
                <input type='hidden' name='list_id_stock' id='selectedValues' value=''>
                <input type='hidden' name='total_id_stock' id='selectedTotal' value=''>
                <input type='submit' id='deleteButton' value='Delete Product' style='background-color:#ef476f;' disabled />
            </form>
        </div>
        <div>
            <form action='stock_insert_form.html' method='post' style='display: inline-block; margin-right: 5px;'>
                <input type='submit' value='Add Product' style='background-color:#4b93ff;' />
            </form>
            <br>
        </div>
    </div>

    <?php
    $cx =  mysqli_connect("localhost", "root", "", "mydb");
    $cur = "SELECT * FROM stock";
    $msresults = mysqli_query($cx, $cur);

    echo "<center>";
    echo "<div>
        <table style='width: 100%; margin: auto; text-align: center; border-collapse: collapse; border: 1px solid #ccc;'>
            <tr style='background-color:#ef476f;'>  
                <th style='width: 60px;'><img src='http://localhost/phpmyadmin/themes/pmahomme/img/arrow_ltr.png'></th>                   
                <th style='padding:10px;'>ID</th>
                <th>ProductName</th>
                <th>ProductPerUnit</th>
                <th>StockQty</th>
                <th>Action</th>
            </tr>";

    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr style='border-bottom: 1px solid #ccc;'>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['IDProduct']}'></td>
                    <td style='padding:5px;'>{$row['IDProduct']}</td>
                    <td>{$row['ProductName']}</td>
                    <td>{$row['ProductPerUnit']}</td>
                    <td>{$row['StockQty']}</td>
                    <td>
                        <form action='stock_update.php' method='get' style='display: inline-block;'>  
                            <input type='hidden' name='id_stock' value={$row['IDProduct']}>
                            <input type='image' alt='update' src='pen-solid.svg' style='width: 20px; height: 20px;'/>
                        </form>
                        <form action='stock_delete_confirm.php' method='get' style='display: inline-block;'>
                            <input type='hidden' name='id_stock' value={$row['IDProduct']}>
                            <input type='image' alt='delete' src='trash-solid.svg' style='width: 20px; height: 20px;'/>
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
