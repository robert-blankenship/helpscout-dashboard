<?php
session_start();

include 'helpscout.php';

define('CLEF_ID', '');
define('CLEF_SECRET', '');

$postdata = http_build_query([
	'code' => (string) $_GET['code'],
	'app_id' => CLEF_ID,
	'app_secret' => CLEF_SECRET
]);

$opts = [
	'http' => [
		'method'  => 'POST',
		'header'  => 'Content-type: application/x-www-form-urlencoded',
		'content' => $postdata
	]
];

$context  = stream_context_create($opts);

$response = file_get_contents('https://clef.io/api/v1/authorize', false, $context);

$url = 'https://clef.io/api/v1/info?access_token=' . json_decode($response)->access_token;

$context  = stream_context_create( [
	'http' => [
		'method'  => 'GET'
	]
]);

$response = file_get_contents($url, false, $context);

if ( json_decode($response)->info == $clef_user_id ) {
	$_SESSION['clef_id'] = $clef_user_id;
	header("Location: .");
	die();
}

?>
