<?php
$country_name = urlencode($_GET['country_name']);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://newsapi.org/v2/everything?q=".$country_name."&sortBy=relevancy&apiKey=04174686125f4b648b2673010bae7e18",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 9c8f0d10-73d0-30cd-3dd0-1c5fe6159999"
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
/* $country_name = urlencode($_GET['country_name']);
$data = file_get_contents('http://newsapi.org/v2/everything?q='.$country_name.'&sortBy=relevancy&apiKey=04174686125f4b648b2673010bae7e18');
print_r($data); */ 