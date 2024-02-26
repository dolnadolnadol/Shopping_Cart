<?php
function getCustomerName($cx, $uid)
{
    if ($uid) {
        $getCustomerNameQuery = mysqli_query($cx, "SELECT CusFName FROM customer WHERE CusID = '$uid'");

        if ($getCustomerNameQuery) {
            $customerData = mysqli_fetch_assoc($getCustomerNameQuery);
            return $customerData['CusFName'];
        }
    }

    // If $uid is not set or user is guest
    // Generate a unique guest name with an auto-incrementing number
    $guestNumber = generateGuestNumber();
    return "GUEST" . str_pad($guestNumber, 4, '0', STR_PAD_LEFT);
}

function getProductName($cx, $code)
{
    $cur = "SELECT * FROM product WHERE ProID = $code ";
    $result = mysqli_query($cx, $cur);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['ProName'];
    } else {
        // Handle the case where the product is not found or an error occurs
        return "Unknown Product";
    }
}
function generateGuestNumber()
{
    // Logic to generate a unique guest number locally
    // You can store it in a file, session, or use any other method to persist it between requests

    // For this example, let's use a session variable to store and increment the guest number
    session_start();

    if (!isset($_SESSION['guestNumber'])) {
        $_SESSION['guestNumber'] = 1;
    }

    $guestNumber = $_SESSION['guestNumber'];
    $_SESSION['guestNumber']++;

    return $guestNumber;
}

function logAction($uid, $customerName, $action, $productName, $callingFile)
{
    AccessLog::log($uid, $customerName, $action, $productName, $callingFile);
}
