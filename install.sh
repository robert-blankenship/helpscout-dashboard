#!/bin/bash
echo 'Please enter your helpscout api key.
 For Windows/Ubuntu, use Shift+Ctrl+V to paste text into the terminal. 
 For Mac, use CMD+V.'
read helpscout_key

echo 'Please enter your clef app key.'
read clef_app_key

echo 'Please enter your clef app secret.'
read clef_app_secret

echo 'Please enter the clef user id.'
read clef_user_id

touch helpscout_creds.php
echo '$helpscout_key' = $helpscout_key > helpscout_creds.php
echo '$clef_app_key' = $clef_app_key >> helpscout_creds.php
echo '$clef_app_secret' = $clef_app_secret >> helpscout_creds.php
echo '$clef_user_id' = $clef_user_id >> helpscout_creds.php