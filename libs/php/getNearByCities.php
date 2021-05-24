<?php

$east = $_GET['east'];
$west = $_GET['west'];
$north = $_GET['north'];
$south = $_GET['south'];
$username = "jsingh318";
//$username = $_GET['username'];
// The file_get_contents() reads a file into a string.
/* $data = file_get_contents("http://api.geonames.org/citiesJSON?north=$north&south=$south&east=$east&west=$west&username=$username");
print_r($data); */

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://api.geonames.org/citiesJSON?north=".$north."&south=".$south."&east=".$east."&west=".$west."&username=".$username,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 66a5fe35-7e50-7529-5285-0991e7f8dde2"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}