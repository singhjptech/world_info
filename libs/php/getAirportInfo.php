<?php
$country_code = $_GET['country_name'];
$year = date('Y');
// print_r($country_code); die;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://holidays.abstractapi.com/v1/?api_key=4eafde3ef9e9430cbf905d16ec0b7d07&country=".$country_code."&year=2020",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 9757ddc1-d2ed-90f6-6988-8c6a3704e19a"
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
/* $data = file_get_contents("https://corona.lmao.ninja/v2/countries/$country_code?yesterday&strict&query");
print_r($data); */ 