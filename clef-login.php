<?php
session_start();

require __DIR__.'var/clef-credentials.php';

define('CLEF_API', 'https://clef.io/api/v1');

function getAccessToken ($code) {
	$context  = stream_context_create([
		'http' => [
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',

			'content' => http_build_query([

				'code' => (string) $_GET['code'],
				'app_id' => CLEF_ID,
				'app_secret' => CLEF_SECRET
			]);
		]
	]);

	$response = file_get_contents(CLEF_API.'/authorize', false, $context);

	return json_decode($response)->access_token;
}

function getClefId ($access_token) {
	$context  = stream_context_create([
		'http' => [
			'method'  => 'GET',
			'content' => 'access_token='.$access_token
		]
	]);

	$response = file_get_contents(CLEF_API.'/info', false, $context);

	return json_decode($response)->info
}

$access_token = getAccessToken((string)$_GET['code']);

if ( getClefId($access_token) == CLEF_USER_ID ) {

	$_SESSION['clef_id'] = CLEF_USER_ID;
	header("Location: .");
	die();
}

?>