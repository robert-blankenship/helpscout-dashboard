angular.module 'helpscout-dashboard', []
	.controller 'main', ['$scope', '$http', '$interval', ($scope, $http, $interval) ->
		
		HELPSCOUT_API_KEY = null

		$scope.mailboxes = {}
		
		$http.get('helpscout.php').success (data) ->
			HELPSCOUT_API_KEY = data
			
			if data isnt ''
				$scope.hide_it = true
				$scope.init()

		get_request_headers = ->
			'Authorization':'Basic '+Base64.encode( HELPSCOUT_API_KEY + ':X')
			'Content-Type':'application/x-www-form-urlencoded'

		$scope.init = ->
			$http.get 'https://api.helpscout.net/v1/mailboxes.json', { headers: get_request_headers() }
				.success (mailboxes) ->
					$scope.api_ready = true					
			
					for item in data.items
						mailbox_id = String item.id

						$scope.mailboxes[mailbox_id] = 
							id: mailbox_id
							name: item.name
							email: item.email
							active_tickets: null
							pending_tickets: null
							folders: []
							get_count: (status) ->
								@folders.reduce (folder, sum) ->
									if folder.type is 'mytickets' or folder.type is 'open'
										folder[status + 'Count'] + sum
									else
										sum

						add_mailbox_listener mailbox_id
		
		add_mailbox_listener = (mailbox_id) ->
			success_callback = (data) ->
				#Reset the color.
				$scope.mailboxes[mailbox_id].color = 'white';

				#Update the numbers from last time.
				$scope.mailboxes[mailbox_id].active_tickets_last = $scope.mailboxes[mailbox_id].active_tickets;
				$scope.mailboxes[mailbox_id].pending_tickets_last = $scope.mailboxes[mailbox_id].pending_tickets;

				for folder, idx in data.item.folders
					$scope.mailboxes[mailbox_id].folders[idx] = folder

				$scope.mailboxes[mailbox_id].active_tickets = $scope.mailboxes[mailbox_id].get_count 'active', $scope.mailboxes[mailbox_id]
				$scope.mailboxes[mailbox_id].total_tickets = $scope.mailboxes[mailbox_id].get_count 'total', $scope.mailboxes[mailbox_id]

				#CSS animations!.
				if $scope.mailboxes[mailbox_id].active_tickets > $scope.mailboxes[mailbox_id].active_tickets_last
					$scope.mailboxes[mailbox_id].color = 'lightseagreen'
				else if $scope.mailboxes[mailbox_id].active_tickets < $scope.mailboxes[mailbox_id].active_tickets_last
					$scope.mailboxes[mailbox_id].color = 'lightgreen'


			#We'll start the update rate off at 2000
			$interval ->
				$http.get 'https://api.helpscout.net/v1/mailboxes/' + mailbox_id + '.json' , {headers: get_request_headers()}
					.success success_callback
			, 2000

]

angular.element(document).ready ->
	angular.bootstrap document, ['helpscout-dashboard']