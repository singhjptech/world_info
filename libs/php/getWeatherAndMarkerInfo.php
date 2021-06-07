<?php
 
	$executionStartTime = microtime(true) / 1000;

    $ch_1 = curl_init('http://api.openweathermap.org/data/2.5/onecall?lat='.$_REQUEST['lat'].'&lon='.$_REQUEST['lng'].'&exclude=hourly&units=metric&appid=9d21adbaf29b1b1269b5d0eb5af76f9e');
    $ch_2 = curl_init('http://api.geonames.org/citiesJSON?north='.$_REQUEST['north'].'&south='.$_REQUEST['south'].'&east='.$_REQUEST['east'].'&west='.$_REQUEST['west'].'&username=jsingh318');
    $ch_3 = curl_init('http://api.geonames.org/wikipediaBoundingBoxJSON?north='.$_REQUEST['north'].'&south='..$_REQUEST['south'].'&east='.$_REQUEST['east'].'&west='.$_REQUEST['west'].'&username=jsingh318');

    curl_setopt($ch_1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_3, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_3, CURLOPT_RETURNTRANSFER, true);

     $mh = curl_multi_init();
    curl_multi_add_handle($mh, $ch_1);
    curl_multi_add_handle($mh, $ch_2);
    curl_multi_add_handle($mh, $ch_3);

     $running = null;
    do {
        curl_multi_exec($mh, $running);
     } while ($running);

    //close the handles
    curl_multi_remove_handle($mh, $ch_1);
    curl_multi_remove_handle($mh, $ch_2);
    curl_multi_remove_handle($mh, $ch_3);

    $openWeather = curl_multi_getcontent($ch_1);
    $cities = curl_multi_getcontent($ch_2);
    $tourists = curl_multi_getcontent($ch_3);

    $output['status']['code'] = "200";
    $output['status']['name'] = "OK";
    $output['openWeather'] = json_decode($openWeather, true);
    $output['tourists'] = json_decode($tourists, true);
    $output['cities'] = json_decode($cities, true);

     $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000000 . " ms";

	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output);

?>