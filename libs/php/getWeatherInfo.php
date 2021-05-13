<?php
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$data = file_get_contents("https://api.openweathermap.org/data/2.5/onecall?units=metric&lat=$lat&lon=$lng&exclude=current,minutely,hourly,alerts&APPID=8711e064ad102236f3619e48e109b109");
print_r($data);