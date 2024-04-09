<?php

$url = 'https://kvstore.p.rapidapi.com/collections';
$collection_name = 'RapidAPI';
$request_url = $url . '/' . $collection_name;

$curl = curl_init($request_url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
  'X-RapidAPI-Host: kvstore.p.rapidapi.com',
  'X-RapidAPI-Key: 47967a7651msh5679d4ecab858b3p146c59jsn6340c95e31b5',
  'Content-Type: application/json'
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response . PHP_EOL;
?>