<?php
 
	$executionStartTime = microtime(true) / 1000;

    // build the individual requests, but do not execute them
    $ch_1 = curl_init('http://api.geonames.org/countryInfoJSON?formatted=true&lang=en&country='. $_REQUEST['iso'].'&username=jsingh318&style=full');
    //$ch_1 = curl_init('http://api.geonames.org/countryInfoJSON?formatted=true&lang=en&country='. $_REQUEST['iso'].'&username=loradennys01&style=full');
    $ch_2 = curl_init('https://restcountries.eu/rest/v2/alpha/'.$_REQUEST['iso']);
    $ch_3 = curl_init('https://openexchangerates.org/api/latest.json?app_id=c9c775b779134d43a9c6bae1d080c4ee');
    //$ch_3 = curl_init('https://openexchangerates.org/api/latest.json?app_id=f0ddae51e6fa49379e6e0c16c576eb86');
    $ch_4 = curl_init('https://api.opencagedata.com/geocode/v1/json?q='.urlencode($_REQUEST['country']).'&key=bf4c62e6d078444ca0e0324114a08b16');
    $ch_5 = curl_init('http://api.geonames.org/wikipediaSearchJSON?q='.urlencode($_REQUEST['country']).'&maxRows=30&username=jsingh318');
    //$ch_5 = curl_init('http://api.geonames.org/wikipediaSearchJSON?q='.urlencode($_REQUEST['country']).'&maxRows=30&username=loradennys01');
    $ch_6 = curl_init('https://corona.lmao.ninja/v2/countries/'.$_REQUEST['iso'].'?yesterday&strict&query');
    $ch_7 = curl_init('http://newsapi.org/v2/top-headlines?country='.$_REQUEST['iso'].'&apiKey=04174686125f4b648b2673010bae7e18');
    //$ch_7 = curl_init('http://newsapi.org/v2/top-headlines?country='.$_REQUEST['iso'].'&apiKey=ef179c07070844c3988bf9f317d446d1');

    curl_setopt($ch_1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_3, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_3, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_4, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_4, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_5, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_5, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_6, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_6, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_7, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_7, CURLOPT_RETURNTRANSFER, true);

    // build the multi-curl handle, adding both $ch
    $mh = curl_multi_init();
    curl_multi_add_handle($mh, $ch_1);
    curl_multi_add_handle($mh, $ch_2);
    curl_multi_add_handle($mh, $ch_3);
    curl_multi_add_handle($mh, $ch_4);
    curl_multi_add_handle($mh, $ch_5);
    curl_multi_add_handle($mh, $ch_6);
    curl_multi_add_handle($mh, $ch_7);
 
    // execute all queries simultaneously, and continue when all are complete
    $running = null;
    do {
        curl_multi_exec($mh, $running);
     } while ($running);

    //close the handles
    curl_multi_remove_handle($mh, $ch_1);
    curl_multi_remove_handle($mh, $ch_2);
    curl_multi_remove_handle($mh, $ch_3);
    curl_multi_remove_handle($mh, $ch_4);
    curl_multi_remove_handle($mh, $ch_5);
    curl_multi_remove_handle($mh, $ch_6);
    curl_multi_remove_handle($mh, $ch_7);
    curl_multi_close($mh);
 
    // all of our requests are done, we can now access the results

    $geoNames = curl_multi_getcontent($ch_1);
    $restCountries = curl_multi_getcontent($ch_2);
    $openExchange = curl_multi_getcontent($ch_3);
    $openCage = curl_multi_getcontent($ch_4);
    $geoNamesWiki = curl_multi_getcontent($ch_5);
    $covid = curl_multi_getcontent($ch_6);
    $news = curl_multi_getcontent($ch_7);

    $openCage_1 = json_decode($openCage, true);

    $north = $openCage_1['results'][0]['bounds']['northeast']['lat'];
    $south = $openCage_1['results'][0]['bounds']['southwest']['lat'];
    $east = $openCage_1['results'][0]['bounds']['northeast']['lng'];
    $west = $openCage_1['results'][0]['bounds']['southwest']['lng'];
    $lat = $openCage_1['results'][0]['geometry']['lat'];
    $lng = $openCage_1['results'][0]['geometry']['lng'];

    $ch_9 = curl_init('http://api.geonames.org/citiesJSON?north='.$north.'&south='.$south.'&east='.$east.'&west='.$west.'&username=jsingh318');
    //$ch_9 = curl_init('http://api.geonames.org/citiesJSON?north='.$north.'&south='.$south.'&east='.$east.'&west='.$west.'&username=loradennys01');
    $ch_10 = curl_init('http://api.geonames.org/wikipediaBoundingBoxJSON?north='.$north.'&south='.$south.'&east='.$east.'&west='.$west.'&username=jsingh318');
    //$ch_10 = curl_init('http://api.geonames.org/wikipediaBoundingBoxJSON?north='.$north.'&south='.$south.'&east='.$east.'&west='.$west.'&username=loradennys01');

    curl_setopt($ch_9, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_9, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch_10, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch_10, CURLOPT_RETURNTRANSFER, true);

     $mh = curl_multi_init();
    curl_multi_add_handle($mh, $ch_9);
    curl_multi_add_handle($mh, $ch_10);

     $running = null;
    do {
        curl_multi_exec($mh, $running);
     } while ($running);

    //close the handles
    curl_multi_remove_handle($mh, $ch_9);
    curl_multi_remove_handle($mh, $ch_10);

    $cities = curl_multi_getcontent($ch_9);
    $tourists = curl_multi_getcontent($ch_10);

    // Final Output
	$output['status']['code'] = "200";
    $output['status']['name'] = "OK";
    $output['geoNames'] = json_decode($geoNames, true);
    $output['restCountries'] = json_decode($restCountries, true);
    $output['openExchange'] = json_decode($openExchange, true);
    $output['openCage'] = json_decode($openCage, true);
    $output['geoNamesWiki'] = json_decode($geoNamesWiki, true);
    $output['covid'] = json_decode($covid, true);
    $output['news'] = json_decode($news, true);
    $output['tourists'] = json_decode($tourists, true);
    $output['cities'] = json_decode($cities, true);
   
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000000 . " ms";

	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output);

?>





