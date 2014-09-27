<?php

include 'helpscout_creds.php';

session_start();

header("Content-Type:text/plain");

if ($_SESSION['clef_id'] == $clef_user_id) { //Replace this with your Clef ID.
	echo $helpscout_key; //Replace this with your Helpscout appkey.
} else {
	echo false;
}

?>
