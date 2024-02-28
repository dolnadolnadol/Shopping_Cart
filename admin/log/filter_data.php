<?php
include('../callDatabase/sql_connection.php');
$sqlConnectionInstance = new Sql_connection();
$cx = $sqlConnectionInstance->sql_Connection();

$filterKeyword = isset($_GET['filterKeyword']) ? mysqli_real_escape_string($cx, $_GET['filterKeyword']) : '';

if (!empty($filterKeyword)) {
    $cur = "SELECT * FROM log 
            WHERE CustomerName LIKE '%$filterKeyword%'
               OR IPAddress LIKE '%$filterKeyword%'
               OR Action LIKE '%$filterKeyword%'
               OR CallingFile LIKE '%$filterKeyword%'";
} else {
    // If $filterKeyword is empty, select all records
    $cur = "SELECT * FROM log LIMIT 10 OFFSET 0";
}
$msresults = mysqli_query($cx, $cur);

if (mysqli_num_rows($msresults) > 0) {
    echo "<tr>           
        <th>Time</th>
        <th>User</th>
        <th>IP Address</th>
        <th>Action</th>
        <th>CallFile</th>
        </tr>";

    while ($row = mysqli_fetch_array($msresults)) {
        echo "<tr class='user-row'>
                <td>{$row['TimeStamp']}</td>
                <td id='blue-fonts-table'>{$row['CustomerName']}</td>
                <td>{$row['IPAddress']}</td>               
                <td>{$row['Action']}</td>
                <td id='blue-fonts-table'>{$row['CallingFile']}</td>             
            </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}

mysqli_close($cx);
?>
