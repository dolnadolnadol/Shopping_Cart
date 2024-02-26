<?php
// AccessLogger.php
class AccessLog
{
    public static function log($uid, $cusName, $action, $productName, $callingFile)
    {
        $logFilePath = __DIR__ . '/Accesslog.json';

        // Read existing JSON data from the file
        $existingData = [];
        if (file_exists($logFilePath)) {
            $existingData = json_decode(file_get_contents($logFilePath), true);
        }
        // Extract only the file name from the file path
        $callingFileName = basename($callingFile);

        // Add the new log entry
        $logData = [
            'TimeStamp' => date('Y-m-d H:i:s'),
            'UserID' => $uid,
            'CustomerName' => $cusName,
            'Action' => $action,
            'ProductName' => $productName,
            'CallingFile' => $callingFileName
        ];

        $existingData[] = $logData;

        // Write the updated JSON data back to the file
        file_put_contents($logFilePath, json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    public static function logGuest($ipAddress, $action, $productName, $callingFile)
    {
        $logFilePath = __DIR__ . '/Accesslog.json';

        // Read existing JSON data from the file
        $existingData = [];
        if (file_exists($logFilePath)) {
            $existingData = json_decode(file_get_contents($logFilePath), true);
        }

        // Add the new log entry for guest
        $logData = [
            'TimeStamp' => date('Y-m-d H:i:s'),
            'IP Address' => $ipAddress,
            'CustomerName' => 'GUEST',
            'Action' => $action,
            'ProductName' => $productName,
            'CallingFile' => basename($callingFile)
        ];

        $existingData[] = $logData;

        // Write the updated JSON data back to the file
        file_put_contents($logFilePath, json_encode($existingData, JSON_PRETTY_PRINT));
    }
}
