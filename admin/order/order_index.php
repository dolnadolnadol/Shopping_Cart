<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
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

        .add-order-form {
            display: inline-block;
            margin-right: 5px;
        }

        #addOrderButton {
            background-color: #4b93ff;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #addOrderButton:hover {
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

        .action-button input[type='image'] {
            width: 30px;
            height: 30px; 
        }

    </style>
</head>

<body>
    <h1>Order List</h1>
    <div class="container">
        <div>
            <input type='checkbox' id='checkAll' onchange='checkAll()'>
            <label class="check-all-label">Check All</label>
            <form id='deleteForm' class="delete-form" action='order_delete_confirm.php' method='post'>
                <input type='hidden' name='list_id_order' id='selectedValues' value=''>
                <input type='hidden' name='total_id_order' id='selectedTotal' value=''>
                <input type='submit' id='deleteButton' value='Delete Order' disabled />
            </form>
        </div>
        <div>
            <form class="add-order-form" action='order_insert.php' method='post'>
                <input type='submit' id="addOrderButton" value='Add Order'/>
            </form>
            <br>
        </div>
    </div>

    <?php
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $cur = "SELECT * FROM receive 
    INNER JOIN customer ON customer.CusID = receive.CusID
    INNER JOIN receive_detail ON receive_detail.RecID = receive.RecID";
    $msresults = mysqli_query($cx, $cur);

    echo "<center>";
    echo "<div>
        <table>
            <tr>  
                <th></th>                   
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Order Date</th>
                <th>Delivery Date</th>
                <th>Delivery Status</th>
                <th>Action</th>
            </tr>";

    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['RecID']}'></td>
                    <td>{$row['RecID']}</td>
                    <td>{$row['CusName']}</td>
                    <td>{$row['TotalPrice']}</td>
                    <td>{$row['OrderDate']}</td>
                    <td>{$row['DeliveryDate']}</td>";

                    echo "<td><div style='border-radius:10px; padding: 3.920px 7.280px; width:90px; margin: 0 auto; background-color:";

                    // เงื่อนไขตรวจสอบค่า Status และกำหนดสีให้กับ background-color
                    if ($row['Status'] == 'Pickups') {
                        echo '#0176FF;';
                    } elseif ($row['Status'] == 'Pending' || $row['Status'] == 'pending') {
                        echo '#FFA500;';
                    } elseif ($row['Status'] == 'Inprogress') {
                        echo '#7C6BFF;'; 
                    } else if ($row['Status'] == 'Canceled'){
                        echo '#FF0000;';
                    } else {
                        echo '#06D6B1;'; 
                    }
                    
                    echo "'><span style='color: #ffff;'>{$row['Status']}</span></div></td>";
                  
            echo    "<td>
                        <form class='action-button' action='order_update.php' method='post' style='display: inline-block;'>  
                            <input type='hidden' name='id_order' value={$row['RecID']}>
                            <input type='image' alt='update' src='../img/pencil.png'/>
                        </form>
                        <form class='action-button' action='order_delete_confirm.php' method='post' style='display: inline-block;'>
                            <input type='hidden' name='total_id_order' value={$row['RecID']}>
                            <input type='image' alt='delete' src='../img/trash.png'/>
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
