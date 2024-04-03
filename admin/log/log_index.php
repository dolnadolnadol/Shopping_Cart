<?php
    include_once '../../dbConfig.php'; 

    $recordsPerPage = 10;

    $totalRecords = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM log"))[0];

    $recordsPerPage = isset($_GET['recordsPerPage']) ? (int)$_GET['recordsPerPage'] : 10;
  
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    $offset = ($currentPage - 1) * $recordsPerPage;

    $cur = "SELECT * FROM log LIMIT $recordsPerPage OFFSET $offset";
    $msresults = mysqli_query($conn, $cur);
    
    $totalPages = ceil($totalRecords / $recordsPerPage);
?>

<?php
    session_start();
    if($_SESSION['auth'] !== 'super-admin') {
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
    <title>Stock List</title>
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
        }

        th {
            background-color: #666666;
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
        .navbar {
            margin-top: 100px;
        }

        #blue-fonts-table {
            color: #3a75c4;
        }

        #img-setting {
            width: 14px;
            height: 14px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #3a75c4;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .active {
            background-color: #3a75c4;
            color: white;
            border: 1px solid #3a75c4;
        }
    </style>
</head>

<body>
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>
    <h1>Access Log</h1>
    <div class="container">
        <div>
            <!------------- Fillter ------------------->
            <label for="filter"><img src="../img/settings.png" id='img-setting'>Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <br>
        </div>
    </div>

    <?php
        $cur = "SELECT * FROM log LIMIT $recordsPerPage OFFSET $offset";
        $msresults = mysqli_query($conn, $cur);
        
        echo "<center>";
        echo "<div>
            <table id='logTable'>
                <tr>           
                    <th>Time</th>
                    <th>User</th>
                    <th>IP Address</th>
                    <th>Action</th>
                    <th>CallFile</th>
                </tr>";
        
        if (mysqli_num_rows($msresults) > 0) {
            while ($row = mysqli_fetch_array($msresults)) {
                echo "<tr class='user-row'>
                        <td>{$row['TimeStamp']}</td>
                        <td id='blue-fonts-table'>{$row['CustomerName']}</td>
                        <td>{$row['IPAddress']}</td>               
                        <td>{$row['Action']}</td>
                        <td id='blue-fonts-table'>{$row['CallingFile']}</td>             
                    </tr>";
            }
        }
        
        echo "</table></div>";
        echo "</center>";
        mysqli_close($conn);
    ?>
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a class='" . ($currentPage == $i ? "active" : "") . "' href='log_index.php?page=$i&recordsPerPage=$recordsPerPage'>$i</a> ";
        }
        ?>
    </div>

<script>
   

    /* Fillter */
    function updateTable(filterKeyword) {
    // AJAX request to fetch filtered data
        $.ajax({
            type: 'GET',
            url: 'filter_data.php',
            data: { filterKeyword: filterKeyword },
            success: function(data) {
                // Replace the table content with the filtered data
                $('#logTable').html(data); // Ensure that the id matches your table id
            },
            error: function(error) {
                console.error('Error fetching filtered data:', error);
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
</body>
</html>
