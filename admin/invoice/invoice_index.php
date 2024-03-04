<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>invoice List</title>
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

        .add-invoice-form {
            display: inline-block;
            margin-right: 5px;
        }

        #addInvoiceButton {
            background-color: #4b93ff;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #addInvoiceButton:hover {
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

        .navbar {
            margin-top: 100px;
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
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>
    <h1>Invoice List</h1>
    <div class="container">
        <div>
            <input type="checkbox" id="checkAll" onchange="checkAll()">
            <label class="check-all-label">Check All</label>
            <form  id='deleteForm' class="delete-form" action="invoice_delete_confirm.php" method="post">
                <input type="hidden" name="list_id_invoice" id="selectedValues" value="">
                <input type="hidden" name="total_id_invoice" id="selectedTotal" value="">
                <input type="submit" id="deleteButton" value="Delete Invoice" disabled>
            </form>
        </div>
        <div>
            <!------------- Fillter ------------------->
            <label for="filter">Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <form class="add-invoice-form" action="invoice_insert.php" method="post">
                <input type="submit" id="addInvoiceButton" value="Add Invoice">
            </form>
            <br>
        </div>
    </div>
    
    <?php
    include_once '../../dbConfig.php'; 
    $cur = "SELECT invoice.* , customer.CusFName , customer.CusLName
    FROM 
        invoice
    JOIN
        customer ON invoice.CusID = customer.CusID
    JOIN 
        invoice_detail ON invoice.InvID = invoice_detail.InvID
    JOIN
        product ON invoice_detail.ProID = product.proID";

    $msresults = mysqli_query($conn, $cur);
    echo "<center>";
    echo "<div>
        <table>
            <tr>
                <th><img src='http://localhost/phpmyadmin/themes/pmahomme/img/arrow_ltr.png'></th>
                <th>ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>";

    $index = 1;
    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr class='user-row'>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['InvID']}'></td>
                    <td>{$row['InvID']}</td>
                    <td>{$row['CusFName']} {$row['CusLName']}</td>
                    <td>{$row['Period']}</td>
                    <td>{$row['TotalPrice']}</td>";

                    echo "<td>";
                    echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:90px; margin: 0 auto; background-color:";

                    // เงื่อนไขตรวจสอบค่า Status และกำหนดสีให้กับ background-color
                    if ($row['Status'] == 'Unpaid') {
                        echo '#FFA500;';
                    } elseif ($row['Status'] == 'Paid') {
                        echo '#06D6B1;';
                    } else if ($row['Status'] == 'Canceled'){
                        echo '#FF0000;';
                    } else {
                        echo '#06D6B1;'; 
                    }       

                    echo "'>";
                    echo "<select id='select_$index' data-recid='{$row['InvID']}' style='background-color: inherit; border:0; width:100%; cursor: pointer;
                    user-select: none; color: #ffff;' required>";

                    $statusCompare = ['Unpaid', 'Paid', 'Canceled'];

                    foreach ($statusCompare as $value) {
                        $selected = ($value == $row['Status']) ? 'selected' : '';
 

                        echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                    }

                    echo "</select>";
                    echo "</div></td>";

                    echo "<td class='action-buttons'>
                        <form class='action-button' action='invoice_update.php' method='post'>  
                            <input type='hidden' name='id_invoice' value={$row['InvID']}>
                            <input type='image' alt='update' src='../img/pencil.png'>
                        </form>
                        <form class='action-button' action='invoice_delete_confirm.php' method='post'>
                            <input type='hidden' name='total_id_invoice' value={$row['InvID']}>
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
        /* Fillter */
    function updateTable(filterKeyword) {
            var tableRows = document.querySelectorAll('.user-row');

            tableRows.forEach(function (row) {
                var containsKeyword = false;

                // Loop through all columns (td elements) in the current row
                row.querySelectorAll('td').forEach(function (cell, index) {
                    var cellText = cell.innerText.toLowerCase();

                    // Check if the cell contains the filter keyword (string comparison)
                    if (cellText.includes(filterKeyword.toLowerCase())) {
                        containsKeyword = true;
                        return; // Break out of the loop if the keyword is found in any cell
                    }

                    // Check if the cell contains the filter keyword as a number
                    var cellNumber = parseFloat(cellText);
                    var filterNumber = parseFloat(filterKeyword);

                    if (!isNaN(cellNumber) && !isNaN(filterNumber) && cellNumber === filterNumber) {
                        containsKeyword = true;
                        return; // Break out of the loop if the numeric values match
                    }
                });

                // Display or hide the row based on the keyword presence
                if (containsKeyword) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Listen for input event on the filter input
        $('#filter').on('input', function() {
            // Get the value of the filter input
            var filterKeyword = $(this).val();

            // Update the table based on the filter keyword
            updateTable(filterKeyword);
        });
    </script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    // Loop through all select elements and attach event listeners
    for (var i = 1; i <= <?php echo $index; ?>; i++) {
        var selectElement = document.getElementById('select_' + i);

        if (selectElement) {
            selectElement.addEventListener('change', function () {
                var selectedValue = this.value;
                var selectDiv = this.parentElement;
                var recID = this.getAttribute('data-recid');

                switch (selectedValue) {
                    case 'Unpaid':
                        selectDiv.style.backgroundColor = '#FFA500';
                        break;
                    case 'Paid':
                        selectDiv.style.backgroundColor = '#06D6B1';
                        break;
                    case 'Canceled':
                        selectDiv.style.backgroundColor = '#FF0000';
                        break;
                    default:
                        selectDiv.style.backgroundColor = '#06D6B1';
                }

                console.log(recID , selectedValue );
                // Update the status using AJAX
                updateStatus(recID, selectedValue);
            });
        }
    }

    function updateStatus(invID, newStatus) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'invoice_update_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        console.log(invID , newStatus )

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Handle successful response
                    console.log('Status updated successfully');
                } else {
                    // Handle error
                    console.error('Error updating status');
                }
            }
        };
        xhr.send('InvID=' + encodeURIComponent(invID) + '&newStatus=' + encodeURIComponent(newStatus));

    }
    });
</script>
</body>
</html>
