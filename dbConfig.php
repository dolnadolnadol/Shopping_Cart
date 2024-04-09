<?php
$dbHost     = "139.5.145.94";
$dbUsername = "shopping";
$dbPassword = "ShoppingPamulamo028060";
$dbName     = "shopping";

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>