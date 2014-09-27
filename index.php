<?php session_start(); ?>

<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="include/base64.js"></script>
	<script type="text/javascript" src="include/angular.min.js"></script>
</head>
<body ng-controller='main'>

	<div class="clef-wrapper" ng-hide="hide_it" id="Clef">
		<script type="text/javascript" src="https://clef.io/v3/clef.js" class="clef-button" data-embed="true" data-app-id="f5141de0b960fbeba62a08aeeaed6b6e" data-color="blue" data-style="flat" data-redirect-url="http://test.robert.gg/login.php"></script>
	</div>

	<div class="mailboxes" id="key.name" ng-repeat="key in mailboxes" ng-if="api_ready" style="background-color : {{key.color}}">
		<h1>{{key.name}}</h1>
		<h2>Active: {{key.active_tickets}}</h2>
		<h2>Total: {{key.total_tickets}}</h2>
		<br>
		<br>
	</div>

	<script>
		//Thanks for your time Denny!
		angular.module('helpscout_web_app', [])
			.controller('main', ['$scope','$http', '$interval', function ($scope,$http,$interval) {
				
				$scope.api_key = null;

				//I'll go with the simple object for now.
				$scope.mailboxes = {};
				$http.get('/api_key.php').success(function (data) {
					$scope.api_key = data;
					if (data != false) {
						$scope.hide_it = true;
						$scope.init();
					}
				});

				//Dunno why this request is so weird.
				var api_auth = function () {
					return 'Basic ' + Base64.encode($scope.api_key + ':X');
				}
				
				$scope.init = function () {
					$http.get('https://api.helpscout.net/v1/mailboxes.json', {headers: {'Authorization':api_auth(), 'Content-Type':'application/x-www-form-urlencoded'}}).success($scope.mailbox_controller);
				}
				
				$scope.mailbox_controller = function (data) {
					$scope.api_ready = true;

					for (i=0;i<data.items.length;i++) {

						//Just want to make sure that the id is a string.
						var mailbox_id = String(data.items[i].id);

						//I'll make this prettier later.
						$scope.mailboxes[mailbox_id] = {}
						$scope.mailboxes[mailbox_id].id = mailbox_id;
						$scope.mailboxes[mailbox_id].name = data.items[i].name;
						$scope.mailboxes[mailbox_id].email = data.items[i].email;
						$scope.mailboxes[mailbox_id].active_tickets = null;
						$scope.mailboxes[mailbox_id].pending_tickets = null;
						$scope.mailboxes[mailbox_id].folders = []
						//Takes the argument "active" or "total"
						$scope.mailboxes[mailbox_id].get_count = function (status, obj) {
							
							//Couldn't figure out how to implement "this" here, hence the
							//extra argument, but let's roll with it.

							var count = null;
							for (i=0;i<obj.folders.length;i++) {
								if ((obj.folders[i].type === 'mytickets') || (obj.folders[i].type === 'open')) {
									count = count + obj.folders[i][status+'Count'];
								}
							}
							return count;

						}

						//The way I've set this up, the only argument I will need to ever
						//pass is the mailbox_id.
						$scope.add_mailbox_listener(mailbox_id);
					}
				}
				
				$scope.add_mailbox_listener = function (mailbox_id) {
					var success_callback = function (data) {
						//Reset the color.
						$scope.mailboxes[mailbox_id].color = 'white';


						//Update the numbers from last time.
						$scope.mailboxes[mailbox_id].active_tickets_last = $scope.mailboxes[mailbox_id].active_tickets;
						$scope.mailboxes[mailbox_id].pending_tickets_last = $scope.mailboxes[mailbox_id].pending_tickets;

						for (i=0;i<data.item.folders.length;i++) {
							$scope.mailboxes[mailbox_id].folders[i] = data.item.folders[i];
						}

						$scope.mailboxes[mailbox_id].active_tickets = $scope.mailboxes[mailbox_id].get_count('active',$scope.mailboxes[mailbox_id]);
						$scope.mailboxes[mailbox_id].total_tickets = $scope.mailboxes[mailbox_id].get_count('total',$scope.mailboxes[mailbox_id]);

						//CSS animations!.
						if ($scope.mailboxes[mailbox_id].active_tickets > $scope.mailboxes[mailbox_id].active_tickets_last) {
							$scope.mailboxes[mailbox_id].color = 'lightseagreen';
						} else if ($scope.mailboxes[mailbox_id].active_tickets < $scope.mailboxes[mailbox_id].active_tickets_last) {
							$scope.mailboxes[mailbox_id].color = 'lightgreen';
						}

					}
					$interval(function () {
						$http.get('https://api.helpscout.net/v1/mailboxes/'+mailbox_id+'.json', {headers: {'Authorization':api_auth(), 'Content-Type':'application/x-www-form-urlencoded'}}).success(success_callback);
					}, 2000);
				}
			}]);
			angular.element(document).ready(function() {
				angular.bootstrap(document, ['helpscout_web_app']);
			});
	</script>
</body>
</html>
