<?php
 
// Remote image URL

$url = $_REQUEST['url'];

// Image path
for($i=0;$i<sizeof($url);$i++) {

$url_image = $url[$i]['thumbnailImg'];

$img = 'imageSrc'.$i.'.jpg';

// Save image 

file_put_contents($img, file_get_contents($url_image));

}

?>