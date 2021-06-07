 <?php
 
	$executionStartTime = microtime(true) / 1000;      

        $url = 'https://api.opencagedata.com/geocode/v1/json?q='.$_REQUEST['lat'].'+'.$_REQUEST['lng'].'&key=bf4c62e6d078444ca0e0324114a08b16';

        $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    curl_setopt($ch, CURLOPT_URL, $url);
        $openCage = curl_exec($ch);

        curl_close($ch);

        $openCage = json_decode($openCage, true);

            // Final Output
	$output['status']['code'] = "200";
    $output['status']['name'] = "OK";

    $output['openCage'] = $openCage;

    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000000 . " ms";

	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output);

?>
