<?php

require __DIR__.'var/helpscout-credentials.php';

session_start();

header("Content-Type:text/plain");

echo $_SESSION['clef_id'] === $clef_user_id ? HELPSCOUT_KEY : false;

?>
