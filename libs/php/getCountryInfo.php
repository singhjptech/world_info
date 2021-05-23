<?php
$country_code = $_GET['country_code'];
/* $data = file_get_contents("https://restcountries.eu/rest/v2/alpha/$country_code");
print_r($data); */

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://restcountries.eu/rest/v2/alpha/".$country_code,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 24429985-f302-ad01-7a39-7da077292a91"
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