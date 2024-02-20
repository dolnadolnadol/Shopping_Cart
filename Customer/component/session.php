<?php
// Start session
session_start();

if (isset($_SESSION['id_username']) && isset($_SESSION['status'])) {
    if($_SESSION['status'] == true){
        $uid = $_SESSION['id_username'];
        $status = $_SESSION['status'];
        unset($_SESSION['cart']);
    }
    else {
        $_SESSION['status'] = false;
        $_SESSION['cart'];
    } 
} 
var_dump($_SESSION);
?>