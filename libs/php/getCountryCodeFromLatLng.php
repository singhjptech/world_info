<?php

$lat = $_GET['lat'];
$lng = $_GET['lng'];
$username = $_GET['username'];
// The file_get_contents() reads a file into a string.
/* $data = file_get_contents("http://api.geonames.org/countryCodeJSON?lat=$lat&lng=$lng&username=$username");
print_r($data); */ 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://api.geonames.org/countryCodeJSON?lat=".$lat."&lng=".$lng."&username=".$username,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: c3ad4d2b-0faf-7ff7-b908-2350a7c7bb1a"
  ),
));

$data = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $data;
}