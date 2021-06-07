 <?php
 
	$executionStartTime = microtime(true) / 1000;      

        $url = 'https://api.opencagedata.com/geocode/v1/json?q='.urlencode($_REQUEST['capital']).'&key=bf4c62e6d078444ca0e0324114a08b16';

        $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    curl_setopt($ch, CURLOPT_URL, $url);
        $openCage = curl_exec($ch);

        curl_close($ch);

        $openCage = json_decode($openCage, true);

        $lat = $openCage['results'][0]['geometry']['lat'];
        $lng = $openCage['results'][0]['geometry']['lng'];

        $url_2 = 'http://api.openweathermap.org/data/2.5/onecall?lat='.$lat.'&lon='.$lng.'&exclude=hourly&units=metric&appid=9d21adbaf29b1b1269b5d0eb5af76f9e';

        $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    curl_setopt($ch, CURLOPT_URL, $url_2);
        $openWeather = curl_exec($ch);

        curl_close($ch);

        $openWeather = json_decode($openWeather, true);

            // Final Output
	$output['status']['code'] = "200";
    $output['status']['name'] = "OK";
    $output['openWeather'] = $openWeather;
    $output['openCage'] = $openCage;

    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000000 . " ms";

	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output);

?>