<?php
$lat = $_GET['lat'];
$lng = $_GET['lng'];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.openweathermap.org/data/2.5/onecall?units=metric&lat=".$lat."&lon=".$lng."&exclude=current%2Cminutely%2Chourly%2Calerts&APPID=8711e064ad102236f3619e48e109b109",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: a33d00ed-faa6-7c87-4773-097eee9b1f8b"
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
/* $data = file_get_contents("https://api.openweathermap.org/data/2.5/onecall?units=metric&lat=$lat&lon=$lng&exclude=current,minutely,hourly,alerts&APPID=8711e064ad102236f3619e48e109b109");
print_r($data); */ 