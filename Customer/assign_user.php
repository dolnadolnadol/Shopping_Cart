<?php
include ('./component/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'];
    $auth = $_POST['auth'];
    echo $auth;
    $user = $uid; // Assign the value to $user variable

    $_SESSION['status'] = true;
    $_SESSION['id_username'] = $user;
    $_SESSION['uid'] = $user;
    unset($_SESSION['cart']);
    if ($auth == 'product-admin') {
        $_SESSION['auth'] = 'product-admin';
    }
    if ($auth == 'permissions-admin') {
        $_SESSION['auth'] = 'permissions-admin';
    }
    if ($auth == 'super-admin') {
        $_SESSION['auth'] = 'super-admin';
    }
    if ($auth == 'users'){
        $_SESSION['auth'] = 'users';
    }

    // header('Location: ./');
    // exit();
} else {
    echo 'Invalid request method!';
}
