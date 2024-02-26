<?php
class Accesslog
{
    public static function log($uid, $whoDid, $action)
    {
        $logFilePath = 'logFolder/Accesslog.txt';
        $logMessage = "[" . date('Y-m-d H:i:s') . "] ";
        $logMessage .= "Uid: $uid, Who did: $whoDid, Action: $action" . PHP_EOL;

        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    }
}
