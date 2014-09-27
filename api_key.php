<?php
session_start();

header("Content-Type:text/plain");

if ($_SESSION['clef_id'] == 0) { //Replace this with your Clef ID.
	echo 'YOUR_HELPSCOUT_APP_KEY'; //Replace this with your Helpscout appkey.
} else {
	echo false;
}
?>
