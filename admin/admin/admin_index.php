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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Admin List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color:white;
        }

        h1 {
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: space-between;
            background-color: #4c4c4c;
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
        }

        th {
            background-color: #666666;
            padding: 10px;
        }

        td {
            padding: 5px;
            border-bottom: 1px solid #ccc;
        }
        tr:hover{
            background-color: rgba(255,0,0,0.2);
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
        .navbar {
            margin-top: 100px;
        }

    </style>
</head>

<body>
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>
    <h1>Admin List</h1>
    <div class="container">
        <div>
            <input type='checkbox' id='checkAll' onchange='checkAll()'>
            <label class="check-all-label">Check All</label>
            <form id='deleteForm' class="delete-form" action='admin_delete_confirm.php' method='post'>
                <input type='hidden' name='list_id_customer' id='selectedValues' value=''>
                <input type='hidden' name='total_id_customer' id='selectedTotal' value=''>
                <input type='submit' id='deleteButton' value='Delete Order' disabled />
            </form>

        </div>
        <div>
            <!------------- Fillter ------------------->
            <label for="filter">Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <!-- <form class="add-order-form" action='order_insert.php' method='post'>
                <input type='submit' id="addOrderButton" value='Add Order'/>
            </form> -->
            <br>
        </div>
    </div>

    <?php
    include_once '../../dbConfig.php'; 
    $cur = "SELECT * FROM customer WHERE authority != 'users' AND deleteStatus != '0'";
    $msresults = mysqli_query($conn, $cur);

    echo "<center>";
    echo "<div>
        <table>
            <tr>  
                <th></th>     
                <th>Admin ID</th>
                <th>Name</th> 
                <th>Email</th> 
                <th>Username</th>        
                <th>Permission Status</th>
                <th>Action</th>
            </tr>";

    $index = 1;
    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr class='user-row'>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['CusID']}'></td>
                    <td>{$row['CusID']}</td>
                    <td>{$row['fname']} {$row['lname']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['Username']}</td>";

                    echo "<td>";
                    echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:150px; margin: 0 auto; background-color:";
                    if ($row['authority'] == 'super-admin') {
                        echo '#ffffff                        ;';
                    } elseif ($row['authority'] == 'product-admin') {
                        echo '#ffff00;';
                    } elseif ($row['authority'] == 'permissions-admin') {
                        echo '#008000;';
                    }
                    echo "'>";
                    echo "<select id='select_$index' data-cusid='{$row['CusID']}' style='background-color: inherit; border:0; width:100%; cursor: pointer;
                    user-select: none; color: #black;' required>";
                    $statusCompare = ['super-admin', 'product-admin', 'permissions-admin'];
                    foreach ($statusCompare as $value) {
                        $selected = ($value == $row['authority']) ? 'selected' : '';
                        echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                    }
                    echo "</select>";
                    echo "</div></td>";

            echo    "<td>
                        <form class='action-button' action='admin_delete_confirm.php' method='post'>
                            <input type='hidden' name='total_id_customer' value={$row['CusID']}>
                            <input type='image' alt='delete' src='../img/trash.png'>
                        </form>
                    </td>
                </tr>";
            $index++;
        }
    }

    echo "</table></div>";
    echo "</center>";
    mysqli_close($conn);
    ?>
<script>
    function updateDeleteButtonStatus() {
        var checkboxes = document.getElementsByName('checkbox[]');
        var deleteButton = document.getElementById('deleteButton');
        var selectedValuesInput = document.getElementById('selectedValues');

        var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
        var enableDeleteButton = checkedCheckboxes.length > 0;

        deleteButton.disabled = !enableDeleteButton;

        var checkboxValues = checkedCheckboxes.map(checkbox => checkbox.value);
        selectedValuesInput.value = checkboxValues.join(',');
    }

    var individualCheckboxes = document.getElementsByName('checkbox[]');
    for (var i = 0; i < individualCheckboxes.length; i++) {
        individualCheckboxes[i].addEventListener('change', function () {
            updateDeleteButtonStatus();
        });
    }
</script>

<script>
    /*Approve*/
    document.addEventListener('DOMContentLoaded', function () {
    for (var i = 1; i <= <?php echo $index; ?>; i++) {
        var selectElement = document.getElementById('select_' + i);
        if (selectElement) {
            selectElement.addEventListener('change', function () {
                var selectedValue = this.value;
                var selectDiv = this.parentElement;
                var cusid = this.getAttribute('data-cusid');

                switch (selectedValue) {
                    case 'super-admin':
                        selectDiv.style.backgroundColor = '#ffffff';
                        break;
                    case 'product-admin':
                        selectDiv.style.backgroundColor = '#ffff00';
                        break;
                    case 'permissions-admin':
                        selectDiv.style.backgroundColor = '#008000';
                        break;
                }

                console.log(cusid, selectedValue);
                updateStatus(cusid, selectedValue);
            });
        }
    }
    
    function updateStatus(cusid, newStatus) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'admin_update_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Status updated successfully');
                } else {
                    console.error('Error updating status');
                }
            }
        };
        xhr.send('cusid=' + encodeURIComponent(cusid) + '&newStatus=' + encodeURIComponent(newStatus));
    }
});
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

        document.getElementById('deleteForm').elements['selectedValues'].value = checkboxValues.join(',');
        var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
        var enableDeleteButton = checkedCheckboxes.length > 0;

        deleteButton.disabled = !enableDeleteButton;
    }

    /* Fillter */
    function updateTable(filterKeyword) {
            var tableRows = document.querySelectorAll('.user-row');

            tableRows.forEach(function (row) {
                var containsKeyword = false;

                row.querySelectorAll('td').forEach(function (cell, index) {
                    var cellText = cell.innerText.toLowerCase();

                    if (cellText.includes(filterKeyword.toLowerCase())) {
                        containsKeyword = true;
                    }

                    var cellNumber = parseFloat(cellText);
                    var filterNumber = parseFloat(filterKeyword);

                    if (!isNaN(cellNumber) && !isNaN(filterNumber) && cellNumber === filterNumber) {
                        containsKeyword = true;
                        return;
                    }
                });

                if (containsKeyword) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        $('#filter').on('input', function() {
            var filterKeyword = $(this).val();
            updateTable(filterKeyword);
        });
</script>
</body>
</html>
