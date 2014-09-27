<?php

include 'helpscout_creds.php';

session_start();

header("Content-Type:text/plain");

if ($_SESSION['clef_id'] == $clef_user_id) {
	echo $helpscout_key;
} else {
	echo false;
}

?>
