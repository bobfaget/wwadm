<?php 
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}


//$dlc = get_data("https://isyourlinksad.com/more/featured_user_auto_clicks.php");
$dlc = file_get_contents("https://isyourlinksad.com/more/featured_user_auto_clicks.php");
?>
