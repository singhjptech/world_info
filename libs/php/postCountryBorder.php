<?php
 
	$executionStartTime = microtime(true) / 1000;    

    $iso = $_REQUEST['iso'];

    $string = file_get_contents("countryBorders.geo.json");
    $json = json_decode($string);
    $features = $json->features;

    $output_geom = "";
    for($i=0;$i<sizeof($features);$i++){
        $feature = $features[$i];
        if($feature->properties->iso_a2 == $iso){
            $output_geom = $feature->geometry;
        }
    }

    $output['status']['code'] = "200";
    $output['status']['name'] = "OK";
    $output['geom'] = $output_geom;
   
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000000 . " ms";

	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output);

?>