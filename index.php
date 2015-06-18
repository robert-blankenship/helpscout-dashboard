<?php session_start(); ?>

<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="./style.css">
	
	<script type="text/javascript" src="include/base64.js"></script>
	<script type="text/javascript" src="include/angular.min.js"></script>
</head>

<body ng-controller='main'>
	<div class="clef-wrapper" ng-hide="hide_it" id="Clef">
		<script type="text/javascript" src="https://clef.io/v3/clef.js" class="clef-button" data-embed="true" data-app-id="f5141de0b960fbeba62a08aeeaed6b6e" data-color="blue" data-style="flat" data-redirect-url="http://test.robert.gg/helpscout-dashboard/login.php"></script>
	</div>

	<div class="mailboxes" id="key.name" ng-repeat="key in mailboxes" ng-if="api_ready" style="background-color : {{key.color}}">
		<h1>{{key.name}}</h1>
		<h2>Active: {{key.active_tickets}}</h2>
		<h2>Total: {{key.total_tickets}}</h2>
		<br>
		<br>
	</div>

	<script type="text/javascript" src="app.js"></script>
</body>

</html>
