<?php
class CallLog
{
    public static function callLog($cx, $uid, $productId, $calledFile, $action)
    {
        // ACCESS LOG

        $getCName = getCustomerName($cx, $uid);


        $getPName = getProductName($cx, $productId);

        echo "<script>console.log('CHECK LOG');</script>";

        if (isset($_SESSION['id_username'])) { // Make  a copy of checking GUEST!
            // Registered user
            error_log('REG USer');
            AccessLog::log($uid, $getCName, $action, $getPName, $calledFile);
        } else {
            // Guest user
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            error_log('GUEST USer');
            AccessLog::logGuest($ipAddress, $action, $getPName, $calledFile);
        }
        //END LOG
    }
}
