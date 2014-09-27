<?php
session_start();

include 'helpscout_creds.php'

$app_id 	= $clef_app_key;
$app_secret = $clef_app_secret;

$code 		= $_GET['code'];

$postdata = http_build_query(
	array(
		'code' => $code,
		'app_id' => $app_id,
		'app_secret' => $app_secret
		)
	);

$opts = array('http' =>
	array(
		'method'  => 'POST',
		'header'  => 'Content-type: application/x-www-form-urlencoded',
		'content' => $postdata
		)
	);

$url = 'https://clef.io/api/v1/authorize';

$context  = stream_context_create($opts);

$response = file_get_contents($url, false, $context);

$response = json_decode($response);

$access_token = $response->{'access_token'};

$opts = array('http' =>
	array(
		'method'  => 'GET'
		)
	);

$url = 'https://clef.io/api/v1/info?access_token='.$access_token;

$context  = stream_context_create($opts);
$response = file_get_contents($url, false, $context);

$response = json_decode($response, true);
$user_info = $response['info'];

$clef_id = $user_info['id'];

if ($clef_id == $clef_user_id) {
	$_SESSION['clef_id'] = $clef_user_id;
	header("Location: .");
	die();
}?>
