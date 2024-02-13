<?php
// Start session
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'true') {
    // Redirect to the login page
    header("Location: login.php");
    session_destroy();
    exit();
}
?>